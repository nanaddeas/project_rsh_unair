<?php

namespace App\Http\Controllers\Admin;

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
    public function index(Request $request)
    {
        BookingService::markExpiredBookingsAsNoShow();
        $query = $this->filteredQuery($request);

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function export(Request $request)
    {
        BookingService::markExpiredBookingsAsNoShow();
        $bookings = $this->filteredQuery($request)->latest()->get();
        $filename = 'data-booking-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'Kode Booking', 'Tanggal', 'Jam', 'Status', 'Status Klien',
                'NIK', 'Nama Pemilik', 'WhatsApp', 'Alamat', 'Status Pasien',
                'Nama Hewan', 'Jenis', 'Ras', 'Jenis Kelamin', 'Usia',
                'Warna Bulu', 'Catatan',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($output, [
                    $booking->booking_code,
                    $booking->booking_date->format('d-m-Y'),
                    $booking->formatted_time,
                    $booking->status,
                    $booking->client_type,
                    $booking->owner->nik,
                    $booking->owner->name,
                    $booking->owner->whatsapp,
                    $booking->owner->address,
                    $booking->patient_type,
                    $booking->pet->name,
                    $booking->pet->species,
                    $booking->pet->breed,
                    $booking->pet->gender,
                    $booking->pet->age,
                    $booking->pet->fur_color,
                    $booking->notes,
                ]);
            }

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function create()
    {
        return view('admin.bookings.create');
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
        ]);
    }

    public function lookupOwner(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|digits_between:12,20',
        ]);

        return response()->json(BookingService::lookupOwnerByNik($validated['nik']));
    }

    public function store(Request $request)
    {
        $validated = $this->validateManualBooking($request);

        if (! BookingService::isSlotAvailable($validated['booking_date'], $validated['booking_time'])) {
            return back()->withErrors(['booking_time' => 'Slot waktu yang dipilih sudah penuh. Silakan pilih waktu lain.'])->withInput();
        }

        try {
            DB::beginTransaction();

            [$owner, $pet, $clientType, $patientType] = BookingService::resolveOwnerAndPet($validated);

            $booking = Booking::create([
                'booking_code' => Booking::generateCode(),
                'owner_id' => $owner->id,
                'pet_id' => $pet->id,
                'client_type' => $clientType,
                'patient_type' => $patientType,
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'] . ':00',
                'status' => $validated['status'] ?? 'Menunggu Konfirmasi',
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', "Booking manual <strong>{$booking->booking_code}</strong> berhasil dibuat.");
        } catch (QueryException $e) {
            DB::rollBack();

            return back()->withErrors(['booking_time' => 'Slot waktu yang dipilih sudah penuh. Silakan pilih waktu lain.'])->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat menyimpan booking manual.')->withInput();
        }
    }

    public function show(Booking $booking)
    {
        BookingService::markExpiredBookingsAsNoShow();
        $booking->refresh();
        $booking->load(['owner', 'pet']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Booking::STATUSES),
        ]);

        $booking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking berhasil diperbarui menjadi <strong>' . $request->status . '</strong>.');
    }

    public function destroy(Booking $booking)
    {
        $code = $booking->booking_code;
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', "Booking <strong>{$code}</strong> berhasil dihapus.");
    }

    private function validateManualBooking(Request $request): array
    {
        $validated = $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
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
            'status' => 'required|in:' . implode(',', Booking::STATUSES),
            'notes' => 'nullable|string|max:2000',
        ]);

        if (Carbon::parse($validated['booking_date'])->dayOfWeek === 0) {
            throw ValidationException::withMessages([
                'booking_date' => 'Tidak dapat booking pada hari Minggu.',
            ]);
        }

        return BookingService::classifyPatient($validated);
    }

    private function filteredQuery(Request $request)
    {
        $query = Booking::with(['owner', 'pet']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('owner', fn ($owner) => $owner
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%"))
                    ->orWhereHas('pet', fn ($pet) => $pet->where('name', 'like', "%{$search}%"))
                    ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        if ($request->filled('client_type')) {
            $query->where('client_type', $request->client_type);
        }

        if ($request->filled('patient_type')) {
            $query->where('patient_type', $request->patient_type);
        }

        return $query;
    }
}
