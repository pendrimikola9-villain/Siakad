@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow border-0">
                <div class="card-header bg-info text-white p-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-person-badge me-2"></i>Detail Profil Mahasiswa</h4>
                    <a href="{{ route('data-mahasiswa') }}" class="btn btn-light btn-sm text-info fw-bold">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Bagian Kiri: Info Utama -->
                        <div class="col-md-6 border-end">
                            <h5 class="text-primary border-bottom pb-2">Informasi Akademik</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">NIM</th>
                                    <td>: {{ $mahasiswa->nim }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td>: {{ $mahasiswa->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Prodi</th>
                                    <td>: {{ $mahasiswa->prodi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Semester</th>
                                    <td>: {{ $mahasiswa->semester ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>IPK Terakhir</th>
                                    <td>: <span class="badge bg-warning text-dark">{{ $mahasiswa->ipk_terakhir ?? '0.00' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>: 
                                        <span class="badge {{ $mahasiswa->status_mahasiswa == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $mahasiswa->status_mahasiswa }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Bagian Kanan: Info Pribadi -->
                        <div class="col-md-6 px-4">
                            <h5 class="text-primary border-bottom pb-2">Informasi Pribadi</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Tempat Lahir</th>
                                    <td>: {{ $mahasiswa->tempat_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Lahir</th>
                                    <td>: {{ $mahasiswa->tanggal_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $mahasiswa->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>No. HP</th>
                                    <td>: {{ $mahasiswa->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>: {{ $mahasiswa->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="row mt-3 text-center">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Dosen Pembimbing</small>
                            <p class="fw-bold">{{ $mahasiswa->dosen_pembimbing ?? 'Belum Ditentukan' }}</p>
                        </div>
                        <div class="col-md-4 border-start border-end">
                            <small class="text-muted d-block">Nama Ayah</small>
                            <p class="fw-bold">{{ $mahasiswa->nama_ayah ?? '-' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Nama Ibu</small>
                            <p class="fw-bold">{{ $mahasiswa->nama_ibu ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light text-end p-3">
                    <a href="{{ route('edit-mahasiswa', $mahasiswa->id) }}" class="btn btn-warning text-white">
                        <i class="bi bi-pencil-square"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection