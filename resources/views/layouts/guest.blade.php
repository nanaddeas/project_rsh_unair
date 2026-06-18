<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RS Hewan Online') - Rumah Sakit Hewan</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Flatpickr for datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/confetti.css">

    <style>
        :root {
            --primary: #199528;
            --primary-dark: #0F5418;
            --primary-light: #E8F5E9;
            --secondary: #FFFFFF;
            --accent: #F59E0B;
            --dark: #1E293B;
            --muted: #64748B;
            --border: #E2E8F0;
            --surface: #F8FAFC;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC;
            color: var(--dark);
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 4px 20px rgba(25,149,40,0.25);
        }
        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: white !important;
        }
        .navbar-custom .nav-link {
            color: rgba(255,255,255,0.85) !important;
            font-weight: 500;
            transition: color 0.2s;
        }
        .navbar-custom .nav-link:hover { color: white !important; }
        .navbar-custom .btn-booking {
            background: white;
            color: var(--primary);
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 20px;
        }
        .navbar-custom .btn-booking:hover { background: #EFF6FF; }

        /* Step indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 2rem;
        }
        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(50% + 20px);
            width: calc(100% - 10px);
            height: 2px;
            background: var(--border);
        }
        .step-item.active::after { background: var(--primary); }
        .step-item.done::after { background: var(--primary); }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            background: var(--border);
            color: var(--muted);
            transition: all 0.3s;
        }
        .step-item.active .step-circle {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(25,149,40,0.35);
        }
        .step-item.done .step-circle {
            background: var(--primary);
            color: white;
        }
        .step-label {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--muted);
            margin-top: 6px;
            text-align: center;
            white-space: nowrap;
        }
        .step-item.active .step-label { color: var(--primary); font-weight: 600; }
        .step-item.done .step-label { color: var(--primary); font-weight: 600; }

        /* Cards */
        .card { border: none; border-radius: 16px; box-shadow: 0 1px 15px rgba(0,0,0,0.07); }
        .card-header { border-radius: 16px 16px 0 0 !important; border-bottom: 1px solid var(--border); }

        /* Buttons */
        .btn-primary { background: var(--primary); border-color: var(--primary); font-weight: 600; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }

        /* Time slot grid */
        .slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 10px;
        }
        .slot-btn {
            padding: 10px 6px;
            border-radius: 10px;
            border: 2px solid var(--border);
            background: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .slot-btn:hover:not(.slot-full) {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: translateY(-2px);
        }
        .slot-btn.slot-selected {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }
        .slot-btn.slot-full {
            background: #F1F5F9;
            border-color: #CBD5E1;
            color: #94A3B8;
            cursor: not-allowed;
            text-decoration: line-through;
        }

        /* Status badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #1E293B 100%);
            color: rgba(255,255,255,0.8);
        }

        /* Alert */
        .alert { border-radius: 12px; border: none; }

        @media (max-width: 768px) {
            .step-label { font-size: 0.6rem; }
            .slots-grid { grid-template-columns: repeat(3, 1fr); }
        }
    </style>

    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-baru.webp') }}" alt="Logo RSH" height="40" class="me-2">
            Rumah Sakit Hewan Universitas Airlangga
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
            </ul>
            <div class="d-flex gap-2">
                <a href="{{ route('booking.step1') }}" class="btn btn-booking">
                    <i class="bi bi-calendar-plus me-1"></i>Booking Sekarang
                </a>
            </div>
        </div>
    </div>
</nav>

@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{!! session('success') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{!! session('error') !!}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@yield('content')

<footer class="py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-white fw-bold mb-3">
                    <i class="bi bi-heart-pulse-fill me-2 text-white"></i>Rumah Sakit Hewan Universitas Airlangga
                </h5>
                <p class="small">Booking jadwal rumah sakit hewan yang mudah, cepat, dan terpercaya.</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-semibold mb-3">Jam Operasional</h6>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-clock me-2"></i>Senin - Kamis & Sabtu: 08.00 - 15.00</li>
                    <li><i class="bi bi-clock me-2"></i>Jumat: 08.00 - 15.00</li>
                    <li><i class="bi bi-x-circle me-2 text-danger"></i>Minggu: Tutup</li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-semibold mb-3">Alur Booking</h6>
                <p class="small">Pilih jadwal, isi data pemilik dan hewan, lalu simpan kode booking Anda.</p>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center small mb-0">&copy; {{ date('Y') }} Rumah Sakit Hewan Universitas Airlangga. Semua hak dilindungi.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')
</body>
</html>
