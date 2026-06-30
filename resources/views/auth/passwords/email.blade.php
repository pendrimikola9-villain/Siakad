<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD UMB - Pemulihan Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* MEMPERTAHANKAN ANIMASI GRADASI BERGERAK ASLI KAMU */
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

        /* EFEK GLASSMORPHISM */
        .auth-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
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
    </style>
</head>
<body>

    <div class="auth-container floating-card animate__animated animate__zoomIn">
        
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo-umb.png') }}" 
                 alt="Logo UMB" 
                 class="img-fluid mb-2" 
                 style="max-height: 80px; filter: drop-shadow(0 0 8px rgba(255,255,255,0.6)); object-fit: contain;">
            
            <h3 class="text-white fw-bold mt-1">RESET KATA SANDI</h3>
            <p class="text-white-50 small">Masukkan email akademik Anda untuk menerima tautan verifikasi pemulihan.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success py-2 small bg-success bg-opacity-70 text-white border-0 mb-3 animate__animated animate__fadeIn">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label text-white small fw-bold">Alamat Email Terdaftar</label>
                <div class="input-group">
                    <span class="input-group-text input-group-text-custom"><i class="bi bi-envelope-fill"></i></span>
                    <input id="email" type="email" name="email" 
                           class="form-control form-control-custom @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="nama@student.umb.ac.id atau nama@umb.ac.id" 
                           required autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback d-block text-warning small mt-2" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-auth w-100 mb-3">
                KIRIM LINK VERIFIKASI <i class="bi bi-send-fill ms-1" style="font-size: 0.85rem;"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-white-50 small">Sudah ingat password? <a href="{{ route('login') }}" class="toggle-link">Kembali Login</a></p>
        </div>

    </div>

</body>
</html>