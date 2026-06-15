@extends('layouts.guest')
@section('title', 'Layanan Kami')

@section('content')

<div style="background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%); padding: 60px 0 80px;">
    <div class="container text-center text-white">
        <h1 class="fw-bold display-5 mb-3">Layanan Kami</h1>
        <p class="fs-5 text-white-50 mb-4">Berbagai pilihan layanan profesional untuk kesehatan hewan kesayangan Anda</p>
        <a href="{{ route('booking.step1') }}" class="btn btn-warning btn-lg fw-bold px-5 rounded-pill">
            <i class="bi bi-calendar-plus me-2"></i>Booking Sekarang
        </a>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @php
        $icons = ['Grooming'=>'✂️','Pemeriksaan Umum'=>'🩺','Konsultasi Dokter'=>'💬','Vaksinasi'=>'💉','Sterilisasi'=>'🏥','Rawat Luka'=>'🩹','Pemeriksaan Kesehatan Berkala'=>'📋','Lainnya'=>'🐾'];
        $colors = [['#7C3AED','#EDE9FE'],['#059669','#D1FAE5'],['#2563EB','#DBEAFE'],['#D97706','#FEF3C7'],['#DC2626','#FEE2E2'],['#0891B2','#CFFAFE'],['#65A30D','#ECFCCB'],['#6B7280','#F3F4F6']];
        @endphp
        @foreach($services as $service)
        @php $c = $colors[$loop->index % count($colors)]; @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm" style="border-radius:20px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 15px 40px rgba(0,0,0,0.13)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:{{ $c[1] }};font-size:1.8rem;flex-shrink:0;">
                            {{ $icons[$service->name] ?? '🐾' }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" style="color:{{ $c[0] }};">{{ $service->name }}</h5>
                            <div class="fw-bold small" style="color:{{ $c[0] }};">{{ $service->estimated_price }}</div>
                        </div>
                    </div>
                    @if($service->description)
                    <p class="text-muted small mb-4" style="line-height:1.6;">{{ $service->description }}</p>
                    @endif
                    <a href="{{ route('booking.step1') }}" class="btn w-100 fw-semibold" style="background:{{ $c[0] }};color:white;border-radius:12px;">
                        <i class="bi bi-calendar-plus me-2"></i>Booking Layanan Ini
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Price Notice -->
    <div class="alert alert-info mt-5 p-4" style="border-radius:16px; border-left:4px solid #0EA5E9;">
        <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-info-circle-fill text-info fs-4 flex-shrink-0"></i>
            <div>
                <h6 class="fw-bold text-info mb-1">Informasi Penting</h6>
                <p class="mb-2 text-muted">Harga yang ditampilkan merupakan estimasi. Biaya akhir dapat berbeda sesuai kondisi hewan dan tindakan medis yang diperlukan.</p>
                <p class="mb-0 text-muted small"><i class="bi bi-cash-coin me-1"></i>Pembayaran dilakukan langsung di lokasi setelah pelayanan. Kami menerima <strong>Cash</strong> dan <strong>QRIS</strong>.</p>
            </div>
        </div>
    </div>
</div>

@endsection
