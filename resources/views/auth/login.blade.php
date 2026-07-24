<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD UMB - Login Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* 1. ANIMASI GRADASI BERGERAK PADA BACKGROUND */
        body {
            background: linear-gradient(-45deg, #0d6efd, #6610f2, #198754, #dc3545);
            background-size: 400% 400%;
            animation: gradientBg 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes gradientBg {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* 2. EFEK GLASSMORPHISM PADA CARD */
        .auth-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transition: all 0.5s ease;
        }

        .floating-card {
            animation: floatCard 6s ease-in-out infinite;
        }

        @keyframes floatCard {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .input-group-text-custom {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-right: none !important;
            color: #fff !important;
            border-top-left-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
        }

        .form-control-custom {
            background: rgba(255, 255, 255, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-left: none !important;
            color: #fff !important;
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
            padding: 12px 20px 12px 10px !important;
            transition: all 0.3s ease;
        }

        .form-control-custom:focus {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: #fff !important;
            color: #fff !important;
            box-shadow: none !important;
        }
        
        .input-group:focus-within .input-group-text-custom,
        .input-group:focus-within .form-control-custom {
            border-color: #fff !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.4) !important;
        }

        .form-control-custom::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-auth {
            background: #fff;
            color: #6610f2;
            font-weight: bold;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.4s ease;
            border: none;
        }

        .btn-auth:hover {
            background: #6610f2;
            color: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            transform: translateY(-3px);
        }

        .toggle-link {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .toggle-link:hover {
            text-shadow: 0 0 10px rgba(255,255,255,0.8);
            text-decoration: underline;
        }

        #register-form {
            display: none;
        }
    </style>
</head>
<body>

    <div class="auth-container floating-card animate__animated animate__zoomIn">
        @if(session('success'))
    <div class="alert alert-success py-2 small bg-success bg-opacity-70 text-white border-0 animate__animated animate__fadeIn">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif
        <div id="login-form" class="animate__animated">
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo-umb.png') }}" 
                     alt="Logo UMB" 
                     class="img-fluid mb-2" 
                     style="max-height: 80px; filter: drop-shadow(0 0 8px rgba(255,255,255,0.6)); object-fit: contain;">
                
                <h2 class="text-white fw-bold mt-1" style="letter-spacing: -0.5px;">SIAKAD TERPADU</h2>
                <p class="text-white-50 small">Portal Login Civitas Akademika UMB</p>
            </div>

            <form action="{{ route('login.process') }}" method="POST">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger py-2 small bg-danger bg-opacity-70 text-white border-0">{{ session('error') }}</div>
                @endif

                <div class="mb-3">
                    <label class="form-label text-white small fw-bold">Nomor Identitas (NIM / NIDN / dst)</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="username" class="form-control form-control-custom" placeholder="Masukkan NIM, NIDN, atau Username" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-white small fw-bold">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text input-group-text-custom"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" class="form-control form-control-custom" placeholder="Masukkan Password" required>
                    </div>
                </div>

                <div class="text-end mb-4">
                    <a href="{{ route('password.request') }}" class="toggle-link small" style="font-size: 0.8rem; opacity: 0.8;">Lupa Kata Sandi / Akun?</a>
                </div>
                
                <button type="submit" class="btn btn-auth w-100 mb-3">MASUK KE SISTEM <i class="bi bi-box-arrow-in-right"></i></button>
            </form>

            <div class="text-center mt-4">
                <p class="text-white-50 small">Khusus Mahasiswa Baru? <a href="#" id="btn-to-register" class="toggle-link">Registrasi Akun</a></p>
            </div>
        </div>

        <div id="register-form" class="animate__animated">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill text-white" style="font-size: 3.5rem; filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));"></i>
                <h2 class="text-white fw-bold mt-2">Registrasi Portal</h2>
                <p class="text-white-50 small">Lengkapi verifikasi data induk mahasiswa.</p>
            </div>

           <form action="{{ route('register') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label class="form-label text-white small fw-bold">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text input-group-text-custom"><i class="bi bi-fonts"></i></span>
            <input type="text" name="name" class="form-control form-control-custom" placeholder="Masukkan Nama Lengkap Sesuai Ijazah" value="{{ old('name') }}" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label text-white small fw-bold">Nomor Induk Mahasiswa (NIM)</label>
        <div class="input-group">
            <span class="input-group-text input-group-text-custom"><i class="bi bi-card-text"></i></span>
            <input type="text" name="nim" class="form-control form-control-custom" placeholder="Masukkan NIM Resmi" value="{{ old('nim') }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label text-white small fw-bold">Email Kampus / Aktif</label>
        <div class="input-group">
            <span class="input-group-text input-group-text-custom"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" name="email" class="form-control form-control-custom" placeholder="username@student.umb.ac.id" value="{{ old('email') }}" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label text-white small fw-bold">Sandi Baru</label>
        <div class="input-group">
            <span class="input-group-text input-group-text-custom"><i class="bi bi-eye-slash-fill"></i></span>
            <input type="password" name="password" class="form-control form-control-custom" placeholder="Buat Password" required autocomplete="new-password">
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label text-white small fw-bold">Konfirmasi Sandi Baru</label>
        <div class="input-group">
            <span class="input-group-text input-group-text-custom"><i class="bi bi-shield-check"></i></span>
            <input type="password" name="password_confirmation" class="form-control form-control-custom" placeholder="Ulangi Password" required>
        </div>
    </div>

    <button type="submit" class="btn btn-auth w-100 mb-3">AKTIVASI AKUN <i class="bi bi-check-circle"></i></button>
</form>

            <div class="text-center mt-3">
                <p class="text-white-50 small">Sudah aktivasi? <a href="#" id="btn-to-login" class="toggle-link">Kembali Login</a></p>
            </div>
        </div>

    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const toRegisterBtn = document.getElementById('btn-to-register');
        const toLoginBtn = document.getElementById('btn-to-login');

        toRegisterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginForm.classList.remove('animate__fadeInLeft');
            loginForm.classList.add('animate__fadeOutRight');
            
            setTimeout(() => {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                registerForm.classList.remove('animate__fadeOutLeft');
                registerForm.classList.add('animate__fadeInLeft');
            }, 400);
        });

        toLoginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            registerForm.classList.remove('animate__fadeInLeft');
            registerForm.classList.add('animate__fadeOutRight');
            
            setTimeout(() => {
                registerForm.style.display = 'none';
                loginForm.style.display = 'block';
                loginForm.classList.remove('animate__fadeOutRight');
                loginForm.classList.add('animate__fadeInLeft');
            }, 400);
        });
    </script>
</body>
</html>