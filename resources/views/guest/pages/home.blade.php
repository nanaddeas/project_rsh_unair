@extends('layouts.guest')
@section('title', 'Beranda')

@section('content')
<section style="background: linear-gradient(135deg, #0F5418 0%, #199528 60%, #22C55E 100%); min-height: 85vh; display:flex; align-items:center; padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="badge bg-white fw-bold mb-3 px-3 py-2 rounded-pill shadow-sm" style="color:#199528;">
                    <i class="bi bi-calendar-check-fill me-1 text-warning"></i>Booking Jadwal Praktis
                </div>
                <h1 class="display-4 fw-bold text-white mb-4" style="line-height:1.2;">
                    Pilih Jadwal,<br>
                    Isi Data Pasien,<br>
                    <span style="color: #ffe417;">Selesai</span>
                </h1>
                <p class="text-white-50 fs-5 mb-4">
                    Booking rumah sakit hewan tanpa perlu memilih layanan atau estimasi harga. Cukup pilih slot 20 menit yang tersedia, lalu lengkapi data pemilik dan hewan.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-4 rounded-pill shadow">
                        <i class="bi bi-calendar-plus me-2"></i>Booking Sekarang
                    </a>
                </div>

                <div class="d-flex flex-wrap gap-4 mt-5">
                    <div class="text-white">
                        <div class="fw-bold fs-4">20 Menit</div>
                        <div class="small text-white-50">Per Sesi</div>
                    </div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">08.00 - 15.00</div>
                        <div class="small text-white-50">Jam Operasional</div>
                    </div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">Tanpa Akun</div>
                        <div class="small text-white-50">Langsung Booking</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div style="font-size: 150px; line-height:1; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));">
                    <i class="bi bi-heart-pulse-fill text-white"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Cara Booking Mudah</h2>
            <p class="text-muted">Alur baru lebih singkat dan langsung ke data pasien.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $steps = [
                ['icon' => 'calendar-week', 'color' => 'success', 'title' => 'Pilih Jadwal', 'desc' => 'Pilih tanggal dan slot 20 menit yang masih tersedia.'],
                ['icon' => 'person-badge', 'color' => 'success', 'title' => 'Isi Data', 'desc' => 'Lengkapi NIK, kontak, alamat domisili, serta data hewan.'],
                ['icon' => 'check-circle', 'color' => 'success', 'title' => 'Konfirmasi', 'desc' => 'Periksa ringkasan lalu kirim booking.'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="col-md-4 col-sm-6">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px;">
                            <i class="bi bi-{{ $step['icon'] }} fs-2" style="color:#199528;"></i>
                        </div>
                        <div class="position-absolute top-0 start-100 translate-middle badge rounded-circle" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:0.75rem;background-color:#199528;">
                            {{ $i+1 }}
                        </div>
                    </div>
                    <h5 class="fw-bold">{{ $step['title'] }}</h5>
                    <p class="text-muted small">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5" style="background: var(--surface);">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="bi bi-clock-history fs-1 mb-3 d-block" style="color:#199528;"></i>
                        <h5 class="fw-bold">Slot 20 Menit</h5>
                        <p class="text-muted small mb-0">Sistem otomatis menampilkan slot tersedia dan slot penuh berdasarkan jadwal booking.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="bi bi-clipboard2-pulse text-success fs-1 mb-3 d-block"></i>
                        <h5 class="fw-bold">Data Pasien Lengkap</h5>
                        <p class="text-muted small mb-0">Data pemilik dan hewan tersimpan lengkap untuk kebutuhan administrasi rumah sakit hewan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <i class="bi bi-shield-check fs-1 mb-3 d-block" style="color:#199528;"></i>
                        <h5 class="fw-bold">Tanpa Layanan & Harga</h5>
                        <p class="text-muted small mb-0">Booking fokus pada reservasi waktu. Detail layanan dan biaya bisa ditentukan saat pasien datang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background: linear-gradient(135deg, #0F5418 0%, #199528 100%);">
    <div class="container text-center py-4">
        <h2 class="fw-bold text-white mb-3">Siap Booking Sekarang?</h2>
        <p class="text-white-50 mb-4 fs-5">Pilih jadwal terlebih dahulu, lalu isi data pasien.</p>
        <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-5 rounded-pill shadow">
            <i class="bi bi-calendar-plus me-2"></i>Mulai Booking
        </a>
    </div>
</section>
@endsection
