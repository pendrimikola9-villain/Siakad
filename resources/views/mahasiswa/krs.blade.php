@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i>Pengisian Kartu Rencana Studi (KRS)</h3>
                    <p class="mb-0 opacity-75">Silahkan tentukan paket mata kuliah yang akan Anda tempuh pada semester berjalan ini.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                        <i class="bi bi-calendar3 me-1"></i> TA: 2025/2026 Genap
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong class="text-success">Berhasil!</strong>
                    <div class="text-secondary small">{{ session('success') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('mahasiswa.krs.simpan') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-list-check text-primary me-2"></i>Daftar Kelas Ditawarkan</h5>
                <small class="text-muted font-monospace">Maksimal Beban SKS: 24 SKS</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary border-bottom">
                            <tr>
                                <th class="text-center py-3" style="width: 70px;">Ambil</th>
                                <th class="py-3 px-4">Kode MK</th>
                                <th class="py-3">Nama Mata Kuliah</th>
                                <th class="text-center py-3">Beban SKS</th>
                                <th class="text-center py-3">Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            @forelse($katalogMatkul as $item)
                                <tr class="border-bottom border-light">
                                    <td class="text-center py-3">
                                        <div class="form-check d-flex justify-content-center p-0">
                                            <input type="checkbox" name="matkul[]" value="{{ $item->id }}" 
                                                   class="form-check-input border-secondary-subtle rounded-3 shadow-none" 
                                                   style="width: 1.35rem; height: 1.35rem; cursor: pointer;"
                                                   {{ in_array($item->id, $krsDiambil) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-monospace fw-bold text-primary">{{ $item->kode_mk }}</td>
                                    <td class="py-3">
                                        <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $item->nama_mk }}</div>
                                        <span class="text-muted style" style="font-size: 0.75rem;">Fakultas Teknik / Informatika</span>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-3 py-2 rounded-pill fw-bold">
                                            {{ $item->sks }} SKS
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-3 text-capitalize">
                                            Semester {{ $item->semester }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <div class="py-4">
                                            <i class="bi bi-folder-x fs-1 text-black-50 d-block mb-3"></i>
                                            <span class="fw-semibold d-block text-secondary">Belum ada kelas dibuka</span>
                                            <small class="text-muted">Silahkan jalankan seeder mata kuliah di terminal Anda.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(count($katalogMatkul) > 0)
                <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center p-3 px-4">
                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Pastikan pilihan kelas tidak tabrakan.</span>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow rounded-3">
                        <i class="bi bi-check-all me-1 fs-5 align-middle"></i> Simpan & Ajukan KRS
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection