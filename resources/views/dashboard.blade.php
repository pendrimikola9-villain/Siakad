@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2>Selamat Datang, {{ Auth::user()->name ?? 'User' }}!</h2>
        
        <p class="text-secondary">
    Anda masuk sebagai 
    <span class="badge @if(Auth::user()?->role === 'operator') bg-dark @elseif(Auth::user()?->role === 'admin') bg-primary @elseif(Auth::user()?->role === 'kaprodi') bg-warning text-dark @elseif(Auth::user()?->role === 'dosen') bg-success @else bg-info @endif text-capitalize fw-semibold">
        {{ Auth::user()?->role ?? 'Tamu' }}
    </span> 
    — Ringkasan data sistem akademik hari ini.
</p>
    </div>
</div>

<div class="row g-4">
    
    @if(Auth::user()->role === 'operator')
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-dark text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">Total Mahasiswa</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalMhs }}</h2> 
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3 small opacity-75">Fakultas Informatika</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">Dosen Aktif</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalDosen }}</h2>
                        </div>
                        <i class="bi bi-person-workspace fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3 small opacity-75">Dosen Terdaftar</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">Mata Kuliah</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalMatkul }}</h2>
                        </div>
                        <i class="bi bi-book fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3 small opacity-75">Kurikulum Aktif</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="font-size: 0.8rem; opacity: 0.8;">Jumlah Prodi</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalProdi }}</h2>
                        </div>
                        <i class="bi bi-building fs-1 opacity-50"></i>
                    </div>
                    <div class="mt-3 small opacity-75">Program Studi UMB</div>
                </div>
            </div>
        </div>

    @elseif(Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi')
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Mahasiswa</h6>
                            <h2 class="mb-0">{{ $totalMhs }}</h2> 
                        </div>
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('data-mahasiswa') }}" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
                    @else
                        <div class="mt-3 small">Mahasiswa Terdaftar</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Laki-laki</h6>
                            <h2 class="mb-0">{{ $totalLaki }}</h2>
                        </div>
                        <i class="bi bi-gender-male fs-1"></i>
                    </div>
                    <div class="mt-3 small">Mahasiswa Putra</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Perempuan</h6>
                            <h2 class="mb-0">{{ $totalPerempuan }}</h2>
                        </div>
                        <i class="bi bi-gender-female fs-1"></i>
                    </div>
                    <div class="mt-3 small">Mahasiswa Putri</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Dosen & Ruangan</h6>
                            <h2 class="mb-0" style="font-size: 1.4rem;">
                                {{ \Illuminate\Support\Facades\DB::table('lecturers')->count() }} Dosen / {{ \Illuminate\Support\Facades\DB::table('rooms')->count() }} Lab
                            </h2>
                        </div>
                        <i class="bi bi-book fs-1"></i>
                    </div>
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('dosen.index') }}" class="btn btn-light btn-sm mt-3 w-100">Buka Manajemen Dosen</a>
                    @else
                        <div class="mt-3 small">Fakultas Informatika UMB</div>
                    @endif
                </div>
            </div>
        </div>

    @elseif(Auth::user()->role === 'dosen')
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-1">Butuh ACC Bimbingan</h6>
                        <h2 class="mb-0">
                            {{ \Illuminate\Support\Facades\DB::table('consultation_logs')->where('status_bimbingan', '!=', 'ACC')->count() }} Log
                        </h2>
                    </div>
                    <i class="bi bi-chat-left-dots-fill fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm bg-white p-2 border-start border-success border-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-clock text-success me-2"></i>Agenda Mengajar Terdekat</h6>
                    <span class="small text-muted">Pemrograman Web 2 (Praktikum) | Ruang Lab Komputer 3 Kampus Utama UMB.</span>
                </div>
            </div>
        </div>

    @else
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white p-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-uppercase mb-1">Indeks Prestasi Kumulatif</h6>
                        <h2 class="fw-bold mb-0">3.65</h2>
                    </div>
                    <i class="bi bi-graph-up-arrow fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm bg-white p-2 border-start border-info border-4">
                <div class="card-body">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-person-workspace text-info me-2"></i>Dosen Wali Akademik (PA)</h6>
                    <span class="small text-muted fw-semibold text-dark">Muhammad Musthofa, M.Cs.</span> 
                    <span class="small text-muted"> — Gunakan modul SIBIMBING untuk melakukan pengajuan bimbingan log.</span>
                </div>
            </div>
        </div>
    @endif

</div>

@if(Auth::user()->role === 'operator')
<div class="row mt-5 animate__animated animate__fadeIn">
    <div class="col-md-7 mb-4">
        <div class="card shadow border-0 p-4 bg-white rounded-3">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Statistik Mahasiswa per Program Studi</h5>
            <div style="height: 300px;">
                <canvas id="chartProdiOperator"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-5 mb-4">
        <div class="card shadow border-0 p-4 bg-white rounded-3">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-pie-chart-fill text-success me-2"></i>Demografi Asal Mahasiswa</h5>
            <div style="height: 300px;">
                <canvas id="chartAsalOperator"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

