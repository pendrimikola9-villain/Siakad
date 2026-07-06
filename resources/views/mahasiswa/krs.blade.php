@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i>Portal Rencana Studi (KRS)</h3>
                    <p class="mb-0 opacity-75">
                        @if(Auth::user()?->role === 'mahasiswa')
                            Silahkan tentukan paket mata kuliah yang akan Anda tempuh pada semester berjalan ini.
                        @else
                            Panel Evaluasi, Pengesahan, dan Manajemen Distribusi Kartu Rencana Studi Mahasiswa UMB.
                        @endif
                    </p>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(Auth::user()?->role !== 'mahasiswa')
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 animate__animated animate__fadeInUp">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Pengajuan KRS Mahasiswa Bimbingan</h6>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-secondary">
            <tr>
                <th class="py-3 px-4">Mahasiswa</th>
                <th>Kelas / Angkatan</th>
                <th class="text-center">Total SKS Diajukan</th>
                <th class="text-center">Status Validasi</th>
                <th class="text-center">Aksi Persetujuan</th>
            </tr>
        </thead>
        <tbody>
     @forelse($daftarPengajuanKrs ?? [] as $mhsKrs)
<tr class="border-bottom border-light">
    <!-- Kolom 1: Nama Mahasiswa -->
    <td class="py-3 px-4">
        <div class="fw-bold text-dark mb-0">{{ $mhsKrs->name }}</div>
        <small class="text-muted"><i class="bi bi-person-check text-primary"></i> Mahasiswa Aktif</small>
    </td>
    
    <!-- Kolom 2: Kelas / Angkatan -->
    <td>
        <span class="badge bg-light text-dark border">{{ $mhsKrs->kelas ?? '41 TI' }}</span>
        <small class="text-muted d-block">Angkatan {{ $mhsKrs->angkatan ?? '2024' }}</small>
    </td>
    
    <!-- Kolom 3: Total SKS Diajukan -->
    <td class="text-center fw-bold text-primary">
        {{ $mhsKrs->total_sks ?? 0 }} SKS
    </td>
    
    <!-- Kolom 4: Status Validasi -->
    <td class="text-center">
        @if(($mhsKrs->status_krs ?? 'Pending') === 'Disetujui')
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill fw-bold">
                <i class="bi bi-shield-check me-1"></i> Terverifikasi
            </span>
        @else
            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill fw-bold">
                <i class="bi bi-hourglass-split me-1"></i> Pending
            </span>
        @endif
    </td>

    <!-- Kolom 5: Aksi Persetujuan -->
    <td class="text-center">
        <div class="d-inline-flex gap-2">
            @if(($mhsKrs->status_krs ?? 'Pending') !== 'Disetujui')
                <form action="{{ route('krs.approve', $mhsKrs->id) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success rounded-3 px-3 fw-bold">
                        <i class="bi bi-check-lg"></i> Setujui
                    </button>
                </form>
            @endif
            <a href="{{ route('mahasiswa.krs') }}?student_id={{ $mhsKrs->id }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-bold">
                <i class="bi bi-eye"></i> Detail / Edit
            </a>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center py-4 text-muted small">Belum ada mahasiswa yang mengajukan KRS.</td>
</tr>
@endforelse
        </tbody>
    </table>
</div>
            </div>
        </div>
    @endif

    @if(Auth::user()?->role === 'mahasiswa' || request()->has('student_id'))
        <form action="{{ route('mahasiswa.krs.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ request('student_id', Auth::id()) }}">

            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-list-check text-primary me-2"></i>
                        {{ request()->has('student_id') ? 'Lembar Pengeditan KRS Mahasiswa' : 'Daftar Kelas Ditawarkan' }}
                    </h5>
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
                                                       {{ in_array($item->id, $krsDiambil ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-monospace fw-bold text-primary">{{ $item->kode_mk }}</td>
                                        <td class="py-3">
                                            <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $item->nama_mk }}</div>
                                            <span class="text-muted" style="font-size: 0.75rem;">Fakultas Teknik / Informatika Engineering</span>
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
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Pastikan pilihan kelas tidak tabrakan jadwal kuliah perkuliahan.</span>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow rounded-3">
                            <i class="bi bi-check-all me-1 fs-5 align-middle"></i> 
                            {{ Auth::user()?->role === 'mahasiswa' ? 'Simpan & Ajukan KRS' : 'Simpan Update KRS Mahasiswa' }}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @endif
</div>
@endsection