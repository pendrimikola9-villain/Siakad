@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">
    <div class="card shadow border-0 rounded-3">
        <div class="card-header bg-primary text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 px-2 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Mahasiswa</h5>
            @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen']))
                <span class="badge bg-warning text-dark"><i class="bi bi-eye-fill me-1"></i> Mode Lihat Saja (Read-Only)</span>
            @endif
        </div>
        <div class="card-body p-4">
            @php
                $isReadOnly = Auth::check() && in_array(strtolower(Auth::user()->role), ['kaprodi', 'dosen']);
            @endphp

            @if($isReadOnly)
                <div class="alert alert-info border-0 shadow-sm mb-4">
                    <i class="bi bi-info-circle-fill me-2"></i> Perhatian: Akun Anda (<strong>{{ ucfirst(Auth::user()->role) }}</strong>) hanya memiliki hak akses untuk melihat form ini dan tidak memiliki izin untuk menambah atau mengubah data mahasiswa.
                </div>
            @endif

            <!-- Form diarahkan ke store-mahasiswa sesuai web.php -->
            <form action="{{ route('store-mahasiswa') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Contoh: 241001" required {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select" {{ $isReadOnly ? 'disabled' : '' }}>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" {{ $isReadOnly ? 'readonly' : '' }}></textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" placeholder="Informatika" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Semester</label>
                            <input type="number" name="semester" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dosen Pembimbing</label>
                            <select name="dosen_pembimbing" class="form-select" required {{ $isReadOnly ? 'disabled' : '' }}>
                                <option value="">-- Pilih Dosen Pembimbing --</option>
                                @foreach($lecturers as $dosen)
                                    <option value="{{ $dosen->nama_dosen }}">{{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">IPK Terakhir</label>
                            <input type="number" step="0.01" name="ipk_terakhir" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" {{ $isReadOnly ? 'readonly' : '' }}>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status_mahasiswa" class="form-select" {{ $isReadOnly ? 'disabled' : '' }}>
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end align-items-center gap-2">
                    <a href="{{ route('data-mahasiswa') }}" class="btn btn-secondary px-4 fw-semibold">Kembali</a>
                    
                    @if(!$isReadOnly)
                        <button type="submit" class="btn btn-success px-5 fw-semibold shadow-sm">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Data Mahasiswa
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection