<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Rumah Sakit Hewan Universitas Airlangga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E3A8A 0%, #1E293B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            max-width: 420px;
            width: 100%;
        }
        .login-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #2563EB, #7C3AED);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2rem;
        }
        .form-control {
            border-radius: 12px;
            border: 1.5px solid #E2E8F0;
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .btn-login {
            background: linear-gradient(135deg, #2563EB, #7C3AED);
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: opacity 0.2s, transform 0.2s;
        }
        .btn-login:hover { opacity: 0.9; transform: translateY(-1px); }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-heart-pulse-fill text-white"></i>
            </div>

            <div class="text-center mb-4">
                <h4 class="fw-bold mb-1">Rumah Sakit Hewan Universitas Airlangga</h4>
                <p class="text-muted small">Panel Administrator — Masuk untuk melanjutkan</p>
            </div>

            @if(session('success'))
            <div class="alert alert-success py-2 px-3 small mb-3" style="border-radius:10px;">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small mb-3" style="border-radius:10px;">
                <i class="bi bi-exclamation-triangle me-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light" style="border-radius:12px 0 0 12px; border:1.5px solid #E2E8F0; border-right:none;">
                            <i class="bi bi-envelope text-muted"></i>
                        </span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@rshewan.com"
                            style="border-radius:0 12px 12px 0; border-left:none;">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light" style="border-radius:12px 0 0 12px; border:1.5px solid #E2E8F0; border-right:none;">
                            <i class="bi bi-lock text-muted"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput"
                            class="form-control"
                            placeholder="••••••••"
                            style="border-radius:0 12px 12px 0; border-left:none; border-right:none;">
                        <button type="button" class="input-group-text bg-light" style="border-radius:0 12px 12px 0; border:1.5px solid #E2E8F0; border-left:none; cursor:pointer;"
                            onclick="togglePassword()">
                            <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-4 d-flex align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Ingat saya</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Website
                </a>
            </div>

            <!-- Default credentials hint -->
            <div class="mt-4 p-3 bg-light rounded-3 text-center">
                <div class="text-muted small">
                    <i class="bi bi-info-circle me-1"></i>Demo: <strong>admin@rshewan.com</strong> / <strong>admin123</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash text-muted';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye text-muted';
    }
}
</script>
</body>
</html>
