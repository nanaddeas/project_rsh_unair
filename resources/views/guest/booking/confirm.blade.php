@extends('layouts.guest')
@section('title', 'Konfirmasi Booking')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Step Indicator -->
            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Data Pemilik & Hewan', 'Pilih Jadwal', 'Pilih Layanan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 3 ? 'active' : 'done' }} flex-fill">
                    <div class="step-circle">
                        @if($i < 3)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi bi-check2-all" style="font-size:0.9rem;"></i>
                        @endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <!-- Summary Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clipboard-check me-2"></i>Ringkasan Booking
                    </h5>
                </div>
                <div class="card-body p-0">

                    <!-- Data Pemilik -->
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="bi bi-person-circle me-2"></i>Data Pemilik
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">Nama Pemilik</div>
                            <div class="col-sm-8 fw-semibold">{{ $step1['owner_name'] }}</div>
                            <div class="col-sm-4 text-muted small">Alamat</div>
                            <div class="col-sm-8">{{ $step1['owner_address'] }}</div>
                            <div class="col-sm-4 text-muted small">WhatsApp</div>
                            <div class="col-sm-8">
                                <i class="bi bi-whatsapp text-success me-1"></i>{{ $step1['owner_whatsapp'] }}
                            </div>
                        </div>
                    </div>

                    <!-- Data Hewan -->
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="bi bi-heart-pulse me-2"></i>Data Hewan
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">Jenis Hewan</div>
                            <div class="col-sm-8 fw-semibold">
                                {{ $step1['pet_species'] === 'Lainnya' && !empty($step1['pet_custom'])
                                    ? $step1['pet_custom']
                                    : $step1['pet_species'] }}
                            </div>
                            <div class="col-sm-4 text-muted small">Nama Hewan</div>
                            <div class="col-sm-8 fw-semibold">{{ $step1['pet_name'] }}</div>
                            @if(!empty($step1['pet_age']))
                            <div class="col-sm-4 text-muted small">Usia Hewan</div>
                            <div class="col-sm-8">{{ $step1['pet_age'] }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Booking -->
                    <div class="p-4 border-bottom">
                        <h6 class="fw-bold text-warning mb-3">
                            <i class="bi bi-calendar-event me-2"></i>Detail Booking
                        </h6>
                        <div class="row g-2">
                            <div class="col-sm-4 text-muted small">Tanggal</div>
                            <div class="col-sm-8 fw-semibold">{{ $bookingDate }}</div>
                            <div class="col-sm-4 text-muted small">Jam</div>
                            <div class="col-sm-8 fw-semibold">
                                <i class="bi bi-clock me-1 text-primary"></i>{{ $step2['booking_time'] }} WIB
                            </div>
                            <div class="col-sm-4 text-muted small">Layanan</div>
                            <div class="col-sm-8">
                                <span class="badge bg-primary">{{ $service->name }}</span>
                                <span class="text-muted small ms-2">{{ $service->estimated_price }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div class="p-4 bg-light">
                        <h6 class="fw-bold text-info mb-3">
                            <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                        </h6>
                        <div class="alert alert-info mb-3 py-2 px-3" style="border-radius:10px; border-left:4px solid #0EA5E9;">
                            <i class="bi bi-cash-coin me-2"></i>
                            <strong>Pembayaran dilakukan langsung di lokasi</strong> rumah sakit hewan setelah pelayanan diberikan.
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="text-muted small">Metode diterima:</span>
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="bi bi-cash me-1"></i>Cash
                            </span>
                            <span class="badge bg-primary fs-6 px-3 py-2">
                                <i class="bi bi-qr-code me-1"></i>QRIS
                            </span>
                        </div>
                        <div class="mt-3 text-muted small">
                            <i class="bi bi-info-circle me-1"></i>
                            Harga akhir akan disesuaikan dengan kondisi hewan dan tindakan yang dilakukan dokter.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan Tambahan -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <label class="form-label fw-semibold mb-2">
                        <i class="bi bi-chat-square-text me-2 text-muted"></i>Catatan Tambahan <small class="text-muted fw-normal">(opsional)</small>
                    </label>
                    <form action="{{ route('booking.store') }}" method="POST" id="confirmForm">
                        @csrf
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Tambahkan catatan khusus jika ada (keluhan, kondisi hewan, dll)..."></textarea>
                </div>
            </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('booking.step3') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-success btn-lg px-5 fw-bold" id="confirmBtn">
                        <i class="bi bi-check-circle me-2"></i>Konfirmasi Booking
                    </button>
                </div>
            </form>
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
                <p class="mb-2"><strong>Jam:</strong> {{ $step2['booking_time'] }} WIB</p>
                <p class="mb-0"><strong>Layanan:</strong> {{ $service->name }}</p>
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
