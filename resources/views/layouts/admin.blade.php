<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Rumah Sakit Hewan Universitas Airlangga</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #113816; /* Hijau tua gelap agar kontras profesional dengan teks putih */
            --sidebar-hover: #1b5321; /* Hijau medium untuk hover effect */
            --primary: #199528; /* Warna hijau primary baru */
            --primary-light: #E8F5E9; /* Warna hijau sangat muda transparan untuk background alert/badge */
            --secondary: #FFFFFF; /* Warna secondary putih */
        }

        body { font-family: 'Inter', sans-serif; background: #F4F6F4; }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s;
        }
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-logo .brand-text {
            color: var(--secondary);
            font-weight: 700;
            font-size: 1.1rem;
        }
        .sidebar-logo .brand-sub {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
        }
        .sidebar-nav { padding: 16px 0; }
        .sidebar-section-label {
            padding: 8px 20px 4px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: var(--secondary);
            border-left-color: rgba(255,255,255,0.4);
        }
        .sidebar-link.active {
            background: rgba(25, 149, 40, 0.25); /* Transparansi dari warna hijau primary */
            color: #A3E635; /* Hijau muda cerah agar teks menu aktif sangat jelas terbaca */
            border-left-color: var(--primary);
        }
        .sidebar-link i { font-size: 1rem; width: 20px; text-align: center; }

        /* Main content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: var(--secondary);
            border-bottom: 1px solid #E2E8F0;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .topbar-title { font-weight: 700; font-size: 1.15rem; color: #1E293B; }
        .user-badge {
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Page content */
        .page-content { padding: 24px; }

        /* Stats cards */
        .stat-card {
            background: var(--secondary);
            border-radius: 16px;
            padding: 20px;
            border: none;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: 0.75rem; color: #64748B; margin-top: 4px; }

        /* Tables */
        .table-card {
            background: var(--secondary);
            border-radius: 16px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .table-card .card-header {
            background: var(--secondary);
            border-bottom: 1px solid #E2E8F0;
            padding: 18px 24px;
        }
        .table > :not(caption) > * > * { padding: 14px 16px; }
        .table thead th {
            background: #F8FAFC;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            border-bottom: 1px solid #E2E8F0;
        }
        .table tbody tr { transition: background 0.15s; }
        .table tbody tr:hover { background: #F8FAFC; }

        /* Badges */
        .badge { font-size: 0.7rem; padding: 5px 10px; border-radius: 50px; }

        /* Forms */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            padding: 10px 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(25, 149, 40, 0.15);
        }

        /* Buttons modifed to primary green */
        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: var(--secondary) !important;
        }
        .btn-primary:hover {
            background-color: #13751f !important;
            border-color: #13751f !important;
        }
        .btn { border-radius: 10px; font-weight: 600; }
        .card { border-radius: 16px; border: none; background: var(--secondary); }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="d-flex align-items-center gap-2">
            <div style="background:rgba(255,255,255,0.1);border-radius:10px;padding:8px;">
                <i class="bi bi-heart-pulse-fill text-white fs-5"></i>
            </div>
            <div>
                <div class="brand-text">Rumah Sakit Hewan Universitas Airlangga</div>
                <div class="brand-sub">Panel Administrator</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="sidebar-section-label mt-2">Manajemen</div>
        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Booking
        </a>
        <div class="sidebar-section-label mt-2">Akun</div>
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
            <i class="bi bi-house"></i> Lihat Website
        </a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            @method('POST')
            <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent" style="cursor:pointer;">
                <i class="bi bi-box-arrow-right text-danger"></i>
                <span class="text-danger">Keluar</span>
            </button>
        </form>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="user-badge">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
            </span>
        </div>
    </div>

    <div class="page-content pb-0">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Delete confirmation
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const form = document.getElementById(this.dataset.form);
        Swal.fire({
            title: 'Hapus Data?',
            text: this.dataset.confirm || 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
