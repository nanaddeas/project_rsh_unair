@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    @php
    $statsConfig = [
        ['label' => 'Booking Hari Ini', 'value' => $stats['today_total'],  'icon' => 'calendar-today',       'color' => 'primary',  'bg' => '#EFF6FF'],
        ['label' => 'Menunggu Konfirmasi','value' => $stats['waiting'],      'icon' => 'hourglass-split',       'color' => 'warning',  'bg' => '#FFFBEB'],
        ['label' => 'Dikonfirmasi',       'value' => $stats['confirmed'],    'icon' => 'check-circle',          'color' => 'info',     'bg' => '#F0F9FF'],
        ['label' => 'Selesai',            'value' => $stats['done'],         'icon' => 'check-all',             'color' => 'success',  'bg' => '#F0FDF4'],
        ['label' => 'Dibatalkan',         'value' => $stats['cancelled'],    'icon' => 'x-circle',              'color' => 'danger',   'bg' => '#FEF2F2'],
        ['label' => 'Total Booking',      'value' => $stats['total_all'],    'icon' => 'bar-chart-line',        'color' => 'secondary','bg' => '#F8FAFC'],
    ];
    @endphp

    @foreach($statsConfig as $stat)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="stat-icon" style="background: {{ $stat['bg'] }};">
                    <i class="bi bi-{{ $stat['icon'] }} text-{{ $stat['color'] }}"></i>
                </div>
            </div>
            <div class="stat-value text-{{ $stat['color'] }}">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <!-- Today's Bookings -->
    <div class="col-lg-5">
        <div class="table-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="fw-bold">
                    <i class="bi bi-calendar-day text-primary me-2"></i>Booking Hari Ini
                </div>
                <span class="badge bg-primary">{{ $todayBookings->count() }}</span>
            </div>
            <div class="p-0">
                @if($todayBookings->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x fs-2 d-block mb-2 opacity-50"></i>
                    <p class="small mb-0">Tidak ada booking hari ini</p>
                </div>
                @else
                @foreach($todayBookings as $b)
                <div class="d-flex align-items-center gap-3 p-3 border-bottom {{ $loop->last ? '' : '' }}" style="border-color:#F1F5F9 !important;">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:40px;height:40px;">
                        <i class="bi bi-clock text-primary small"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="fw-semibold small text-truncate">{{ $b->owner->name }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $b->pet->name }} - {{ $b->pet->species }}</div>
                    </div>
                    <div class="text-end flex-shrink-0">
                        <div class="fw-bold small">{{ \Carbon\Carbon::createFromTimeString($b->booking_time)->format('H:i') }}</div>
                        <span class="badge bg-{{ $b->status_color }}" style="font-size:0.6rem;">{{ $b->status }}</span>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="col-lg-7">
        <div class="table-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="fw-bold">
                    <i class="bi bi-clock-history text-primary me-2"></i>Booking Terbaru
                </div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px; font-size:0.75rem;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            @if($recentBookings->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-2 d-block mb-2 opacity-50"></i>
                <p class="small mb-0">Belum ada data booking</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pemilik & Hewan</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>
                                <code class="text-primary small">{{ $booking->booking_code }}</code>
                            </td>
                            <td>
                                <div class="fw-semibold small">{{ $booking->owner->name }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $booking->pet->name }} - {{ $booking->pet->species }}</div>
                            </td>
                            <td>
                                <div class="small">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ \Carbon\Carbon::createFromTimeString($booking->booking_time)->format('H:i') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $booking->status_color }}">{{ $booking->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
