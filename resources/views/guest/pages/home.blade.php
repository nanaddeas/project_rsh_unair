@extends('layouts.guest')
@section('title', 'Beranda')

@section('content')

<!-- Hero Section -->
<section style="background: linear-gradient(135deg, #0F5418 0%, #199528 60%, #22C55E 100%); min-height: 85vh; display:flex; align-items:center; padding: 80px 0;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="badge bg-white text-success fw-bold mb-3 px-3 py-2 rounded-pill shadow-sm" style="color: #199528 !important;">
                    <i class="bi bi-star-fill me-1 text-warning"></i>Terpercaya & Profesional
                </div>
                <h1 class="display-4 fw-bold text-white mb-4" style="line-height:1.2;">
                    Kesehatan Hewan<br>
                    <span style="color: #ffe417;">Terjamin</span>,<br>
                    Booking Mudah
                </h1>
                <p class="text-white-50 fs-5 mb-4">
                    Booking layanan rumah sakit hewan kapan saja, tanpa perlu daftar akun. Cepat, mudah, dan langsung terkonfirmasi.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-4 rounded-pill shadow" style="background-color: #F59E0B; border-color: #F59E0B; color: white;">
                        <i class="bi bi-calendar-plus me-2"></i>Booking Sekarang
                    </a>
                    <a href="{{ route('services') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                        <i class="bi bi-grid me-2"></i>Lihat Layanan
                    </a>
                </div>

                <!-- Stats -->
                <div class="d-flex flex-wrap gap-4 mt-5">
                    <div class="text-white">
                        <div class="fw-bold fs-4">08.00 - 15.00</div>
                        <div class="small text-white-50">Jam Operasional</div>
                    </div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">{{ $services->count() }}+</div>
                        <div class="small text-white-50">Jenis Layanan</div>
                    </div>
                    <div class="text-white">
                        <div class="fw-bold fs-4">Gratis</div>
                        <div class="small text-white-50">Biaya Booking</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div style="font-size: 180px; line-height:1; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.3));">
                    🐾
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Cara Booking Mudah</h2>
            <p class="text-muted">Hanya 4 langkah sederhana untuk booking layanan</p>
        </div>
        <div class="row g-4 justify-content-center">
            @php
            $steps = [
                ['icon' => 'person-badge', 'color' => 'success', 'title' => 'Isi Data Diri', 'desc' => 'Masukkan data pemilik dan informasi hewan peliharaan Anda.'],
                ['icon' => 'calendar-week', 'color' => 'success', 'title' => 'Pilih Jadwal', 'desc' => 'Pilih tanggal dan jam yang tersedia sesuai keinginan Anda.'],
                ['icon' => 'grid-3x3-gap', 'color' => 'success', 'title' => 'Pilih Layanan', 'desc' => 'Pilih layanan yang dibutuhkan dari berbagai pilihan tersedia.'],
                ['icon' => 'check-circle', 'color' => 'success', 'title' => 'Konfirmasi', 'desc' => 'Review dan konfirmasi booking Anda. Selesai!'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="col-md-3 col-sm-6">
                <div class="text-center">
                    <div class="position-relative d-inline-block mb-4">
                        <div class="bg-{{ $step['color'] }} bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width:80px;height:80px; background-color: rgba(25, 149, 40, 0.1) !important;">
                            <i class="bi bi-{{ $step['icon'] }} fs-2" style="color: #199528 !important;"></i>
                        </div>
                        <div class="position-absolute top-0 start-100 translate-middle badge rounded-circle" style="width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:0.75rem; background-color: #199528;">
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

<!-- Services Preview -->
<section class="py-5" style="background: var(--surface, #F8FAFC);">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1">Layanan Kami</h2>
                <p class="text-muted mb-0">Berbagai layanan terbaik untuk hewan kesayangan Anda</p>
            </div>
            <a href="{{ route('services') }}" class="btn btn-outline-success" style="color: #199528; border-color: #199528;">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($services->take(6) as $service)
            @php
            $icons = ['Grooming'=>'scissors','Pemeriksaan Umum'=>'heart-pulse','Konsultasi Dokter'=>'chat-dots','Vaksinasi'=>'shield-check','Sterilisasi'=>'hospital','Rawat Luka'=>'bandaid','Pemeriksaan Kesehatan Berkala'=>'clipboard2-pulse','Lainnya'=>'plus-circle'];

            // Mengatur variasi warna bertema hijau-hijau cerah/lembut untuk keselarasan tema
            $colors = ['#199528', '#10B981', '#059669', '#34D399', '#047857', '#0F5418'];
            $icon = $icons[$service->name] ?? 'heart';
            $theme_color = $colors[$loop->index % count($colors)];
            @endphp
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm" style="border-radius:16px; background: white; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(25, 149, 40, 0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div class="card-body p-4">
                        <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3" style="width:48px;height:48px; background-color: rgba(25, 149, 40, 0.1);">
                            <i class="bi bi-{{ $icon }}" style="font-size:1.4rem; color: {{ $theme_color }};"></i>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $service->name }}</h5>
                        <p class="text-muted small mb-3">{{ $service->description ?? 'Layanan profesional untuk hewan Anda.' }}</p>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fw-semibold small" style="color: #199528;">{{ $service->estimated_price }}</span>
                            <a href="{{ route('booking.step1') }}" class="btn btn-sm text-white rounded-pill px-3" style="background-color: #199528; font-weight: 500;">
                                Booking
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5" style="background: linear-gradient(135deg, #0F5418 0%, #199528 100%);">
    <div class="container text-center py-4">
        <h2 class="fw-bold text-white mb-3">Siap Booking Sekarang?</h2>
        <p class="text-white-50 mb-4 fs-5">Tidak perlu daftar akun. Langsung booking dalam beberapa menit!</p>
        <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-5 rounded-pill shadow" style="background-color: #F59E0B; border-color: #F59E0B; color: white;">
            <i class="bi bi-calendar-plus me-2"></i>Mulai Booking
        </a>
    </div>
</section>

@endsection
