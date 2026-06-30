@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    
    <!-- HEADER CARD PREMIUM -->
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-1"><i class="bi bi-journal-plus me-2"></i>Ajukan Konsultasi / Bimbingan</h3>
            <p class="mb-0 opacity-75">Silakan isi detail logbook konsultasi akademik, pkl, magang, atau ajukan janji temu langsung hari ini.</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-primary me-2"></i>Formulir Pengajuan</h5>
        </div>
        
        <form action="{{ route('bimbingan.store') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                
                <div class="row">
                    <!-- 🟢 FITUR BARU: PILIH JENIS KONSULTASI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Jenis Konsultasi <span class="text-danger">*</span></label>
                        <select name="jenis_konsultasi" class="form-select rounded-3 @error('jenis_konsultasi') is-invalid @enderror" required>
                            <option value="Tugas Akhir / Skripsi">🎓 Tugas Akhir / Skripsi</option>
                            <option value="Praktik Kerja Lapangan (PKL)">💼 Praktik Kerja Lapangan (PKL)</option>
                            <option value="Magang Akademik">🏢 Magang Akademik</option>
                            <option value="Konsultasi Mata Kuliah / Tugas">📝 Konsultasi Mata Kuliah / Tugas</option>
                        </select>
                        @error('jenis_konsultasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PILIH DOSEN PEMBIMBING / DOSEN WALI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Dosen Pembimbing / Tujuan <span class="text-danger">*</span></label>
                        <select name="dosen_id" class="form-select rounded-3 @error('dosen_id') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Dosen --</option>
                            @foreach($lecturers as $dosen)
                                <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                            @endforeach
                        </select>
                        @error('dosen_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- TOPIK/BAB PEMBAHASAN -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Topik Pembahasan / Bab <span class="text-danger">*</span></label>
                        <input type="text" name="topik_bimbingan" class="form-control rounded-3 @error('topik_bimbingan') is-invalid @enderror" placeholder="Contoh: Bab III Metodologi / Progres Magang" required>
                        @error('topik_bimbingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- TANGGAL KONSULTASI -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-secondary">Tanggal Konsultasi <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_bimbingan" class="form-control rounded-3 @error('tanggal_bimbingan') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                        @error('tanggal_bimbingan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 🟢 FITUR BARU: REQUEST JANJI TEMU HARI INI -->
                <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="request_pertemuan" value="Ya" id="flexSwitchCheckDefault">
                        <label class="form-check-label fw-bold text-dark" for="flexSwitchCheckDefault">
                            🚀 Minta Janji Temu Tatap Muka Hari Ini?
                        </label>
                        <span class="d-block text-muted small">Aktifkan jika kamu ingin mengirimkan permintaan bertemu langsung dengan Dosen/Kaprodi hari ini di ruangan.</span>
                    </div>
                </div>

                <!-- DESKRIPSI PROGRESS / CATATAN MAHASISWA -->
                <div class="mb-2">
                    <label class="form-label fw-bold text-secondary">Deskripsi Materi Bimbingan / Catatan Konsultasi <span class="text-danger">*</span></label>
                    <textarea name="catatan_mahasiswa" class="form-control rounded-3 @error('catatan_mahasiswa') is-invalid @enderror" rows="4" placeholder="Tuliskan poin-poin yang ingin kamu konsultasikan atau pesan janji temu untuk dosen..." required></textarea>
                    @error('catatan_mahasiswa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            
            <div class="card-footer bg-light border-0 p-3 px-4 d-flex justify-content-between">
                <a href="{{ route('mahasiswa.sibimbing') }}" class="btn btn-secondary rounded-3 px-3 py-2 fw-bold small">
                    <i class="bi bi-arrow-left-short fs-5 align-middle me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold small shadow-sm">
                    <i class="bi bi-send-check me-1"></i>Ajukan Bimbingan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection