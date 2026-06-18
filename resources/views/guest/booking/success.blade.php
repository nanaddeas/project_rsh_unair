@extends('layouts.guest')
@section('title', 'Booking Berhasil!')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="text-center mb-4">
                <div class="mb-3" style="font-size:5rem; animation: bounceIn 0.6s ease;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
                <h2 class="fw-bold text-success">Booking Berhasil!</h2>
                <p class="text-muted">Terima kasih! Booking Anda telah diterima dan menunggu konfirmasi.</p>
            </div>

            <div class="card border-0 mb-4" style="background: linear-gradient(135deg, #2563EB, #7C3AED); border-radius:20px;">
                <div class="card-body text-center py-4">
                    <div class="text-white-50 small fw-semibold mb-2">KODE BOOKING</div>
                    <div class="text-white fw-bold" style="font-size: 2rem; letter-spacing: 4px; font-family: monospace;">
                        {{ $booking->booking_code }}
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                            <i class="bi bi-clock me-1"></i>{{ $booking->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-receipt me-2 text-primary"></i>Detail Reservasi
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1">Tanggal Booking</div>
                                    <div class="fw-bold">
                                        <i class="bi bi-calendar-event text-primary me-2"></i>
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="text-muted small mb-1">Jam Booking</div>
                                    <div class="fw-bold">
                                        <i class="bi bi-clock text-primary me-2"></i>
                                        {{ \Carbon\Carbon::createFromTimeString($booking->booking_time)->format('H:i') }} WIB
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-circle me-2"></i>Data Pemilik
                        </h6>
                        <div class="row g-2">
                            <div class="col-4 text-muted small">NIK</div>
                            <div class="col-8 fw-semibold">{{ $booking->owner->nik }}</div>
                            <div class="col-4 text-muted small">Nama</div>
                            <div class="col-8 fw-semibold">{{ $booking->owner->name }}</div>
                            <div class="col-4 text-muted small">WhatsApp</div>
                            <div class="col-8">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->owner->whatsapp) }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="bi bi-whatsapp me-1"></i>{{ $booking->owner->whatsapp }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-4">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="bi bi-heart-pulse me-2"></i>Data Hewan
                        </h6>
                        <div class="row g-2">
                            <div class="col-4 text-muted small">Nama</div>
                            <div class="col-8 fw-semibold">{{ $booking->pet->name }}</div>
                            <div class="col-4 text-muted small">Jenis</div>
                            <div class="col-8">{{ $booking->pet->species }}</div>
                            <div class="col-4 text-muted small">Ras</div>
                            <div class="col-8">{{ $booking->pet->breed ?: '-' }}</div>
                            <div class="col-4 text-muted small">Jenis Kelamin</div>
                            <div class="col-8">{{ $booking->pet->gender }}</div>
                            <div class="col-4 text-muted small">Usia</div>
                            <div class="col-8">{{ $booking->pet->age ?: '-' }}</div>
                            <div class="col-4 text-muted small">Warna Bulu</div>
                            <div class="col-8">{{ $booking->pet->fur_color ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning mb-4" style="border-radius:16px; border-left:4px solid #F59E0B;">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Hal Penting</h6>
                <ul class="mb-0 small">
                    <li>Simpan kode booking Anda: <strong>{{ $booking->booking_code }}</strong></li>
                </ul>
            </div>

            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <button onclick="window.print()" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-printer me-2"></i>Cetak Bukti Booking
                </button>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>Kembali ke Beranda
                </a>
                <a href="{{ route('booking.step1') }}" class="btn btn-outline-success btn-lg">
                    <i class="bi bi-calendar-plus me-2"></i>Booking Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@keyframes bounceIn {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1); }
}

@media print {
    .navbar-custom, footer, .d-flex.gap-3, .btn { display: none !important; }
    .container { max-width: 100%; }
    .card { border: 1px solid #ddd !important; box-shadow: none !important; }
}
</style>
@endpush