@if(Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi')
<div class="row mt-5 animate__animated animate__fadeIn">
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 p-3 bg-white">
            <h5 class="fw-bold mb-3 text-center text-dark">Sebaran Program Studi</h5>
            <div style="height: 300px;">
                <canvas id="chartProdi"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 p-3 bg-white">
            <h5 class="fw-bold mb-3 text-center text-dark">Asal Wilayah Mahasiswa</h5>
            <div style="height: 300px;">
                <canvas id="chartAsal"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4 @if(Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'operator') mt-2 @else mt-4 @endif mb-5">
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header bg-white border-0 py-3 fw-bold text-dark border-bottom">
                <i class="bi bi-calendar3 text-primary me-2"></i> Jadwal Kuliah Terbaru (SIPLAR)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>Ruangan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recent_schedules = \Illuminate\Support\Facades\DB::table('class_schedules')
                                    ->join('courses', 'class_schedules.course_id', '=', 'courses.id')
                                    ->join('rooms', 'class_schedules.room_id', '=', 'rooms.id')
                                    ->select('class_schedules.*', 'courses.nama_mk', 'rooms.nama_ruangan')
                                    ->orderBy('class_schedules.id', 'desc')
                                    ->limit(3)
                                    ->get();
                            @endphp
                            @forelse($recent_schedules as $js)
                            <tr>
                                <td class="fw-semibold text-dark py-2 px-3">{{ $js->nama_mk }}</td>
                                <td><span class="badge bg-secondary">{{ $js->nama_ruangan }}</span></td>
                                <td><small class="text-muted">{{ $js->hari }}, {{ substr($js->jam_mulai, 0, 5) }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center p-3 text-muted">Belum ada data jadwal transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header bg-white border-0 py-3 fw-bold text-dark border-bottom">
                <i class="bi bi-chat-square-text text-success me-2"></i> Log Bimbingan Terbaru (SIBIMBING)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Mahasiswa</th>
                                <th>Topik Pembahasan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $user = Auth::user();
                                $role = strtolower($user->role);

                                if ($role === 'mahasiswa') {
                                    $recent_bimbingan = \Illuminate\Support\Facades\DB::table('consultation_logs')
                                        ->where('consultation_logs.mahasiswa_id', $user->id)
                                        ->orderBy('consultation_logs.id', 'desc')
                                        ->limit(3)
                                        ->get();
                                } else {
                                    $recent_bimbingan = \Illuminate\Support\Facades\DB::table('consultation_logs')
                                        ->orderBy('consultation_logs.id', 'desc')
                                        ->limit(3)
                                        ->get();
                                }
                            @endphp
                            
                            @forelse($recent_bimbingan as $rb)
                            <tr>
                                <td class="fw-semibold text-dark py-2 px-3">{{ $rb->nama_mahasiswa }}</td>
                                <td class="text-truncate" style="max-width: 150px;">{{ $rb->topik_bimbingan }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $rb->status_bimbingan == 'ACC' ? 'bg-success' : ($rb->status_bimbingan == 'Ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $rb->status_bimbingan }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center p-3 text-muted">Belum ada aktivitas transaksi bimbingan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
        // Ambil data JSON dari backend
        const rawProdi = @json($prodiData ?? []);
        const rawAsal = @json($asalData ?? []);

        // JAVASCRIPT: RENDERING GRAFIK KHUSUS OPERATOR FAKULTAS
        @if(Auth::user()->role === 'operator')
            const ctxProdiOp = document.getElementById('chartProdiOperator');
            if (ctxProdiOp && rawProdi.length > 0) {
                new Chart(ctxProdiOp, {
                    type: 'bar', 
                    data: {
                        labels: rawProdi.map(i => i.prodi),
                        datasets: [{
                            label: 'Jumlah Mahasiswa',
                            data: rawProdi.map(i => i.total),
                            backgroundColor: 'rgba(13, 110, 253, 0.8)',
                            borderRadius: 5
                        }]
                    },
                    options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
                });
            }

            const ctxAsalOp = document.getElementById('chartAsalOperator');
            if (ctxAsalOp && rawAsal.length > 0) {
                new Chart(ctxAsalOp, {
                    type: 'doughnut', 
                    data: {
                        labels: rawAsal.map(i => i.alamat),
                        datasets: [{
                            data: rawAsal.map(i => i.total),
                            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
                        }]
                    },
                    options: { maintainAspectRatio: false }
                });
            }
        @endif

        // KODE SCRIPT GRAFIK ASLI ADMIN / KAPRODI
        const ctxProdi = document.getElementById('chartProdi');
        if (ctxProdi && rawProdi && rawProdi.length > 0) {
            new Chart(ctxProdi, {
                type: 'pie',
                data: {
                    labels: rawProdi.map(i => i.prodi),
                    datasets: [{
                        data: rawProdi.map(i => i.total),
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6610f2']
                    }]
                },
                options: { maintainAspectRatio: false }
            });
        }

        const ctxAsal = document.getElementById('chartAsal');
        if (ctxAsal && rawAsal && rawAsal.length > 0) {
            new Chart(ctxAsal, {
                type: 'bar',
                data: {
                    labels: rawAsal.map(i => i.alamat),
                    datasets: [{
                        label: 'Jumlah Mahasiswa',
                        data: rawAsal.map(i => i.total),
                        backgroundColor: '#0d6efd'
                    }]
                },
                options: { maintainAspectRatio: false }
            });
        }
    });
</script>
@endsection