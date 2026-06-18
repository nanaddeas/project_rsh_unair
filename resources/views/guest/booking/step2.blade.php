@extends('layouts.guest')
@section('title', 'Booking - Pilih Jadwal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="step-indicator mb-4 px-3">
                @php $steps = ['Data Pemilik & Hewan', 'Pilih Jadwal', 'Pilih Layanan', 'Konfirmasi']; @endphp
                @foreach($steps as $i => $label)
                <div class="step-item {{ $i === 1 ? 'active' : ($i < 1 ? 'done' : '') }} flex-fill">
                    <div class="step-circle" style="{{ $i === 1 ? 'background-color: #199528; border-color: #199528;' : ($i < 1 ? 'background-color: #199528; border-color: #199528; color: white;' : '') }}">
                        @if($i < 1)
                            <i class="bi bi-check-lg"></i>
                        @elseif($i === 1)
                            <i class="bi bi-calendar3" style="font-size:1rem; color: white;"></i>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </div>
                    <div class="step-label" style="{{ $i === 1 ? 'color: #199528; font-weight: bold;' : '' }}">{{ $label }}</div>
                </div>
                @endforeach
            </div>

            <form action="{{ route('booking.step2.store') }}" method="POST" id="step2Form">
                @csrf

                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 16px; overflow: hidden;">
                            <div class="card-header text-white py-3 px-4" style="background-color: #199528;">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-calendar3 me-2"></i>Pilih Tanggal
                                </h6>
                            </div>
                            <div class="card-body p-3" style="background-color: #FFFFFF;">
                                <div id="datepicker-inline" class="w-100"></div>
                                <input type="hidden" name="booking_date" id="booking_date" value="{{ old('booking_date') }}">
                                @error('booking_date')
                                <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror

                                <div id="selectedDateDisplay" class="mt-3 d-none">
                                    <div class="alert py-2 px-3 mb-0" style="border-radius:10px; background-color: #E8F5E9; border-color: #C8E6C9; color: #199528;">
                                        <i class="bi bi-calendar-check me-2"></i>
                                        <span id="selectedDateText" class="fw-semibold"></span>
                                    </div>
                                </div>

                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="small fw-semibold mb-2 text-muted">Keterangan Jam Istirahat:</div>
                                    <div class="small"><i class="bi bi-clock text-warning me-1"></i>Senin–Kamis & Sabtu: 12.00–13.00</div>
                                    <div class="small"><i class="bi bi-clock text-warning me-1"></i>Jumat: 11.30–12.30</div>
                                    <div class="small text-danger"><i class="bi bi-x-circle me-1"></i>Minggu: Tutup</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card shadow-sm h-100 border-0" style="border-radius: 16px; overflow: hidden;">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h6 class="mb-0 fw-bold" style="color: #199528;">
                                    <i class="bi bi-clock me-2"></i>Pilih Jam Booking
                                </h6>
                            </div>
                            <div class="card-body p-4" style="background-color: #FFFFFF;">

                                <div class="d-flex gap-3 mb-4 flex-wrap">
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:14px;height:14px;border-radius:4px;background:white;border:2px solid #E2E8F0;"></div>
                                        <span class="small text-muted">Tersedia</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div style="width:14px;height:14px;border-radius:4px;background:#199528;"></div>
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

                                <input type="hidden" name="booking_time" id="booking_time" value="{{ old('booking_time') }}">
                                @error('booking_time')
                                <div class="text-danger small mt-2" id="timeError"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                                <div id="timeError2" class="text-danger small mt-2 d-none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('booking.step1') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <button type="submit" class="btn text-white px-4 rounded-pill" id="nextBtn" style="background-color: #199528;" disabled>
                        Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Menyesuaikan skema warna penanggalan internal Flatpickr ke Hijau */
    .flatpickr-calendar {
        box-shadow: none !important;
        border: none !important;
        width: 100% !important;
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #199528 !important;
        border-color: #199528 !important;
    }
    .flatpickr-day.today {
        border-color: #199528 !important;
    }
    .flatpickr-day.today:hover {
        background: #e8f5e9 !important;
        color: #199528 !important;
    }

    /* Styling tombol grid slot waktu (wajib ditaruh di sini agar tombol dari JS ikut berubah) */
    .slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1-fraction));
        gap: 10px;
    }
    .slot-btn {
        background: white;
        border: 1px solid #E2E8F0;
        padding: 10px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        color: #334155;
    }
    .slot-btn:hover:not(:disabled) {
        border-color: #199528;
        color: #199528;
        background: #F0FDF4;
    }
    .slot-selected {
        background: #199528 !important;
        border-color: #199528 !important;
        color: white !important;
    }
    .slot-full {
        background: #F1F5F9 !important;
        border-color: #CBD5E1 !important;
        color: #94A3B8 !important;
        cursor: not-allowed;
    }
</style>
@endsection

@push('scripts')
<script>
let selectedDate = null;
let selectedTime = null;

// Initialize Flatpickr inline
const fp = flatpickr("#datepicker-inline", {
    inline: true,
    locale: "id",
    minDate: "today",
    disable: [
        function(date) {
            return date.getDay() === 0; // Disable Sundays
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

// Load slots via AJAX
function loadSlots(date) {
    const container = document.getElementById('slotsContainer');
    // Spinner dimodifikasi menggunakan class text-success bawaan Bootstrap agar berwarna hijau
    container.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status"></div>
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

        // Group by hour
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
                <div class="slots-grid d-flex gap-2 flex-wrap">`; // Menambahkan d-flex agar susunan tombol rapi
            slots.forEach(slot => {
                const isFull = slot.status === 'penuh';
                html += `<button type="button"
                    class="slot-btn ${isFull ? 'slot-full' : ''}"
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
    })
    .catch(err => {
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

// Form validation
document.getElementById('step2Form').addEventListener('submit', function(e) {
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
