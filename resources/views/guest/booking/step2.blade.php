@extends('layouts.guest')
@section('title', 'Booking - Data Pemilik & Hewan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Pilih Jadwal', 'Data Pemilik & Hewan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 1 ? 'active' : ($i < 1 ? 'done' : '') }} flex-fill">
                    <div class="step-circle">
                        @if($i < 1)
                            <i class="bi bi-check-lg"></i>
                        @elseif($i === 1)
                            <i class="bi bi-person-fill" style="font-size:1rem;"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-person-badge me-2"></i>Data Pemilik & Hewan
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('booking.step2.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bi bi-person-circle me-2"></i>Informasi Pemilik
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_nik" class="form-control @error('owner_nik') is-invalid @enderror"
                                        value="{{ old('owner_nik', session('booking_patient.owner_nik')) }}" placeholder="Nomor Induk Kependudukan">
                                    @error('owner_nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror"
                                        value="{{ old('owner_name', session('booking_patient.owner_name')) }}" placeholder="Nama lengkap">
                                    @error('owner_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_whatsapp" class="form-control @error('owner_whatsapp') is-invalid @enderror"
                                        value="{{ old('owner_whatsapp', session('booking_patient.owner_whatsapp')) }}" placeholder="Contoh: 08123456789">
                                    @error('owner_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Alamat Domisili <span class="text-danger">*</span></label>
                                    <textarea name="owner_address" class="form-control @error('owner_address') is-invalid @enderror"
                                        rows="2" placeholder="Alamat domisili">{{ old('owner_address', session('booking_patient.owner_address')) }}</textarea>
                                    @error('owner_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-heart-pulse me-2"></i>Informasi Hewan
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Hewan <span class="text-danger">*</span></label>
                                    <input type="text" name="pet_name" class="form-control @error('pet_name') is-invalid @enderror"
                                        value="{{ old('pet_name', session('booking_patient.pet_name')) }}" placeholder="Nama hewan">
                                    @error('pet_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                    <select name="pet_species" class="form-select @error('pet_species') is-invalid @enderror">
                                        <option value="">Pilih jenis hewan</option>
                                        @foreach(['Kucing', 'Anjing', 'Kelinci', 'Burung', 'Hamster', 'Musang', 'Reptil', 'Lainnya'] as $species)
                                        <option value="{{ $species }}" {{ old('pet_species', session('booking_patient.pet_species')) === $species ? 'selected' : '' }}>{{ $species }}</option>
                                        @endforeach
                                    </select>
                                    @error('pet_species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Ras</label>
                                    <input type="text" name="pet_breed" class="form-control"
                                        value="{{ old('pet_breed', session('booking_patient.pet_breed')) }}" placeholder="Contoh: Persia, Lokal">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="pet_gender" class="form-select @error('pet_gender') is-invalid @enderror">
                                        <option value="">Pilih jenis kelamin</option>
                                        @foreach(['Jantan', 'Betina'] as $gender)
                                        <option value="{{ $gender }}" {{ old('pet_gender', session('booking_patient.pet_gender')) === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                    @error('pet_gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Usia</label>
                                    <input type="text" name="pet_age" class="form-control"
                                        value="{{ old('pet_age', session('booking_patient.pet_age')) }}" placeholder="Contoh: 2 tahun, 6 bulan">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Warna Bulu</label>
                                    <input type="text" name="pet_fur_color" class="form-control"
                                        value="{{ old('pet_fur_color', session('booking_patient.pet_fur_color')) }}" placeholder="Contoh: Putih coklat">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('booking.step1') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                Lanjut Konfirmasi <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
