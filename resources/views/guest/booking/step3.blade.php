@extends('layouts.guest')
@section('title', 'Booking - Pilih Layanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Data Pemilik & Hewan', 'Pilih Jadwal', 'Pilih Layanan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 2 ? 'active' : ($i < 2 ? 'done' : '') }} flex-fill">
                    <div class="step-circle" style="{{ $i === 2 ? 'background-color: #199528; border-color: #199528;' : ($i < 2 ? 'background-color: #199528; border-color: #199528; color: white;' : '') }}">
                        @if($i < 2)
                            <i class="bi bi-check-lg"></i>
                        @elseif($i === 2)
                            <i class="bi bi-grid-3x3-gap" style="font-size:0.9rem; color: white;"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label" style="{{ $i === 2 ? 'color: #199528; font-weight: bold;' : '' }}">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header text-white py-3 px-4" style="background-color: #199528;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Pilih Layanan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #FFFFFF;">
                    <form action="{{ route('booking.step3.store') }}" method="POST" id="step3Form">
                        @csrf

                        @php
                        $serviceIcons = [
                            'Grooming'                         => ['icon' => '✂️', 'color' => '#7C3AED', 'bg' => '#EDE9FE'],
                            'Pemeriksaan Umum'              => ['icon' => '🩺', 'color' => '#059669', 'bg' => '#D1FAE5'],
                            'Konsultasi Dokter'             => ['icon' => '💬', 'color' => '#2563EB', 'bg' => '#DBEAFE'],
                            'Vaksinasi'                     => ['icon' => '💉', 'color' => '#D97706', 'bg' => '#FEF3C7'],
                            'Sterilisasi'                   => ['icon' => '🏥', 'color' => '#DC2626', 'bg' => '#FEE2E2'],
                            'Rawat Luka'                    => ['icon' => '🩹', 'color' => '#0891B2', 'bg' => '#CFFAFE'],
                            'Pemeriksaan Kesehatan Berkala' => ['icon' => '📋', 'color' => '#65A30D', 'bg' => '#ECFCCB'],
                            'Lainnya'                       => ['icon' => '🐾', 'color' => '#6B7280', 'bg' => '#F3F4F6'],
                        ];
                        @endphp

                        <div class="row g-3 mb-4">
                            @foreach($services as $service)
                            @php
                            $config = $serviceIcons[$service->name] ?? ['icon' => '🐾', 'color' => '#6B7280', 'bg' => '#F3F4F6'];
                            $isCheck = old('service_id') == $service->id;
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <input type="radio" class="d-none service-radio" name="service_id"
                                    id="service_{{ $service->id }}" value="{{ $service->id }}"
                                    {{ $isCheck ? 'checked' : '' }}>
                                <label for="service_{{ $service->id }}" class="service-card d-block"
                                    style="{{ $isCheck ? 'border-color: #199528; background: #F0FDF4;' : '' }}">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:48px;height:48px;background:{{ $config['bg'] }};font-size:1.5rem;">
                                            {{ $config['icon'] }}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold mb-1" style="color:{{ $config['color'] }}; font-size:0.9rem;">
                                                {{ $service->name }}
                                            </div>
                                            @if($service->description)
                                            <div class="text-muted mb-2" style="font-size:0.75rem; line-height:1.4;">
                                                {{ Str::limit($service->description, 70) }}
                                            </div>
                                            @endif
                                            <div class="fw-bold" style="font-size:0.8rem; color:{{ $config['color'] }};">
                                                <i class="bi bi-tag me-1"></i>{{ $service->estimated_price }}
                                            </div>
                                        </div>
                                        <div class="checkmark-icon flex-shrink-0" style="color: #199528; opacity: {{ $isCheck ? '1' : '0' }}; transition:opacity 0.2s;">
                                            <i class="bi bi-check-circle-fill fs-5"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>

                        @error('service_id')
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ $message }}
                        </div>
                        @enderror

                        <div class="alert alert-info py-3 mb-4" style="border-radius:12px; border-left: 4px solid #0EA5E9;">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill text-info mt-1 flex-shrink-0"></i>
                                <div>
                                    <div class="fw-semibold mb-1 text-info">Informasi Harga</div>
                                    <p class="mb-0 small text-muted">
                                        Harga yang ditampilkan merupakan estimasi. Biaya akhir dapat berbeda sesuai kondisi hewan dan tindakan medis yang diperlukan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('booking.step2') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn text-white px-4 rounded-pill" id="nextBtn3" style="background-color: #199528;">
                                Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Desain dasar kartu komponen pilihan layanan */
    .service-card {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        background: white;
        transition: all 0.2s ease-in-out;
    }
    .service-card:hover {
        border-color: #199528;
        background-color: #F0FDF4;
    }
</style>
@endsection

@push('scripts')
<script>
// Highlight selected service card
document.querySelectorAll('.service-radio').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.service-card').forEach(card => {
            card.style.borderColor = '#E2E8F0';
            card.style.background = 'white';
            card.querySelector('.checkmark-icon').style.opacity = '0';
        });
        const label = document.querySelector(`label[for="${this.id}"]`);
        label.style.borderColor = '#199528';
        label.style.background = '#F0FDF4';
        label.querySelector('.checkmark-icon').style.opacity = '1';
    });
});

// Initialize already-checked card layout
document.querySelectorAll('.service-radio:checked').forEach(function(radio) {
    const label = document.querySelector(`label[for="${radio.id}"]`);
    if (label) {
        label.querySelector('.checkmark-icon').style.opacity = '1';
    }
});

document.getElementById('step3Form').addEventListener('submit', function(e) {
    const checked = document.querySelector('.service-radio:checked');
    if (!checked) {
        e.preventDefault();
        Swal.fire('Perhatian', 'Silakan pilih layanan terlebih dahulu.', 'warning');
    }
});
</script>
@endpush
