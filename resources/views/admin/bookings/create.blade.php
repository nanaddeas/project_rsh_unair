@extends('layouts.admin')
@section('title', 'Booking Manual')
@section('page-title', 'Booking Manual')

@section('content')
<form action="{{ route('admin.bookings.store') }}" method="POST" id="manualBookingForm">
    @csrf
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3 px-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-calendar3 me-2"></i>Jadwal Booking
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="booking_date" id="booking_date" class="form-control @error('booking_date') is-invalid @enderror"
                            value="{{ old('booking_date') }}" min="{{ now()->toDateString() }}">
                        @error('booking_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam <span class="text-danger">*</span></label>
                        <input type="hidden" name="booking_time" id="booking_time" value="{{ old('booking_time') }}">
                        @error('booking_time')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div id="slotsContainer" class="mb-3">
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-calendar3 fs-2 d-block mb-2 opacity-50"></i>
                            Pilih tanggal untuk melihat slot.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Awal</label>
                        <select name="status" class="form-select">
                            @foreach(\App\Models\Booking::STATUSES as $status)
                            <option value="{{ $status }}" {{ old('status', 'Dikonfirmasi') === $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-person-circle text-primary me-2"></i>Data Pemilik
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">Status Klien</label>
                            <span id="clientStatus" class="badge bg-secondary">Masukkan NIK untuk mengecek</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" name="owner_nik" id="owner_nik" class="form-control @error('owner_nik') is-invalid @enderror" value="{{ old('owner_nik') }}">
                                <button type="button" class="btn btn-outline-primary" id="checkNikButton">
                                    <i class="bi bi-search me-1"></i>Cek NIK
                                </button>
                            </div>
                            @error('owner_nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                            <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name') }}">
                            @error('owner_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="owner_whatsapp" class="form-control @error('owner_whatsapp') is-invalid @enderror" value="{{ old('owner_whatsapp') }}">
                            @error('owner_whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Domisili <span class="text-danger">*</span></label>
                            <textarea name="owner_address" rows="2" class="form-control @error('owner_address') is-invalid @enderror">{{ old('owner_address') }}</textarea>
                            @error('owner_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-heart-pulse text-success me-2"></i>Data Hewan
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold d-block">Status Pasien</label>
                            <span id="patientStatus" class="badge bg-success">Pasien Baru</span>
                        </div>
                        <div class="col-12 d-none" id="existingPetWrapper">
                            <label class="form-label fw-semibold">Pilih Hewan Terdaftar</label>
                            <select name="existing_pet_id" id="existing_pet_id" class="form-select">
                                <option value="">Daftarkan pasien baru</option>
                            </select>
                            @error('existing_pet_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-none" id="existingPetSummary">
                            <div class="alert alert-light border mb-0">
                                <div class="fw-bold mb-2" id="summaryPetName"></div>
                                <div class="row g-2 small">
                                    <div class="col-sm-6"><span class="text-muted">Jenis:</span> <span id="summaryPetSpecies"></span></div>
                                    <div class="col-sm-6"><span class="text-muted">Ras:</span> <span id="summaryPetBreed"></span></div>
                                    <div class="col-sm-6"><span class="text-muted">Kelamin:</span> <span id="summaryPetGender"></span></div>
                                    <div class="col-sm-6"><span class="text-muted">Usia:</span> <span id="summaryPetAge"></span></div>
                                    <div class="col-sm-6"><span class="text-muted">Warna bulu:</span> <span id="summaryPetFurColor"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                        <div class="row g-3" id="newPetFields">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Hewan <span class="text-danger">*</span></label>
                            <input type="text" name="pet_name" class="form-control @error('pet_name') is-invalid @enderror" value="{{ old('pet_name') }}">
                            @error('pet_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                            <select name="pet_species" class="form-select @error('pet_species') is-invalid @enderror">
                                <option value="">Pilih jenis hewan</option>
                                @foreach(['Kucing', 'Anjing', 'Kelinci', 'Burung', 'Hamster', 'Musang', 'Reptil', 'Lainnya'] as $species)
                                <option value="{{ $species }}" {{ old('pet_species') === $species ? 'selected' : '' }}>{{ $species }}</option>
                                @endforeach
                            </select>
                            @error('pet_species')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ras</label>
                            <input type="text" name="pet_breed" class="form-control" value="{{ old('pet_breed') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="pet_gender" class="form-select @error('pet_gender') is-invalid @enderror">
                                <option value="">Pilih</option>
                                @foreach(['Jantan', 'Betina'] as $gender)
                                <option value="{{ $gender }}" {{ old('pet_gender') === $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                @endforeach
                            </select>
                            @error('pet_gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Usia</label>
                            <input type="text" name="pet_age" class="form-control" value="{{ old('pet_age') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Warna Bulu</label>
                            <input type="text" name="pet_fur_color" class="form-control" value="{{ old('pet_fur_color') }}">
                        </div>
                        </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Keluhan, kondisi awal, atau catatan walk-in">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i>Simpan Booking Manual
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.manual-slot {
    border: 2px solid #E2E8F0;
    background: white;
    border-radius: 10px;
    padding: 8px 10px;
    font-weight: 600;
    font-size: 0.85rem;
}
.manual-slot.selected {
    background: #2563EB;
    border-color: #2563EB;
    color: white;
}
.manual-slot:disabled {
    background: #F1F5F9;
    color: #94A3B8;
    text-decoration: line-through;
}
</style>
@endpush

@push('scripts')
<script>
const dateInput = document.getElementById('booking_date');
const timeInput = document.getElementById('booking_time');
const slotsContainer = document.getElementById('slotsContainer');
const manualBookingForm = document.getElementById('manualBookingForm');
const nikInput = document.getElementById('owner_nik');
const checkNikButton = document.getElementById('checkNikButton');
const clientStatus = document.getElementById('clientStatus');
const patientStatus = document.getElementById('patientStatus');
const existingPetWrapper = document.getElementById('existingPetWrapper');
const existingPetSelect = document.getElementById('existing_pet_id');
const existingPetSummary = document.getElementById('existingPetSummary');
const newPetFields = document.getElementById('newPetFields');
let registeredPets = [];

dateInput.addEventListener('change', function() {
    loadAdminSlots(this.value);
});

if (dateInput.value) {
    loadAdminSlots(dateInput.value);
}

checkNikButton.addEventListener('click', checkNik);
nikInput.addEventListener('keydown', event => {
    if (event.key === 'Enter') {
        event.preventDefault();
        checkNik();
    }
});
existingPetSelect.addEventListener('change', selectRegisteredPet);

async function checkNik() {
    const nik = nikInput.value.trim();
    if (!/^\d{12,20}$/.test(nik)) {
        clientStatus.className = 'badge bg-danger';
        clientStatus.textContent = 'NIK harus berisi 12-20 digit';
        return;
    }

    checkNikButton.disabled = true;
    checkNikButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengecek';

    try {
        const response = await fetch(`{{ route('admin.bookings.owner.lookup') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': manualBookingForm.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({ nik }),
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Gagal mengecek NIK.');

        if (data.found) {
            clientStatus.className = 'badge bg-primary';
            clientStatus.textContent = 'Klien Lama';
            fillOwner(data.owner);
            showRegisteredPets(data.pets);
        } else {
            clientStatus.className = 'badge bg-success';
            clientStatus.textContent = 'Klien Baru';
            clearOwnerDetails();
            showRegisteredPets([]);
        }
    } catch (error) {
        clientStatus.className = 'badge bg-danger';
        clientStatus.textContent = error.message;
    } finally {
        checkNikButton.disabled = false;
        checkNikButton.innerHTML = '<i class="bi bi-search me-1"></i>Cek NIK';
    }
}

function fillOwner(owner) {
    manualBookingForm.elements.owner_name.value = owner.name || '';
    manualBookingForm.elements.owner_whatsapp.value = owner.whatsapp || '';
    manualBookingForm.elements.owner_address.value = owner.address || '';
}

function clearOwnerDetails() {
    manualBookingForm.elements.owner_name.value = '';
    manualBookingForm.elements.owner_whatsapp.value = '';
    manualBookingForm.elements.owner_address.value = '';
}

function showRegisteredPets(pets) {
    registeredPets = pets;
    existingPetSelect.innerHTML = '<option value="">Daftarkan pasien baru</option>';
    pets.forEach(pet => existingPetSelect.add(new Option(`${pet.name} - ${pet.species}`, pet.id)));
    existingPetWrapper.classList.toggle('d-none', pets.length === 0);
    existingPetSelect.value = '';
    patientStatus.className = 'badge bg-success';
    patientStatus.textContent = 'Pasien Baru';
    existingPetSummary.classList.add('d-none');
    newPetFields.classList.remove('d-none');
    clearPetDetails();
}

function selectRegisteredPet() {
    const pet = registeredPets.find(item => String(item.id) === existingPetSelect.value);
    if (!pet) {
        patientStatus.className = 'badge bg-success';
        patientStatus.textContent = 'Pasien Baru';
        existingPetSummary.classList.add('d-none');
        newPetFields.classList.remove('d-none');
        clearPetDetails();
        return;
    }

    patientStatus.className = 'badge bg-primary';
    patientStatus.textContent = 'Pasien Lama';
    newPetFields.classList.add('d-none');
    existingPetSummary.classList.remove('d-none');
    document.getElementById('summaryPetName').textContent = pet.name || '-';
    document.getElementById('summaryPetSpecies').textContent = pet.species || '-';
    document.getElementById('summaryPetBreed').textContent = pet.breed || '-';
    document.getElementById('summaryPetGender').textContent = pet.gender || '-';
    document.getElementById('summaryPetAge').textContent = pet.age || '-';
    document.getElementById('summaryPetFurColor').textContent = pet.fur_color || '-';
}

function clearPetDetails() {
    ['pet_name', 'pet_species', 'pet_breed', 'pet_gender', 'pet_age', 'pet_fur_color']
        .forEach(name => manualBookingForm.elements[name].value = '');
}

function loadAdminSlots(date) {
    timeInput.value = '';
    slotsContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted small mb-0">Memuat slot...</p>
        </div>`;

    fetch(`{{ route('admin.bookings.slots') }}?date=${date}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            slotsContainer.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            return;
        }

        let html = `<div class="small text-muted mb-2">${data.date}</div><div class="d-flex flex-wrap gap-2">`;
        data.slots.forEach(slot => {
            const full = slot.status === 'penuh';
            html += `<button type="button" class="manual-slot" ${full ? 'disabled' : ''} onclick="selectAdminSlot(this, '${slot.time}')">${slot.time}</button>`;
        });
        html += '</div>';
        slotsContainer.innerHTML = html;
    })
    .catch(() => {
        slotsContainer.innerHTML = '<div class="alert alert-danger">Gagal memuat slot.</div>';
    });
}

function selectAdminSlot(button, time) {
    document.querySelectorAll('.manual-slot').forEach(btn => btn.classList.remove('selected'));
    button.classList.add('selected');
    timeInput.value = time;
}
</script>
@endpush
