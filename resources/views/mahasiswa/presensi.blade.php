@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
    <div class="card-body p-4 position-relative">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-1"><i class="bi bi-calendar-check me-2"></i>Presensi Kuliah</h3>
                <p class="mb-0 opacity-75">Pantau persentase kehadiran Anda. Batas minimal kelayakan mengikuti UTS/UAS adalah <strong>80%</strong>.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                    <i class="bi bi-shield-exclamation me-1"></i> Batas Minimal: 80%
                </span>
            </div>
        </div>
    </div>
</div>

    <div class="row g-4 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">Mata Kuliah</th>
                                    <th class="text-center">Total Pertemuan</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center" style="width: 250px;">Persentase Kehadiran</th>
                                    <th class="text-center">Syarat UTS / UAS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapAbsen as $ra)
                                <tr>
                                    <td class="fw-semibold text-dark py-3 px-4">{{ $ra->nama_mk ?? 'Pemrograman Web 2' }}</td>
                                    <td class="text-center fw-bold text-secondary">{{ $ra->total_pertemuan ?? 0 }}x</td>
                                    <td class="text-center fw-bold text-success">{{ $ra->total_hadir ?? 0 }}x</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fw-bold" style="min-width: 45px;">{{ ($ra->persentase ?? 0) . '%' }}</span>
                                            <div class="progress w-100" style="height: 8px;">
                                                <div class="progress-bar {{ ($ra->persentase ?? 0) >= 80 ? 'bg-success' : 'bg-danger' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ ($ra->persentase ?? 0) . '%' }}" 
                                                     aria-valuenow="{{ $ra->persentase ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if(($ra->layak_ujian ?? false) || ($ra->persentase ?? 0) >= 80)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-20">
                                                <i class="bi bi-check-circle-fill me-1"></i> Lolos (Siap Ujian)
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-20">
                                                <i class="bi bi-x-circle-fill me-1"></i> Cekal (&lt; 80%)
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-exclamation-circle me-1"></i> Belum ada data riwayat absensi berjalan pada database Anda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection