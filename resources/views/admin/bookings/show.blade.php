@extends('layouts.admin')
@section('title', 'Detail Booking - ' . $booking->booking_code)
@section('page-title', 'Detail Booking')

@section('content')

<div class="row g-4">
    <!-- Main Info -->
    <div class="col-lg-8">

        <!-- Booking Header -->
        <div class="card shadow-sm mb-4" style="background: linear-gradient(135deg, #1E3A8A, #2563EB); border-radius:20px;">
            <div class="card-body p-4 text-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <div class="opacity-75 small mb-1">Kode Booking</div>
                        <div class="fw-bold" style="font-size:1.5rem; font-family:monospace; letter-spacing:3px;">
                            {{ $booking->booking_code }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="opacity-75 small mb-1">Status Saat Ini</div>
                        <span class="badge bg-{{ $booking->status_color }} px-3 py-2 fs-6">
                            <i class="bi bi-{{ $booking->status_icon }} me-1"></i>
                            {{ $booking->status }}
                        </span>
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-sm-4">
                        <div class="opacity-75 small">Tanggal</div>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->isoFormat('ddd, D MMM Y') }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="opacity-75 small">Jam</div>
                        <div class="fw-semibold">{{ \Carbon\Carbon::createFromTimeString($booking->booking_time)->format('H:i') }} WIB</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="opacity-75 small">Dibuat</div>
                        <div class="fw-semibold">{{ $booking->created_at->locale('id')->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemilik & Hewan -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-person-circle text-primary me-2"></i>Data Pemilik
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="text-muted small">Nama</div>
                            <div class="fw-bold">{{ $booking->owner->name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Alamat</div>
                            <div>{{ $booking->owner->address }}</div>
                        </div>
                        <div>
                            <div class="text-muted small">WhatsApp</div>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->owner->whatsapp) }}" target="_blank"
                                class="text-success text-decoration-none fw-semibold">
                                <i class="bi bi-whatsapp me-1"></i>{{ $booking->owner->whatsapp }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-bottom py-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-heart-pulse text-success me-2"></i>Data Hewan
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="text-muted small">Nama Hewan</div>
                            <div class="fw-bold">{{ $booking->pet->name }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Jenis Hewan</div>
                            <div>{{ $booking->pet->display_species }}</div>
                        </div>
                        @if($booking->pet->age)
                        <div>
                            <div class="text-muted small">Usia</div>
                            <div>{{ $booking->pet->age }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Layanan & Catatan -->
        <div class="card shadow-sm">
            <div class="card-header border-bottom py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clipboard-check text-warning me-2"></i>Detail Layanan
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Layanan</div>
                        <div class="fw-bold fs-5">{{ $booking->service->name }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small mb-1">Estimasi Harga</div>
                        <div class="fw-bold text-success fs-5">{{ $booking->service->estimated_price }}</div>
                    </div>
                    @if($booking->notes)
                    <div class="col-12">
                        <hr>
                        <div class="text-muted small mb-1">Catatan Dari Pelanggan</div>
                        <div class="p-3 bg-light rounded-3 fst-italic">
                            "{{ $booking->notes }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">

        <!-- Update Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-header border-bottom py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-arrow-repeat text-primary me-2"></i>Ubah Status
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status Booking</label>
                        <select name="status" class="form-select">
                            @foreach(\App\Models\Booking::STATUSES as $status)
                            <option value="{{ $status }}" {{ $booking->status === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-2"></i>Simpan Status
                    </button>
                </form>

                <!-- Quick status buttons -->
                <hr class="my-3">
                <div class="d-flex flex-column gap-2">
                    @foreach(\App\Models\Booking::STATUSES as $status)
                    @if($status !== $booking->status)
                    <form action="{{ route('admin.bookings.status', $booking) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <button type="submit" class="btn btn-{{ \App\Models\Booking::STATUS_COLORS[$status] ?? 'secondary' }} btn-sm w-100">
                            <i class="bi bi-{{ \App\Models\Booking::STATUS_ICONS[$status] ?? 'circle' }} me-1"></i>
                            {{ $status }}
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card shadow-sm">
            <div class="card-header border-bottom py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-gear text-muted me-2"></i>Tindakan
                </h6>
            </div>
            <div class="card-body p-4 d-flex flex-column gap-2">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                </a>
                <a href="{{ route('booking.success', $booking->booking_code) }}" target="_blank" class="btn btn-outline-info">
                    <i class="bi bi-eye me-2"></i>Lihat Halaman Publik
                </a>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" id="deleteForm">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-danger w-100 btn-delete"
                        data-form="deleteForm"
                        data-confirm="Booking {{ $booking->booking_code }} akan dihapus permanen dan tidak bisa dikembalikan.">
                        <i class="bi bi-trash me-2"></i>Hapus Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
