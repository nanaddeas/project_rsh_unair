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
                                <div class="col-12">
                                    <label class="form-label fw-semibold d-block">Status Klien</label>
                                    <span id="clientStatus" class="badge bg-secondary">Masukkan NIK untuk mengecek</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" name="owner_nik" id="owner_nik" class="form-control @error('owner_nik') is-invalid @enderror"
                                            value="{{ old('owner_nik', session('booking_patient.owner_nik')) }}" placeholder="Nomor Induk Kependudukan">
                                        <button type="button" class="btn btn-outline-primary" id="checkNikButton">
                                            <i class="bi bi-search me-1"></i>Cek NIK
                                        </button>
                                    </div>
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

@push('scripts')
<script>
const patientForm = document.querySelector('form[action="{{ route('booking.step2.store') }}"]');
const nikInput = document.getElementById('owner_nik');
const checkNikButton = document.getElementById('checkNikButton');
const clientStatus = document.getElementById('clientStatus');
const patientStatus = document.getElementById('patientStatus');
const existingPetWrapper = document.getElementById('existingPetWrapper');
const existingPetSelect = document.getElementById('existing_pet_id');
const existingPetSummary = document.getElementById('existingPetSummary');
const newPetFields = document.getElementById('newPetFields');
let registeredPets = [];

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
        const response = await fetch(`{{ route('booking.owner.lookup') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
    patientForm.elements.owner_name.value = owner.name || '';
    patientForm.elements.owner_whatsapp.value = owner.whatsapp || '';
    patientForm.elements.owner_address.value = owner.address || '';
}

function clearOwnerDetails() {
    patientForm.elements.owner_name.value = '';
    patientForm.elements.owner_whatsapp.value = '';
    patientForm.elements.owner_address.value = '';
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
        .forEach(name => patientForm.elements[name].value = '');
}
</script>
@endpush
