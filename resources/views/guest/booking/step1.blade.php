@extends('layouts.guest')
@section('title', 'Booking - Langkah 1')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="step-indicator mb-4 px-3" style="gap: 0;">
                @php
                $steps = ['Data Pemilik & Hewan', 'Pilih Jadwal', 'Pilih Layanan', 'Konfirmasi'];
                @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 0 ? 'active' : '' }} flex-fill">
                    <div class="step-circle" style="{{ $i === 0 ? 'background-color: #199528; border-color: #199528;' : '' }}">
                        @if($i === 0)
                            <i class="bi bi-person-fill" style="font-size:1rem; color: white;"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label" style="{{ $i === 0 ? 'color: #199528; fw-bold' : '' }}">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header text-white py-3 px-4" style="background-color: #199528;">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Data Pemilik & Hewan
                    </h5>
                </div>
                <div class="card-body p-4" style="background-color: #FFFFFF;">
                    <form action="{{ route('booking.step1.store') }}" method="POST" id="step1Form">
                        @csrf

                        <div class="mb-4">
                            <h6 class="fw-bold mb-3" style="color: #199528;">
                                <i class="bi bi-person-circle me-2"></i>Informasi Pemilik
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                                        placeholder="Masukkan nama lengkap Anda"
                                        value="{{ old('owner_name', session('booking_step1.owner_name')) }}">
                                    @error('owner_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Alamat <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                    <textarea name="owner_address" class="form-control @error('owner_address') is-invalid @enderror"
                                        rows="2" placeholder="Masukkan alamat lengkap Anda">{{ old('owner_address', session('booking_step1.owner_address')) }}</textarea>
                                    @error('owner_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-whatsapp text-success"></i></span>
                                    <input type="text" name="owner_whatsapp" class="form-control @error('owner_whatsapp') is-invalid @enderror"
                                        placeholder="Contoh: 08123456789"
                                        value="{{ old('owner_whatsapp', session('booking_step1.owner_whatsapp')) }}">
                                    @error('owner_whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi booking.</div>
                            </div>
                        </div>

                        <hr class="my-4" style="opacity: 0.1;">

                        <div class="mb-4">
                            <h6 class="fw-bold text-success mb-3" style="color: #199528 !important;">
                                <i class="bi bi-heart-pulse me-2"></i>Informasi Hewan
                            </h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenis Hewan <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @php
                                    $species = [
                                        ['value' => 'Kucing',  'icon' => '🐱'],
                                        ['value' => 'Anjing',  'icon' => '🐶'],
                                        ['value' => 'Kelinci', 'icon' => '🐰'],
                                        ['value' => 'Burung',  'icon' => '🐦'],
                                        ['value' => 'Hamster', 'icon' => '🐹'],
                                        ['value' => 'Musang',  'icon' => '🦡'],
                                        ['value' => 'Reptil',  'icon' => '🦎'],
                                        ['value' => 'Lainnya', 'icon' => '🐾'],
                                    ];
                                    $selected = old('pet_species', session('booking_step1.pet_species'));
                                    @endphp
                                    @foreach($species as $s)
                                    <div class="col-6 col-md-3">
                                        <input type="radio" class="btn-check" name="pet_species" id="species_{{ $s['value'] }}"
                                            value="{{ $s['value'] }}" {{ $selected === $s['value'] ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success w-100 py-2 custom-radio-btn" for="species_{{ $s['value'] }}" style="border-radius:12px;">
                                            <span class="d-block fs-4 mb-1">{{ $s['icon'] }}</span>
                                            <span class="small fw-semibold">{{ $s['value'] }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @error('pet_species')
                                <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3" id="customSpeciesGroup" style="{{ old('pet_species', session('booking_step1.pet_species')) === 'Lainnya' ? '' : 'display:none;' }}">
                                <label class="form-label fw-semibold">Jenis Hewan Lainnya <span class="text-danger">*</span></label>
                                <input type="text" name="pet_custom" class="form-control @error('pet_custom') is-invalid @enderror"
                                    placeholder="Contoh: Iguana, Sugar Glider, dll"
                                    value="{{ old('pet_custom', session('booking_step1.pet_custom')) }}">
                                @error('pet_custom')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Hewan <span class="text-danger">*</span></label>
                                    <input type="text" name="pet_name" class="form-control @error('pet_name') is-invalid @enderror"
                                        placeholder="Nama hewan peliharaan"
                                        value="{{ old('pet_name', session('booking_step1.pet_name')) }}">
                                    @error('pet_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Usia Hewan <small class="text-muted fw-normal">(opsional)</small></label>
                                    <input type="text" name="pet_age" class="form-control"
                                        placeholder="Contoh: 2 tahun, 6 bulan"
                                        value="{{ old('pet_age', session('booking_step1.pet_age')) }}">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn text-white px-4 rounded-pill" style="background-color: #199528;">
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
    /* Styling tambahan untuk radio button jenis hewan agar berwarna hijau saat dipilih */
    .btn-check:checked + .custom-radio-btn {
        background-color: #199528 !important;
        border-color: #199528 !important;
        color: #FFFFFF !important;
    }
    .btn-outline-success {
        color: #199528;
        border-color: #199528;
    }
    .btn-outline-success:hover {
        background-color: #199528;
        border-color: #199528;
        color: white;
    }
</style>
@endsection

@push('scripts')
<script>
    // Show/hide custom species input
    document.querySelectorAll('input[name="pet_species"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const group = document.getElementById('customSpeciesGroup');
            if (this.value === 'Lainnya') {
                group.style.display = '';
                group.querySelector('input').required = true;
            } else {
                group.style.display = 'none';
                group.querySelector('input').required = false;
            }
        });
    });
</script>
@endpush
