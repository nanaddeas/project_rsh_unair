@extends('layouts.guest')
@section('title', 'Booking - Pilih Jadwal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Pilih Jadwal', 'Data Pemilik & Hewan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 0 ? 'active' : '' }} flex-fill">
                    <div class="step-circle">
                        @if($i === 0)
                            <i class="bi bi-calendar3" style="font-size:1rem;"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <form action="{{ route('booking.step1.store') }}" method="POST" id="step1Form">
                @csrf
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-primary text-white py-3 px-4">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-calendar3 me-2"></i>Pilih Tanggal
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div id="datepicker-inline" class="w-100"></div>
                                <input type="hidden" name="booking_date" id="booking_date" value="{{ old('booking_date', session('booking_schedule.booking_date')) }}">
                                @error('booking_date')
                                <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror

                                <div id="selectedDateDisplay" class="mt-3 d-none">
                                    <div class="alert alert-primary py-2 px-3 mb-0" style="border-radius:10px;">
                                        <i class="bi bi-calendar-check me-2"></i>
                                        <span id="selectedDateText" class="fw-semibold"></span>
                                    </div>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="small fw-semibold mb-2 text-muted">Keterangan Jadwal:</div>
                                    <div class="small"><i class="bi bi-clock text-primary me-1"></i>Sesi booking setiap 20 menit</div>
                                    <div class="small"><i class="bi bi-clock text-warning me-1"></i>Senin-Kamis & Sabtu: istirahat 12.00-13.00</div>
                                    <div class="small"><i class="bi bi-clock text-warning me-1"></i>Jumat: istirahat 11.30-12.30</div>
                                    <div class="small text-danger"><i class="bi bi-x-circle me-1"></i>Minggu: Tutup</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-clock me-2 text-primary"></i>Pilih Jam Booking
                                </h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex gap-3 mb-4 flex-wrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:14px;height:14px;border-radius:4px;background:white;border:2px solid #E2E8F0;"></div>
                                        <span class="small text-muted">Tersedia</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:14px;height:14px;border-radius:4px;background:#2563EB;"></div>
                                        <span class="small text-muted">Dipilih</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:14px;height:14px;border-radius:4px;background:#F1F5F9;border:2px solid #CBD5E1;"></div>
                                        <span class="small text-muted">Penuh</span>
                                    </div>
                                </div>

                                <div id="slotsContainer">
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar3 fs-1 d-block mb-3 text-muted opacity-50"></i>
                                        <p class="mb-0">Pilih tanggal terlebih dahulu untuk melihat slot yang tersedia</p>
                                    </div>
                                </div>

                                <input type="hidden" name="booking_time" id="booking_time" value="{{ old('booking_time', session('booking_schedule.booking_time')) }}">
                                @error('booking_time')
                                <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4" id="nextBtn" disabled>
                        Lanjut Isi Data <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedDate = document.getElementById('booking_date').value || null;
let selectedTime = document.getElementById('booking_time').value || null;

const fp = flatpickr("#datepicker-inline", {
    inline: true,
    locale: "id",
    minDate: "today",
    defaultDate: selectedDate,
    disable: [
        function(date) {
            return date.getDay() === 0;
        }
    ],
    onChange: function(selectedDates, dateStr) {
        selectedDate = dateStr;
        document.getElementById('booking_date').value = dateStr;
        document.getElementById('selectedDateDisplay').classList.remove('d-none');
        document.getElementById('selectedDateText').textContent = '';
        selectedTime = null;
        document.getElementById('booking_time').value = '';
        document.getElementById('nextBtn').disabled = true;
        loadSlots(dateStr);
    }
});

if (selectedDate) {
    document.getElementById('selectedDateDisplay').classList.remove('d-none');
    loadSlots(selectedDate);
}

function loadSlots(date) {
    const container = document.getElementById('slotsContainer');
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted small">Memuat slot tersedia...</p>
        </div>`;

    fetch(`{{ route('booking.slots') }}?date=${date}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            container.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
        }

        document.getElementById('selectedDateText').textContent = data.date;

        if (!data.slots || data.slots.length === 0) {
            container.innerHTML = `<div class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-1 d-block mb-3"></i><p>Tidak ada slot tersedia untuk tanggal ini.</p></div>`;
            return;
        }

        const groups = {};
        data.slots.forEach(slot => {
            const hour = slot.time.split(':')[0];
            if (!groups[hour]) groups[hour] = [];
            groups[hour].push(slot);
        });

        let html = '';
        for (const [hour, slots] of Object.entries(groups)) {
            html += `<div class="mb-3">
                <div class="text-muted small fw-semibold mb-2">${hour}:00</div>
                <div class="slots-grid">`;
            slots.forEach(slot => {
                const isFull = slot.status === 'penuh';
                const isSelected = selectedTime === slot.time;
                html += `<button type="button"
                    class="slot-btn ${isFull ? 'slot-full' : ''} ${isSelected ? 'slot-selected' : ''}"
                    data-time="${slot.time}"
                    ${isFull ? 'disabled' : ''}
                    onclick="selectTime(this, '${slot.time}')">
                    ${slot.time}
                    ${isFull ? '<br><small style="font-size:0.6rem;">Penuh</small>' : ''}
                </button>`;
            });
            html += '</div></div>';
        }
        container.innerHTML = html;
        document.getElementById('nextBtn').disabled = !selectedTime;
    })
    .catch(() => {
        container.innerHTML = `<div class="alert alert-danger">Gagal memuat slot. Silakan coba lagi.</div>`;
    });
}

function selectTime(btn, time) {
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('slot-selected'));
    btn.classList.add('slot-selected');
    selectedTime = time;
    document.getElementById('booking_time').value = time;
    document.getElementById('nextBtn').disabled = false;
}

document.getElementById('step1Form').addEventListener('submit', function(e) {
    if (!selectedDate) {
        e.preventDefault();
        Swal.fire('Perhatian', 'Silakan pilih tanggal booking terlebih dahulu.', 'warning');
        return;
    }
    if (!selectedTime) {
        e.preventDefault();
        Swal.fire('Perhatian', 'Silakan pilih jam booking terlebih dahulu.', 'warning');
    }
});
</script>
@endpush
