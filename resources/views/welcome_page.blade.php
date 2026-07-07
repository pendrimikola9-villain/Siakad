<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD UMB - Portal Akademik Digital</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons & Animate.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand img {
            max-height: 50px;
        }
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            min-height: calc(100vh - 76px);
            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .feature-box {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0,0,0,.08)!important;
        }
        .logo-container img {
            max-width: 150px;
            filter: drop-shadow(0px 4px 8px rgba(0, 0, 0, 0.2));
        }
    </style>
</head>
<body>

    <!-- NAVBAR DENGAN LOGO UMB -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
        <div class="container">
           <a class="navbar-brand fw-bold d-flex align-items-center text-primary" href="#">
    <img src="{{ asset('img/logo-umb.png') }}" alt="Logo UMB" class="me-2" style="height: 45px; width: auto;">
    <div>
        <span class="d-block mb-0 lh-1 fs-5">SIAKAD UMB</span>
        <small class="text-muted d-block" style="font-size: 0.7rem;">Univ. Muhammadiyah Banjarmasin</small>
    </div>
</a>
           <div class="ms-auto">
    <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-3 me-2 rounded-3">Login</a>
    <a href="{{ route('register') }}" class="btn btn-primary fw-bold px-3 rounded-3">Daftar</a>
</div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start animate__animated animate__fadeInLeft">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill mb-3 shadow-sm">
                        <i class="bi bi-patch-check-fill me-1"></i> Mode Pengembangan v2.4
                    </span>
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang di <br><span class="text-warning">SIAKAD UMB Portal</span></h1>
                    <p class="lead mb-4 opacity-75">Platform digital terintegrasi untuk mempermudah manajemen perkuliahan, pengisian KRS, rekapitulasi nilai, serta transparansi logbook bimbingan mahasiswa dan dosen secara riil.</p>
                    
                   <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
    <a href="{{ route('login') }}" class="btn btn-white bg-white text-primary btn-lg fw-bold px-4 py-3 rounded-3 shadow">
        <i class="bi bi-box-arrow-in-right me-2"></i> Portal Login SIAKAD
    </a>
    <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg fw-bold px-4 py-3 rounded-3">
        <i class="bi bi-person-plus me-2"></i> Registrasi Akun Baru
    </a>
</div>
                </div>
                
                <div class="col-lg-6 text-center animate__animated animate__fadeInRight">
                    <!-- 🟢 KOTAK ILUSTRASI UTAMA DENGAN LOGO KAMPUS BESAR -->
                 <div class="p-5 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-10 shadow-lg text-center">
    <div class="logo-container mb-3">
        <img src="{{ asset('img/logo-umb.png') }}" alt="Universitas Muhammadiyah Banjarmasin" class="img-fluid animate__animated animate__pulse animate__infinite animate__slow" style="max-height: 140px; width: auto;">
    </div>
    <h3 class="fw-bold text-white mt-3">Universitas Muhammadiyah Banjarmasin</h3>
    <p class="small text-white-50 mb-0">Fakultas Teknik — Program Studi Teknik Informatika</p>
</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FITUR UTAMA -->
    <section class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Modul Layanan SIAKAD</h2>
                <p class="text-secondary">Arsitektur sistem yang dirancang untuk mendukung efisiensi pilar akademik kampus.</p>
            </div>
            
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm rounded-4 h-100 feature-box">
                        <div class="bg-primary bg-opacity-10 text-primary mx-auto rounded-3 p-3 mb-3" style="width: 60px; height: 60px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-chat-square-text-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold">SIBIMBING</h5>
                        <p class="text-secondary small mb-0">Sistem monitoring logbook skripsi, konsultasi dosen wali, serta pengajuan janji temu yang cepat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm rounded-4 h-100 feature-box">
                        <div class="bg-success bg-opacity-10 text-success mx-auto rounded-3 p-3 mb-3" style="width: 60px; height: 60px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-file-earmark-bar-graph-fill fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Manajemen Nilai</h5>
                        <p class="text-secondary small mb-0">Input nilai mahasiswa secara instan berdasarkan hak akses operator, kaprodi, maupun dosen pengampu matakuliah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm rounded-4 h-100 feature-box">
                        <div class="bg-info bg-opacity-10 text-info mx-auto rounded-3 p-3 mb-3" style="width: 60px; height: 60px; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-journal-arrow-up fs-3"></i>
                        </div>
                        <h5 class="fw-bold">Bahan & Tugas</h5>
                        <p class="text-secondary small mb-0">Pusat repositori untuk mengunduh materi kuliah mandiri serta portal pengumpulan file berkas tugas mahasiswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-dark text-white-50 text-center py-4 border-top border-secondary">
        <div class="container small">
            <p class="mb-0">&copy; 2026 SIAKAD UMB. Dikembangkan oleh Tim Teknik Informatika Universitas Muhammadiyah Banjarmasin.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundled -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>