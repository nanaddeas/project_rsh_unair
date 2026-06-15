<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class BookingService
{
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
        $bookedQuery = Booking::where('booking_date', $date)
            ->whereIn('status', ['Menunggu Konfirmasi', 'Dikonfirmasi']);

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
            ->where('booking_time', $formattedTime . ':00')
            ->whereIn('status', ['Menunggu Konfirmasi', 'Dikonfirmasi']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->count() === 0;
    }
}
