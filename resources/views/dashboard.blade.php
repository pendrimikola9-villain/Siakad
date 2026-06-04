@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h2>Selamat Datang, Admin!</h2>
        <p class="text-secondary">Ringkasan data sistem akademik hari ini.</p>
    </div>
</div>

<div class="row g-4">
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
                <a href="{{ route('data-mahasiswa') }}" class="btn btn-light btn-sm mt-3 w-100">Lihat Detail</a>
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
                <a href="{{ route('dosen.index') }}" class="btn btn-light btn-sm mt-3 w-100">Buka Manajemen Dosen</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 p-3">
            <h5 class="fw-bold mb-3 text-center">Sebaran Program Studi</h5>
            <div style="height: 300px;">
                <canvas id="chartProdi"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow border-0 p-3">
            <h5 class="fw-bold mb-3 text-center">Asal Wilayah Mahasiswa</h5>
            <div style="height: 300px;">
                <canvas id="chartAsal"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2 mb-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-3">
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
        <div class="card border-0 shadow-sm rounded-3">
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
                                $recent_bimbingan = \Illuminate\Support\Facades\DB::table('consultation_logs')
                                    ->join('mahasiswas', 'consultation_logs.mahasiswa_id', '=', 'mahasiswas.id')
                                    ->select('consultation_logs.*', 'mahasiswas.nama as nama_mahasiswa')
                                    ->orderBy('consultation_logs.id', 'desc')
                                    ->limit(3)
                                    ->get();
                            @endphp
                            @forelse($recent_bimbingan as $rb)
                            <tr>
                                <td class="fw-semibold text-dark py-2 px-3">{{ $rb->nama_mahasiswa }}</td>
                                <td class="text-truncate" style="max-width: 150px;">{{ $rb->topik_bimbingan }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $rb->status_bimbingan == 'ACC' ? 'bg-success' : 'bg-warning text-dark' }}">
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
        const rawProdi = @json($prodiData);
        const rawAsal = @json($asalData);

        console.log("Cek Data Prodi:", rawProdi);

        if (rawProdi && rawProdi.length > 0) {
            new Chart(document.getElementById('chartProdi'), {
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

        if (rawAsal.length > 0) {
            new Chart(document.getElementById('chartAsal'), {
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