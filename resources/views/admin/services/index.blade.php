@extends('layouts.admin')
@section('title', 'Manajemen Layanan')
@section('page-title', 'Manajemen Layanan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-muted mb-0 small">Kelola daftar layanan yang tersedia untuk pelanggan</p>
    </div>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>Tambah Layanan
    </a>
</div>

<div class="table-card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold">
            <i class="bi bi-grid-3x3-gap text-primary me-2"></i>
            Daftar Layanan
            <span class="badge bg-primary ms-2">{{ $services->total() }}</span>
        </div>
    </div>

    @if($services->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
        <p class="mb-3">Belum ada layanan yang ditambahkan</p>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Layanan Pertama
        </a>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width:60px;">Urutan</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Estimasi Harga</th>
                    <th>Status</th>
                    <th>Booking</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td class="text-center">
                        <div class="badge bg-light text-muted fw-bold">{{ $service->sort_order }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $service->name }}</div>
                    </td>
                    <td>
                        <div class="text-muted small" style="max-width:250px;">
                            {{ $service->description ? Str::limit($service->description, 80) : '-' }}
                        </div>
                    </td>
                    <td>
                        <span class="text-success fw-semibold">{{ $service->estimated_price }}</span>
                    </td>
                    <td>
                        @if($service->is_active)
                        <span class="badge bg-success">Aktif</span>
                        @else
                        <span class="badge bg-secondary">Non-aktif</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $service->bookings()->count() }} booking
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.services.edit', $service) }}"
                                class="btn btn-sm btn-outline-primary"
                                style="border-radius:8px;" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($service->bookings()->count() === 0)
                            <form action="{{ route('admin.services.destroy', $service) }}" method="POST" id="svc-del-{{ $service->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete"
                                    style="border-radius:8px;"
                                    data-form="svc-del-{{ $service->id }}"
                                    data-confirm="Layanan '{{ $service->name }}' akan dihapus."
                                    title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @else
                            <button class="btn btn-sm btn-outline-secondary disabled" style="border-radius:8px;" title="Tidak bisa dihapus (ada booking)">
                                <i class="bi bi-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($services->hasPages())
    <div class="p-4 border-top">
        {{ $services->links('pagination::bootstrap-5') }}
    </div>
    @endif
    @endif
</div>

@endsection
