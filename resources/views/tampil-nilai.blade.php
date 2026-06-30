@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-file-earmark-bar-graph me-2"></i>Tampilan Nilai Akademik</h3>
                    <p class="mb-0 opacity-75">Lihat dan pantau rekapitulasi nilai transkrip perkuliahan Anda secara transparan.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                        <i class="bi bi-award-fill me-1"></i> KHS & Transkrip
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Data Transaksi Nilai (Gabungan 3 Tabel)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary border-bottom">
                        <tr>
                            <th class="text-center py-3" style="width: 70px;">No</th>
                            <th class="py-3 px-4">Nama Mahasiswa</th>
                            <th class="py-3">Mata Kuliah</th>
                            <th class="text-center py-3" style="width: 120px;">Beban SKS</th>
                            <th class="text-center py-3" style="width: 150px;">Nilai Angka</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($dataNilai as $key => $item)
                            <tr class="border-bottom border-light">
                                <td class="text-center fw-bold text-secondary py-3">{{ $key + 1 }}</td>
                                <td class="py-3 px-4 fw-semibold text-dark">{{ $item->nama }}</td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $item->nama_mk }}</div>
                                    <span class="text-muted" style="font-size: 0.75rem;">Fakultas Teknik / Informatika</span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold">
                                        {{ $item->sks }} SKS
                                    </span>
                                </td>
                                <td class="text-center py-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-3 py-2 rounded-pill fw-bold fs-6">
                                        {{ $item->nilai }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-folder-x fs-1 text-black-50 d-block mb-3"></i>
                                        <span class="fw-semibold d-block text-secondary">Belum ada data transaksi nilai.</span>
                                        <small class="text-muted">Nilai transkrip Anda akan muncul setelah divalidasi oleh Dosen Pengampu.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-light border-0 p-3 px-4">
            <a href="/" class="btn btn-secondary px-3 py-2 rounded-3 fw-bold small shadow-sm">
                <i class="bi bi-arrow-left-short fs-5 align-middle me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection