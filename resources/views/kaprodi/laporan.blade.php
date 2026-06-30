@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark"><i class="bi bi-file-earmark-bar-graph text-info me-2"></i>Laporan Management Akademik</h2>
            <p class="text-secondary">Rekapitulasi berkas kuantitas data mahasiswa aktif dan grafik capaian kelulusan program studi.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <button class="btn btn-outline-danger shadow-sm fw-bold rounded-3" onclick="alert('Fitur eksport PDF sedang dipersiapkan!')">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan Prodi
            </button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 border-start border-info border-4">
                <small class="text-muted fw-bold text-uppercase">Binaan Mahasiswa</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalMhsProdi }} Jiwa</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase">Rata-rata IPK Prodi</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $rataIpk }} / 4.00</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase">Status Akreditasi</small>
                <h3 class="fw-bold text-primary mb-0 mt-1">Baik Sekali (B)</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-5">
        <div class="card-header bg-white p-3 border-bottom px-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-bar-chart text-secondary me-2"></i>Distribusi Kuantitas Mahasiswa per Angkatan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th width="10%" class="text-center py-3">No</th>
                            <th class="py-3">Tahun Angkatan</th>
                            <th class="py-3 text-center">Jumlah Mahasiswa Aktif</th>
                            <th class="py-3 text-center">Rasio Target Lulus Tepat Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angkatanData as $index => $angkatan)
                            <tr class="border-bottom border-light">
                                <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                                <td><span class="fw-bold text-dark">Tahun 20{{ $angkatan->tahun }}</span></td>
                                <td class="text-center"><span class="badge bg-info px-3 py-2 text-white fw-bold fs-6 rounded-3">{{ $angkatan->total }} Mhs</span></td>
                                <td class="text-center">
                                    <div class="progress mx-auto shadow-sm" style="height: 10px; max-width: 200px;">
                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-success fw-bold d-block mt-1">85% Optimal</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Data pembagian angkatan belum terhitung di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection