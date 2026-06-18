@extends('layouts.guest')
@section('title', 'Layanan Kami')

@section('content')

<!-- Header Section -->
<div style="background: linear-gradient(135deg, #0F5418 0%, #199528 100%); padding: 60px 0 80px;">
    <div class="container text-center text-white">
        <h1 class="fw-bold display-5 mb-3">Layanan Kami</h1>
        <p class="fs-5 text-white-50 mb-4">Berbagai pilihan layanan profesional untuk kesehatan hewan kesayangan Anda</p>
        <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-5 rounded-pill shadow" style="background-color: #F59E0B; border-color: #F59E0B; color: white;">
            <i class="bi bi-calendar-plus me-2"></i>Booking Sekarang
        </a>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @php
        $icons = ['Grooming'=>'✂️','Pemeriksaan Umum'=>'🩺','Konsultasi Dokter'=>'💬','Vaksinasi'=>'💉','Sterilisasi'=>'🏥','Rawat Luka'=>'🩹','Pemeriksaan Kesehatan Berkala'=>'📋','Lainnya'=>'🐾'];

        // Variasi warna tombol & background icon bertema hijau yang serasi
        // Format: [Warna_Teks_&_Tombol, Warna_Background_Icon]
        $colors = [
            ['#199528', '#E8F5E9'],
            ['#10B981', '#D1FAE5'],
            ['#059669', '#E6F4EA'],
            ['#047857', '#D1E7DD'],
            ['#12731E', '#EAF5EA'],
            ['#22C55E', '#DCFCE7'],
            ['#0F5418', '#E2EF text-opacity-10'],
            ['#15803D', '#DCFCE7']
        ];
        @endphp

        @foreach($services as $service)
        @php
            $c = $colors[$loop->index % count($colors)];
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius:20px; background: white; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 15px 40px rgba(25, 149, 40, 0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px; background-color: {{ $c[1] }}; font-size:1.8rem; flex-shrink:0;">
                            {{ $icons[$service->name] ?? '🐾' }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color: {{ $c[0] }};">{{ $service->name }}</h5>
                            <div class="fw-bold small" style="color: #199528;">{{ $service->estimated_price }}</div>
                        </div>
                    </div>
                    @if($service->description)
                    <p class="text-muted small mb-4" style="line-height:1.6; min-height: 48px;">{{ $service->description }}</p>
                    @else
                    <p class="text-muted small mb-4" style="line-height:1.6; min-height: 48px;">Layanan profesional terbaik untuk kesehatan hewan kesayangan Anda.</p>
                    @endif
                    <a href="{{ route('booking.step1') }}" class="btn w-100 fw-semibold text-white shadow-sm" style="background-color: {{ $c[0] }}; border-radius:12px; transition: filter 0.2s;" onmouseover="this.style.filter='brightness(90%)'" onmouseout="this.style.filter='none'">
                        <i class="bi bi-calendar-plus me-2"></i>Booking Layanan Ini
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Price Notice -->
    <div class="alert alert-success mt-5 p-4 shadow-sm" style="border-radius:16px; border-left:4px solid #199528; background-color: #E8F5E9; border-color: transparent;">
        <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-info-circle-fill fs-4 flex-shrink-0" style="color: #199528;"></i>
            <div>
                <h6 class="fw-bold mb-1" style="color: #12731E;">Informasi Penting</h6>
                <p class="mb-2 text-muted-dark small" style="color: #1E293B;">Harga yang ditampilkan merupakan estimasi. Biaya akhir dapat berbeda sesuai kondisi hewan dan tindakan medis yang diperlukan langsung di lapangan.</p>
                <p class="mb-0 small" style="color: #12731E;"><i class="bi bi-cash-coin me-1"></i>Pembayaran dilakukan langsung di lokasi setelah pelayanan. Kami menerima <strong>Cash</strong> dan <strong>QRIS</strong>.</p>
            </div>
        </div>
    </div>
</div>

@endsection
