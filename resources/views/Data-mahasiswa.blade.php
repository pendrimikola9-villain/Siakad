<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white p-3">
            <h4 class="mb-0 px-2"><i class="bi bi-person-plus-fill me-2"></i>Tambah Data Mahasiswa</h4>
        </div>
        <div class="card-body p-4">
            <!-- Form diarahkan ke store-mahasiswa sesuai web.php -->
            <form action="{{ route('store-mahasiswa') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIM</label>
                            <input type="text" name="nim" class="form-control" placeholder="Contoh: 241001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" placeholder="Informatika">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Semester</label>
                            <input type="number" name="semester" class="form-control">
                        </div>
                        <div class="mb-3">
    <label class="form-label fw-bold">Dosen Pembimbing</label>
    <select name="dosen_pembimbing" class="form-select" required>
        <option value="">-- Pilih Dosen Pembimbing --</option>
        @foreach($lecturers as $dosen)
            <option value="{{ $dosen->nama_dosen }}">{{ $dosen->nama_dosen }}</option>
        @endforeach
    </select>
</div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">IPK Terakhir</label>
                            <input type="number" step="0.01" name="ipk_terakhir" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status_mahasiswa" class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Cuti">Cuti</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mt-4 text-end">
                    <!-- Link Batal diarahkan ke halaman tabel (data-mahasiswa) -->
                    <a href="{{ route('data-mahasiswa') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-success px-5 shadow-sm">Simpan Data Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>