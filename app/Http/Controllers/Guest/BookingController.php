<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pet;
use App\Models\PetOwner;
use App\Models\Service;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Step 1 - Show form pemilik & hewan
     */
    public function step1()
    {
        return view('guest.booking.step1');
    }

    /**
     * Step 1 - Store data pemilik & hewan ke session
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'owner_name'     => 'required|string|max:255',
            'owner_address'  => 'required|string|max:1000',
            'owner_whatsapp' => 'required|string|max:20|regex:/^[0-9+\-\s]{8,20}$/',
            'pet_species'    => 'required|in:Kucing,Anjing,Kelinci,Burung,Hamster,Musang,Reptil,Lainnya',
            'pet_custom'     => 'required_if:pet_species,Lainnya|nullable|string|max:100',
            'pet_name'       => 'required|string|max:255',
            'pet_age'        => 'nullable|string|max:50',
        ], [
            'owner_name.required'     => 'Nama pemilik wajib diisi.',
            'owner_address.required'  => 'Alamat pemilik wajib diisi.',
            'owner_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'owner_whatsapp.regex'    => 'Format nomor WhatsApp tidak valid.',
            'pet_species.required'    => 'Jenis hewan wajib dipilih.',
            'pet_custom.required_if'  => 'Jenis hewan lainnya wajib diisi.',
            'pet_name.required'       => 'Nama hewan wajib diisi.',
        ]);

        session(['booking_step1' => $validated]);
        return redirect()->route('booking.step2');
    }

    /**
     * Step 2 - Pilih tanggal & jam
     */
    public function step2()
    {
        if (!session('booking_step1')) {
            return redirect()->route('booking.step1')->with('error', 'Silakan isi data pemilik dan hewan terlebih dahulu.');
        }

        return view('guest.booking.step2');
    }

    /**
     * Get slots JSON for AJAX
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $date   = $request->date;
        $carbon = Carbon::parse($date);

        if ($carbon->dayOfWeek === 0) {
            return response()->json(['error' => 'Kami tutup pada hari Minggu.'], 422);
        }

        $slots = BookingService::getSlotsWithStatus($date);

        return response()->json([
            'slots'    => $slots,
            'date'     => $carbon->locale('id')->isoFormat('dddd, D MMMM Y'),
            'is_today' => $carbon->isToday(),
        ]);
    }

    /**
     * Step 2 - Store jadwal ke session
     */
    public function storeStep2(Request $request)
    {
        if (!session('booking_step1')) {
            return redirect()->route('booking.step1');
        }

        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
        ], [
            'booking_date.required'       => 'Tanggal booking wajib dipilih.',
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh di masa lalu.',
            'booking_time.required'       => 'Jam booking wajib dipilih.',
            'booking_time.date_format'    => 'Format jam tidak valid.',
        ]);

        // Check Sunday
        if (Carbon::parse($validated['booking_date'])->dayOfWeek === 0) {
            return back()->withErrors(['booking_date' => 'Tidak dapat booking pada hari Minggu.']);
        }

        // Check slot availability
        if (!BookingService::isSlotAvailable($validated['booking_date'], $validated['booking_time'])) {
            return back()->withErrors(['booking_time' => 'Slot waktu yang dipilih sudah penuh. Silakan pilih waktu lain.']);
        }

        session(['booking_step2' => $validated]);
        return redirect()->route('booking.step3');
    }

    /**
     * Step 3 - Pilih layanan
     */
    public function step3()
    {
        if (!session('booking_step2')) {
            return redirect()->route('booking.step2');
        }

        $services = Service::active()->get();
        return view('guest.booking.step3', compact('services'));
    }

    /**
     * Step 3 - Store layanan ke session
     */
    public function storeStep3(Request $request)
    {
        if (!session('booking_step2')) {
            return redirect()->route('booking.step2');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
        ], [
            'service_id.required' => 'Layanan wajib dipilih.',
            'service_id.exists'   => 'Layanan tidak ditemukan.',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        session(['booking_step3' => ['service_id' => $service->id, 'service_name' => $service->name]]);

        return redirect()->route('booking.confirm');
    }

    /**
     * Step 4 - Konfirmasi booking
     */
    public function confirm()
    {
        if (!session('booking_step3')) {
            return redirect()->route('booking.step3');
        }

        $step1   = session('booking_step1');
        $step2   = session('booking_step2');
        $step3   = session('booking_step3');
        $service = Service::find($step3['service_id']);

        $bookingDate = Carbon::parse($step2['booking_date'])->locale('id')->isoFormat('dddd, D MMMM Y');

        return view('guest.booking.confirm', compact('step1', 'step2', 'step3', 'service', 'bookingDate'));
    }

    /**
     * Simpan booking ke database
     */
    public function store(Request $request)
    {
        if (!session('booking_step3')) {
            return redirect()->route('booking.step1');
        }

        $step1 = session('booking_step1');
        $step2 = session('booking_step2');
        $step3 = session('booking_step3');

        // Final availability check
        if (!BookingService::isSlotAvailable($step2['booking_date'], $step2['booking_time'])) {
            return redirect()->route('booking.step2')
                ->with('error', 'Maaf, slot yang Anda pilih baru saja terisi. Silakan pilih waktu lain.');
        }

        try {
            DB::beginTransaction();

            // Create pet owner
            $owner = PetOwner::create([
                'name'     => $step1['owner_name'],
                'address'  => $step1['owner_address'],
                'whatsapp' => $step1['owner_whatsapp'],
            ]);

            // Create pet
            $pet = Pet::create([
                'owner_id'       => $owner->id,
                'name'           => $step1['pet_name'],
                'species'        => $step1['pet_species'],
                'custom_species' => $step1['pet_custom'] ?? null,
                'age'            => $step1['pet_age'] ?? null,
            ]);

            // Create booking
            $booking = Booking::create([
                'booking_code' => Booking::generateCode(),
                'owner_id'     => $owner->id,
                'pet_id'       => $pet->id,
                'service_id'   => $step3['service_id'],
                'booking_date' => $step2['booking_date'],
                'booking_time' => $step2['booking_time'] . ':00',
                'status'       => 'Menunggu Konfirmasi',
                'notes'        => $request->notes ?? null,
            ]);

            DB::commit();

            // Clear session
            session()->forget(['booking_step1', 'booking_step2', 'booking_step3']);

            return redirect()->route('booking.success', $booking->booking_code);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi. ' . $e->getMessage());
        }
    }

    /**
     * Halaman sukses booking
     */
    public function success(string $code)
    {
        $booking = Booking::with(['owner', 'pet', 'service'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return view('guest.booking.success', compact('booking'));
    }
}
