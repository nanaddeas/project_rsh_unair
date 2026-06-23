<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function step1()
    {
        return view('guest.booking.step1');
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date = $request->date;
        $carbon = Carbon::parse($date);

        if ($carbon->dayOfWeek === 0) {
            return response()->json(['error' => 'Kami tutup pada hari Minggu.'], 422);
        }

        return response()->json([
            'slots' => BookingService::getSlotsWithStatus($date),
            'date' => $carbon->locale('id')->isoFormat('dddd, D MMMM Y'),
            'is_today' => $carbon->isToday(),
        ]);
    }

    public function lookupOwner(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits_between:12,20',
        ]);

        return response()->json(BookingService::lookupOwnerByNik($validated['nik']));
    }

    public function storeStep1(Request $request)
    {
        $validated = $this->validateSchedule($request);

        if (! BookingService::isSlotAvailable($validated['booking_date'], $validated['booking_time'])) {
            return back()->withErrors(['booking_time' => 'Slot waktu yang dipilih sudah penuh. Silakan pilih waktu lain.']);
        }

        session(['booking_schedule' => $validated]);

        return redirect()->route('booking.step2');
    }

    public function step2()
    {
        if (! session('booking_schedule')) {
            return redirect()->route('booking.step1')->with('error', 'Silakan pilih tanggal dan jam booking terlebih dahulu.');
        }

        return view('guest.booking.step2');
    }

    public function storeStep2(Request $request)
    {
        if (! session('booking_schedule')) {
            return redirect()->route('booking.step1');
        }

        session(['booking_patient' => $this->validatePatient($request)]);

        return redirect()->route('booking.confirm');
    }

    public function confirm()
    {
        if (! session('booking_patient')) {
            return redirect()->route('booking.step2');
        }

        $schedule = session('booking_schedule');
        $patient = session('booking_patient');
        $bookingDate = Carbon::parse($schedule['booking_date'])->locale('id')->isoFormat('dddd, D MMMM Y');

        return view('guest.booking.confirm', compact('schedule', 'patient', 'bookingDate'));
    }

    public function store(Request $request)
    {
        if (! session('booking_patient')) {
            return redirect()->route('booking.step1');
        }

        $schedule = session('booking_schedule');
        $patient = session('booking_patient');

        if (! BookingService::isSlotAvailable($schedule['booking_date'], $schedule['booking_time'])) {
            return redirect()->route('booking.step1')
                ->with('error', 'Maaf, slot yang Anda pilih baru saja terisi. Silakan pilih waktu lain.');
        }

        try {
            DB::beginTransaction();

            [$owner, $pet, $clientType, $patientType] = BookingService::resolveOwnerAndPet($patient);

            $booking = Booking::create([
                'booking_code' => Booking::generateCode(),
                'owner_id' => $owner->id,
                'pet_id' => $pet->id,
                'client_type' => $clientType,
                'patient_type' => $patientType,
                'booking_date' => $schedule['booking_date'],
                'booking_time' => $schedule['booking_time'] . ':00',
                'status' => 'Menunggu Konfirmasi',
                'notes' => $request->notes,
            ]);

            DB::commit();

            session()->forget(['booking_schedule', 'booking_patient']);

            return redirect()->route('booking.success', $booking->booking_code);
        } catch (QueryException $e) {
            DB::rollBack();

            return redirect()->route('booking.step1')
                ->with('error', 'Maaf, slot yang Anda pilih baru saja terisi. Silakan pilih waktu lain.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function success(string $code)
    {
        $booking = Booking::with(['owner', 'pet'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return view('guest.booking.success', compact('booking'));
    }

    private function validateSchedule(Request $request): array
    {
        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ], [
            'booking_date.required' => 'Tanggal booking wajib dipilih.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'booking_time.required' => 'Jam booking wajib dipilih.',
            'booking_time.date_format' => 'Format jam tidak valid.',
        ]);

        if (Carbon::parse($validated['booking_date'])->dayOfWeek === 0) {
            throw ValidationException::withMessages([
                'booking_date' => 'Tidak dapat booking pada hari Minggu.',
            ]);
        }

        return $validated;
    }

    private function validatePatient(Request $request): array
    {
        $validated = $request->validate([
            'owner_nik' => 'required|digits_between:12,20',
            'owner_name' => 'required|string|max:255',
            'owner_whatsapp' => 'required|string|max:20|regex:/^[0-9+\-\s]{8,20}$/',
            'owner_address' => 'required|string|max:1000',
            'existing_pet_id' => 'nullable|integer',
            'pet_name' => 'nullable|required_without:existing_pet_id|string|max:255',
            'pet_species' => 'nullable|required_without:existing_pet_id|string|max:100',
            'pet_breed' => 'nullable|string|max:100',
            'pet_gender' => 'nullable|required_without:existing_pet_id|in:Jantan,Betina',
            'pet_age' => 'nullable|string|max:50',
            'pet_fur_color' => 'nullable|string|max:100',
        ], [
            'owner_nik.required' => 'NIK wajib diisi.',
            'owner_nik.digits_between' => 'NIK harus berisi 12 sampai 20 digit angka.',
            'owner_name.required' => 'Nama pemilik wajib diisi.',
            'owner_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'owner_whatsapp.regex' => 'Format nomor WhatsApp tidak valid.',
            'owner_address.required' => 'Alamat domisili wajib diisi.',
            'pet_name.required' => 'Nama hewan wajib diisi.',
            'pet_species.required' => 'Jenis hewan wajib diisi.',
            'pet_gender.required' => 'Jenis kelamin hewan wajib dipilih.',
            'pet_gender.in' => 'Jenis kelamin hewan tidak valid.',
        ]);

        return BookingService::classifyPatient($validated);
    }

}
