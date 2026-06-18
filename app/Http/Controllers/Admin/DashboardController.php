<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $stats = [
            'today_total'   => Booking::where('booking_date', $today)->count(),
            'waiting'       => Booking::where('status', 'Menunggu Konfirmasi')->count(),
            'confirmed'     => Booking::where('status', 'Dikonfirmasi')->count(),
            'done'          => Booking::where('status', 'Selesai')->count(),
            'cancelled'     => Booking::where('status', 'Dibatalkan')->count(),
            'absent'        => Booking::where('status', 'Tidak Hadir')->count(),
            'total_all'     => Booking::count(),
        ];

        $recentBookings = Booking::with(['owner', 'pet'])
            ->latest()
            ->take(10)
            ->get();

        $todayBookings = Booking::with(['owner', 'pet'])
            ->where('booking_date', $today)
            ->orderBy('booking_time')
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recentBookings', 'todayBookings'));
    }
}
