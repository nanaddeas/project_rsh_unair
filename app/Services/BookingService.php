<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public const SESSION_DURATION_MINUTES = 20;

    /**
     * Generate all time slots for a given date
     */
    public static function generateSlots(string $date): array
    {
        $carbon = Carbon::parse($date);
        $dayOfWeek = $carbon->dayOfWeek; // 0=Sun, 1=Mon, ..., 5=Fri, 6=Sat

        // Closed on Sunday
        if ($dayOfWeek === 0) {
            return [];
        }

        // Determine break time
        // Friday: 11:30 - 12:30
        // Mon-Thu & Sat: 12:00 - 13:00
        $breakStart = ($dayOfWeek === 5) ? '11:30' : '12:00';
        $breakEnd   = ($dayOfWeek === 5) ? '12:30' : '13:00';

        $slots = [];
        $current = Carbon::createFromTimeString('08:00');
        $end = Carbon::createFromTimeString('15:00');
        $breakStartTime = Carbon::createFromTimeString($breakStart);
        $breakEndTime   = Carbon::createFromTimeString($breakEnd);

        while ($current->lt($end)) {
            $timeStr = $current->format('H:i');

            // Skip break time
            $isBreak = $current->gte($breakStartTime) && $current->lt($breakEndTime);

            if (!$isBreak) {
                $slots[] = $timeStr;
            }

            $current->addMinutes(20);
        }

        return $slots;
    }

    /**
     * Get slots with booking status for a date
     */
    public static function getSlotsWithStatus(string $date, ?int $excludeBookingId = null): array
    {
        $allSlots = self::generateSlots($date);

        // Get booked times for this date
        $bookedQuery = Booking::where('booking_date', $date);

        if ($excludeBookingId) {
            $bookedQuery->where('id', '!=', $excludeBookingId);
        }

        $bookedTimes = $bookedQuery->pluck('booking_time')
            ->map(fn($t) => Carbon::createFromTimeString($t)->format('H:i'))
            ->toArray();

        $result = [];
        foreach ($allSlots as $slot) {
            $result[] = [
                'time'   => $slot,
                'status' => in_array($slot, $bookedTimes) ? 'penuh' : 'tersedia',
            ];
        }

        return $result;
    }

    /**
     * Check if a slot is available
     */
    public static function isSlotAvailable(string $date, string $time, ?int $excludeBookingId = null): bool
    {
        $allSlots = self::generateSlots($date);
        $formattedTime = Carbon::createFromTimeString($time)->format('H:i');

        if (!in_array($formattedTime, $allSlots)) {
            return false;
        }

        $query = Booking::where('booking_date', $date)
            ->where('booking_time', $formattedTime . ':00');

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }

    /**
     * Mark unattended bookings as no-show after their 20-minute session ends.
     */
    public static function markExpiredBookingsAsNoShow(?Carbon $now = null): int
    {
        $now ??= Carbon::now();

        $expiredIds = Booking::query()
            ->whereIn('status', ['Menunggu Konfirmasi', 'Dikonfirmasi'])
            ->whereDate('booking_date', '<=', $now->toDateString())
            ->get(['id', 'booking_date', 'booking_time'])
            ->filter(function (Booking $booking) use ($now) {
                $sessionEndsAt = Carbon::parse(
                    $booking->booking_date->format('Y-m-d').' '.$booking->booking_time
                )->addMinutes(self::SESSION_DURATION_MINUTES);

                return $sessionEndsAt->lte($now);
            })
            ->pluck('id');

        if ($expiredIds->isEmpty()) {
            return 0;
        }

        return Booking::whereKey($expiredIds)->update(['status' => 'Tidak Hadir']);
    }

    public static function classifyPatient(array $data): array
    {
        $owner = PetOwner::where('nik', $data['owner_nik'])->latest('id')->first();
        $pet = null;

        if (! empty($data['existing_pet_id'])) {
            $pet = Pet::whereKey($data['existing_pet_id'])
                ->whereHas('owner', fn ($query) => $query->where('nik', $data['owner_nik']))
                ->first();

            if (! $pet) {
                throw ValidationException::withMessages([
                    'existing_pet_id' => 'Pasien lama yang dipilih tidak sesuai dengan NIK pemilik.',
                ]);
            }
        }

        $classified = array_merge($data, [
            'client_type' => $owner ? 'Lama' : 'Baru',
            'patient_type' => $pet ? 'Lama' : 'Baru',
        ]);

        if ($pet) {
            $classified = array_merge($classified, [
                'pet_name' => $pet->name,
                'pet_species' => $pet->species,
                'pet_breed' => $pet->breed,
                'pet_gender' => $pet->gender,
                'pet_age' => $pet->age,
                'pet_fur_color' => $pet->fur_color,
            ]);
        }

        return $classified;
    }

    public static function resolveOwnerAndPet(array $data): array
    {
        $data = self::classifyPatient($data);
        $pet = ! empty($data['existing_pet_id'])
            ? Pet::with('owner')->whereKey($data['existing_pet_id'])
                ->whereHas('owner', fn ($query) => $query->where('nik', $data['owner_nik']))
                ->firstOrFail()
            : null;
        $owner = $pet?->owner
            ?? PetOwner::where('nik', $data['owner_nik'])->latest('id')->first();

        if ($owner) {
            $owner->update([
                'name' => $data['owner_name'],
                'address' => $data['owner_address'],
                'whatsapp' => $data['owner_whatsapp'],
            ]);
        } else {
            $owner = PetOwner::create([
                'nik' => $data['owner_nik'],
                'name' => $data['owner_name'],
                'address' => $data['owner_address'],
                'whatsapp' => $data['owner_whatsapp'],
            ]);
        }

        $pet ??= Pet::create([
                'owner_id' => $owner->id,
                'name' => $data['pet_name'],
                'species' => $data['pet_species'],
                'breed' => $data['pet_breed'] ?? null,
                'gender' => $data['pet_gender'],
                'age' => $data['pet_age'] ?? null,
                'fur_color' => $data['pet_fur_color'] ?? null,
            ]);

        return [$owner, $pet, $data['client_type'], $data['patient_type']];
    }

    public static function lookupOwnerByNik(string $nik): array
    {
        $owners = PetOwner::where('nik', $nik)->latest('id')->get();

        if ($owners->isEmpty()) {
            return ['found' => false, 'pets' => []];
        }

        $owner = $owners->first();
        $pets = Pet::whereIn('owner_id', $owners->pluck('id'))
            ->orderBy('name')
            ->get()
            ->map->only(['id', 'name', 'species', 'breed', 'gender', 'age', 'fur_color'])
            ->values();

        return [
            'found' => true,
            'owner' => $owner->only(['name', 'whatsapp', 'address']),
            'pets' => $pets,
        ];
    }
}
