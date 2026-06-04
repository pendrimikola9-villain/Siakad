@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-info text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-user me-2"></i> Detail Profil Lengkap Dosen
            </h5>
        </div>

        <div class="card-body p-4 bg-white">
            <div class="row">
                
                <div class="col-md-6 border-end">
                    <h5 class="text-primary border-bottom pb-2 mb-3 fw-bold">Informasi Pribadi</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">NIDN</label>
                        <span class="fs-5 text-dark fw-semibold">{{ $dosen->nidn }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Nama Lengkap</label>
                        <span class="fs-5 text-dark fw-semibold">{{ $dosen->nama_dosen }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Tempat, Tanggal Lahir</label>
                        <span class="text-dark">{{ $dosen->tempat_lahir }}, {{ \Carbon\Carbon::parse($dosen->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Jenis Kelamin</label>
                        <span class="text-dark">{{ $dosen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Email Resmi</label>
                        <span class="text-dark text-primary"><i class="bi bi-envelope me-1"></i>{{ $dosen->email_dosen }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">No. HP / WhatsApp</label>
                        <span class="text-dark"><i class="bi bi-whatsapp text-success me-1"></i>{{ $dosen->no_hp }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Alamat Rumah Domisili</label>
                        <span class="text-dark d-block text-justify bg-light p-2 rounded" style="font-size: 0.95rem;">{{ $dosen->alamat_lengkap }}</span>
                    </div>
                </div>

                <div class="col-md-6 ps-md-4">
                    <h5 class="text-primary border-bottom pb-2 mb-3 fw-bold">Informasi Akademik</h5>
                    
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">NIK Karyawan</label>
                        <span class="fs-5 text-dark fw-semibold">{{ $dosen->nik_karyawan }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Pendidikan Terakhir</label>
                        <span class="text-dark fw-semibold">{{ $dosen->pendidikan_terakhir }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Jabatan Fungsional</label>
                        <span class="text-dark">{{ $dosen->jabatan_fungsional }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Bidang Keahlian Utama</label>
                        <span class="badge bg-warning text-dark fs-6 py-2 px-3 fw-normal">{{ $dosen->bidang_keahlian }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Status Ikatan Kerja</label>
                        <span class="badge bg-success py-2 px-3 fs-6 fw-normal">{{ $dosen->status_kerja ?? 'Dosen Tetap' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Fakultas / Homebase</label>
                        <span class="text-dark">{{ $dosen->fakultas ?? 'Fakultas Teknik' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small fw-bold">Tahun Mulai Tugas di UMB</label>
                        <span class="text-dark fw-bold">{{ $dosen->tahun_masuk ?? '-' }}</span>
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-start">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary px-4 py-2 shadow-sm rounded-2 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

    </div>
</div>
@endsection