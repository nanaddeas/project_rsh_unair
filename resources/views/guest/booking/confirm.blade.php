@extends('layouts.guest')
@section('title', 'Konfirmasi Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Pilih Jadwal', 'Data Pemilik & Hewan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 2 ? 'active' : 'done' }} flex-fill">
                    <div class="step-circle">
                        @if($i < 2)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi bi-check2-all" style="font-size:0.9rem;"></i>
                        @endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clipboard-check me-2"></i>Ringkasan Booking
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-warning mb-3">
                            <i class="bi bi-calendar-event me-2"></i>Jadwal Booking
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">Tanggal</div>
                            <div class="col-sm-8 fw-semibold">{{ $bookingDate }}</div>
                            <div class="col-sm-4 text-muted small">Jam</div>
                            <div class="col-sm-8 fw-semibold">
                                <i class="bi bi-clock me-1 text-primary"></i>{{ $schedule['booking_time'] }} WIB
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-circle me-2"></i>Data Pemilik
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">NIK</div>
                            <div class="col-sm-8 fw-semibold">{{ $patient['owner_nik'] }}</div>
                            <div class="col-sm-4 text-muted small">Nama Pemilik</div>
                            <div class="col-sm-8 fw-semibold">{{ $patient['owner_name'] }}</div>
                            <div class="col-sm-4 text-muted small">WhatsApp</div>
                            <div class="col-sm-8"><i class="bi bi-whatsapp text-success me-1"></i>{{ $patient['owner_whatsapp'] }}</div>
                            <div class="col-sm-4 text-muted small">Alamat Domisili</div>
                            <div class="col-sm-8">{{ $patient['owner_address'] }}</div>
                        </div>
                    </div>

                    <div class="p-4">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="bi bi-heart-pulse me-2"></i>Data Hewan
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">Nama Hewan</div>
                            <div class="col-sm-8 fw-semibold">{{ $patient['pet_name'] }}</div>
                            <div class="col-sm-4 text-muted small">Jenis</div>
                            <div class="col-sm-8">{{ $patient['pet_species'] }}</div>
                            <div class="col-sm-4 text-muted small">Ras</div>
                            <div class="col-sm-8">{{ $patient['pet_breed'] ?: '-' }}</div>
                            <div class="col-sm-4 text-muted small">Jenis Kelamin</div>
                            <div class="col-sm-8">{{ $patient['pet_gender'] }}</div>
                            <div class="col-sm-4 text-muted small">Usia</div>
                            <div class="col-sm-8">{{ $patient['pet_age'] ?: '-' }}</div>
                            <div class="col-sm-4 text-muted small">Warna Bulu</div>
                            <div class="col-sm-8">{{ $patient['pet_fur_color'] ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('booking.store') }}" method="POST" id="confirmForm">
                        @csrf
                        <label class="form-label fw-semibold mb-2">
                            <i class="bi bi-chat-square-text me-2 text-muted"></i>Catatan Tambahan <small class="text-muted fw-normal">(opsional)</small>
                        </label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Tambahkan catatan khusus jika ada (keluhan, kondisi hewan, dll)..."></textarea>
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('booking.step2') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" form="confirmForm" class="btn btn-success btn-lg px-5 fw-bold" id="confirmBtn">
                    <i class="bi bi-check-circle me-2"></i>Konfirmasi Booking
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('confirmBtn').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('confirmForm');
    Swal.fire({
        title: 'Konfirmasi Booking?',
        html: `
            <div class="text-start mt-3">
                <p class="mb-2"><strong>Tanggal:</strong> {{ $bookingDate }}</p>
                <p class="mb-2"><strong>Jam:</strong> {{ $schedule['booking_time'] }} WIB</p>
                <p class="mb-0"><strong>Pasien:</strong> {{ $patient['pet_name'] }} / {{ $patient['owner_name'] }}</p>
            </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Ya, Konfirmasi!',
        cancelButtonText: 'Tinjau Ulang',
    }).then((result) => {
        if (result.isConfirmed) {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            form.submit();
        }
    });
});
</script>
@endpush
