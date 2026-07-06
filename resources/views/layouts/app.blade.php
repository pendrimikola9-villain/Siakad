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
            overflow-x: hidden;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            width: 260px;
            transition: all 0.25s ease-out;
        }
        .list-group-item {
            border: none;
            padding: 12px 20px;
            transition: all 0.2s ease;
        }
        .list-group-item:hover {
            background-color: #343a40 !important;
            color: #fff !important;
        }
        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="d-flex" id="wrapper">
        
        <div class="bg-dark text-white" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                SIAKAD UMB
            </div>
            
          <div class="list-group list-group-flush mt-3">
                
                <a href="/" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="bi bi-speedometer2 text-white me-2"></i> Dashboard
                </a>

              @if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'|| Auth::user()->role === 'dosen'))
                    <div class="small text-white-50 text-uppercase px-4 mt-3 mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Master Data</div>
                    <a href="/data-mahasiswa" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-people text-primary me-2"></i> Data Mahasiswa
                    </a>
                    <a href="{{ route('dosen.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-person-workspace text-success me-2"></i> Data Dosen
                    </a>
                    <a href="{{ route('room.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-door-open text-info me-2"></i> Data Ruangan / Lab
                    </a>
                    <a href="{{ route('courses.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-book text-warning me-2"></i> Mata Kuliah
                    </a>
                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-shield-lock text-danger me-2"></i> Hak Akses (Role)
                    </a>
                @endif

               @if(Auth::check() && ( Auth::user()->role === 'admin' || Auth::user()->role === 'operator'))
                    <div class="small text-white-50 text-uppercase px-4 mt-3 mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Fakultas Monitoring</div>
                    <a href="{{ route('jadwal.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-calendar3 me-2 text-warning"></i> Monitor SIPLAR
                    </a>
                    <a href="{{ route('bimbingan.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-chat-square-text me-2 text-info"></i> Monitor SIBIMBING
                    </a>
                @endif

                @if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'))
                   <div class="small text-white-50 text-uppercase px-4 mt-3 mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Manajemen Akademik</div>
                    <a href="{{ route('kaprodi.kurikulum') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-check2-square text-warning me-2"></i> Validasi Kurikulum
                    </a>
                    <a href="{{ route('kaprodi.laporan') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-file-earmark-bar-graph text-info me-2"></i> Laporan Academic
                    </a>
                @endif

                @if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'|| Auth::user()->role === 'dosen'))
                    <a href="{{ route('grades.index') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-pencil-square text-danger me-2"></i> Input Nilai
                    </a>
                @endif

                @if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'|| Auth::user()->role === 'dosen'|| Auth::user()->role === 'mahasiswa'))
                    <div class="small text-white-50 text-uppercase px-4 mt-3 mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Fitur Academic</div>
                    
                    <a href="{{ route('mahasiswa.siplar') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-calendar3 me-2 text-primary"></i> Jadwal Kuliah 
                    </a>

                    <a href="{{ route('mahasiswa.presensi') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-calendar-check me-2 text-info"></i> Presensi Kuliah
                    </a>

                    <a href="{{ route('mahasiswa.tugas') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-journal-arrow-up me-2 text-success"></i> Bahan & Tugas
                    </a>
                    
                    <a href="{{ route('mahasiswa.krs') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-book me-2 text-danger"></i> Pengisian KRS
                    </a>
                    
                    <a href="{{ route('grades.index') }}"" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-file-earmark-bar-graph me-2 text-warning"></i> Tampilan Nilai
                    </a>

                    <a href="{{ route('mahasiswa.sibimbing') }}" class="list-group-item list-group-item-action bg-dark text-white">
                        <i class="bi bi-chat-square-text me-2 text-white"></i> Log Bimbingan 
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="list-group-item bg-dark p-0 mt-4 border-top border-secondary border-opacity-20">
                    @csrf
                    <button type="submit" class="btn btn-link text-white text-start w-100 h-100 text-decoration-none py-3 px-4 shadow-none">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i> Keluar Portal
                    </button>
                </form>
            </div>
        </div> 

        <div id="page-content-wrapper" class="flex-grow-1">
            
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 shadow-sm">
                <div class="container-fluid p-0">
                    <span class="navbar-brand fw-semibold text-dark fs-5">Sistem Informasi Akademik (SIAKAD)</span>
                    
                    <div class="ms-auto fw-bold text-primary d-flex align-items-center">
                        
                      <div class="dropdown me-4">
    <a href="#" class="text-secondary position-relative text-decoration-none" id="dropdownMenuNotif" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-4"></i>
        @if(($globalNotificationCount ?? 0) > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 4px 6px;">
                {{ $globalNotificationCount }}
            </span>
        @else
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary bg-opacity-50" style="font-size: 0.65rem; padding: 4px 6px;">
                0
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" aria-labelledby="dropdownMenuNotif" style="width: 320px; font-size: 0.85rem; max-height: 400px; overflow-y: auto;">
        <li class="p-2 border-bottom fw-bold text-dark d-flex justify-content-between align-items-center">
            <span>Pemberitahuan Terbaru</span>
            @if(($globalNotificationCount ?? 0) > 0)
                <span class="badge bg-primary-subtle text-primary small" style="font-size: 0.7rem;">Baru</span>
            @endif
        </li>
        
      @forelse($globalNotifications ?? [] as $notif)
            <li class="border-bottom border-light">
                <div class="p-3 dropdown-item text-wrap rounded-2 my-1 bg-light bg-opacity-50">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <strong class="text-dark" style="font-size: 0.8rem;">
                            <i class="bi bi-info-circle-fill text-primary me-1"></i> SIPLAR Info
                        </strong>
                        <small class="text-muted font-monospace" style="font-size: 0.65rem;">
                            {{ \Carbon\Carbon::parse($notif->updated_at)->diffForHumans() }}
                        </small>
                    </div>
                    <p class="mb-1 text-secondary" style="font-size: 0.8rem; line-height: 1.4;">
                        Mata Kuliah <strong>{{ $notif->nama_mk }}</strong> (Kelas {{ $notif->kelas }}): {{ $notif->keterangan_status }}
                    </p>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">
                         {{ $notif->status_dosen }}
                    </span>
                </div>
            </li>
        @empty
            <li>
                <div class="p-4 text-center text-muted small">
                    <i class="bi bi-bell-slash d-block fs-3 mb-2 text-black-50"></i>
                    Belum ada pemberitahuan baru hari ini.
                </div>
            </li>
        @endforelse
    </ul>
</div>

                       <div class="d-flex align-items-center">
    <i class="bi bi-person-circle me-2 fs-4 text-secondary"></i>
    <div>
        <span class="d-block text-dark lh-1 fs-6 fw-bold">{{ Auth::user()?->name ?? 'User UMB' }}</span>
        <small class="text-muted fw-normal text-capitalize" style="font-size: 0.75rem;">
            Panel: <span class="badge @if(Auth::user()?->role === 'operator') bg-dark text-white @else bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 @endif text-capitalize">{{ Auth::user()?->role ?? 'Guest' }}</span>
        </small>
    </div>
</div>

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