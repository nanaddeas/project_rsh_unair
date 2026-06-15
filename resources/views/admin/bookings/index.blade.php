@extends('layouts.admin')
@section('title', 'Manajemen Booking')
@section('page-title', 'Manajemen Booking')

@section('content')

<!-- Filters -->
<div class="card mb-4 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.bookings.index') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Cari Booking</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Nama pemilik, hewan, atau kode..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Filter Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        @foreach(\App\Models\Booking::STATUSES as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="table-card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="fw-bold">
            <i class="bi bi-calendar-check text-primary me-2"></i>
            Data Booking
            <span class="badge bg-primary ms-2">{{ $bookings->total() }}</span>
        </div>
    </div>

    @if($bookings->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
        <p class="mb-0">Tidak ada data booking ditemukan</p>
        @if(request()->hasAny(['search', 'status', 'date']))
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-primary mt-3">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
        </a>
        @endif
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Booking</th>
                    <th>Pemilik</th>
                    <th>Hewan</th>
                    <th>Layanan</th>
                    <th>Tanggal & Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $i => $booking)
                <tr>
                    <td class="text-muted small">{{ $bookings->firstItem() + $i }}</td>
                    <td>
                        <code class="text-primary small fw-bold">{{ $booking->booking_code }}</code>
                    </td>
                    <td>
                        <div class="fw-semibold small">{{ $booking->owner->name }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">
                            <i class="bi bi-whatsapp text-success me-1"></i>{{ $booking->owner->whatsapp }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold small">{{ $booking->pet->name }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">{{ $booking->pet->display_species }}</div>
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary" style="border-radius:8px;">
                            {{ $booking->service->name }}
                        </span>
                    </td>
                    <td>
                        <div class="small fw-semibold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                        <div class="text-muted" style="font-size:0.72rem;">
                            <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::createFromTimeString($booking->booking_time)->format('H:i') }} WIB
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $booking->status_color }}" style="border-radius:20px;">
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                                class="btn btn-sm btn-outline-primary"
                                style="border-radius:8px;"
                                title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" id="del-{{ $booking->id }}">
                                @csrf @method('DELETE')
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger btn-delete"
                                    style="border-radius:8px;"
                                    data-form="del-{{ $booking->id }}"
                                    data-confirm="Booking {{ $booking->booking_code }} akan dihapus permanen."
                                    title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
    <div class="p-4 border-top">
        {{ $bookings->links('pagination::bootstrap-5') }}
    </div>
    @endif
    @endif
</div>

@endsection
