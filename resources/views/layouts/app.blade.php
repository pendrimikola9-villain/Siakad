<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universitas Muhammadiyah Banjarmasin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            transition: all 0.25s ease-out;
        }
        .list-group-item {
            border: none;
            padding: 12px 20px;
        }
        .list-group-item:hover {
            background-color: #343a40 !important;
        }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading p-4 text-center border-bottom border-secondary">
                <h4 class="fw-bold mb-0 text-truncate">SIAKAD</h4>
            </div>
            
            <div class="list-group list-group-flush mt-3">
                <a href="/" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                <a href="/data-mahasiswa" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-people me-2"></i> Data Mahasiswa
                </a>

                <a href="{{ route('dosen.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-person-workspace me-2"></i> Data Dosen
                </a>

                <a href="{{ route('room.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-door-open me-2"></i> Data Ruangan / Lab
                </a>

                <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-book me-2"></i> Mata Kuliah
                </a>

                <a href="{{ route('grades.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-pencil-square me-2"></i> Input Nilai
                </a>

                <a href="{{ route('jadwal.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-calendar3 me-2"></i> Jadwal Kuliah
                </a>

                <a href="{{ route('bimbingan.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-chat-square-text me-2"></i> Log Bimbingan (SIBIMBING)
                </a>

                <a href="{{ route('nilai.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Tampilan Nilai
                </a>

                <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-shield-lock me-2"></i> Hak Akses (Role)
                </a>
            </div>
        </div>

        <div class="w-100 flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 shadow-sm">
                <div class="container-fluid p-0">
                    <span class="navbar-brand fw-semibold text-dark">Sistem Informasi Akademik</span>
                    <div class="ms-auto fw-bold text-primary">
                        <i class="bi bi-person-circle me-1"></i> Admin UMB
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>