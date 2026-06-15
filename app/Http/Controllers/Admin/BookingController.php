<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['owner', 'pet', 'service']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('owner', fn($o) => $o->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('pet', fn($p) => $p->where('name', 'like', "%{$search}%"))
                  ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->where('booking_date', $request->date);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['owner', 'pet', 'service']);
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
}
