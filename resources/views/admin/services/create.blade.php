@extends('layouts.admin')
@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header border-bottom py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Layanan Baru
                </h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Contoh: Grooming, Vaksinasi, dll">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi <small class="text-muted fw-normal">(opsional)</small></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="3" placeholder="Deskripsi singkat layanan ini">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Estimasi Harga <span class="text-danger">*</span></label>
                        <input type="text" name="estimated_price" class="form-control @error('estimated_price') is-invalid @enderror"
                            value="{{ old('estimated_price') }}" placeholder="Contoh: Mulai Rp 75.000 atau Rp 50.000 - Rp 150.000">
                        @error('estimated_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Masukkan teks estimasi harga bebas, misal: "Mulai Rp 75.000"</div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Urutan Tampil</label>
                            <input type="number" name="sort_order" class="form-control"
                                value="{{ old('sort_order', 0) }}" min="0">
                            <div class="form-text">Angka kecil tampil lebih awal</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                    value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-success" for="is_active">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Simpan Layanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
