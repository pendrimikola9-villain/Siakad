@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-primary text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i> Tambah Data Ruangan Baru
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('room.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ruangan / Laboratorium</label>
                            <input type="text" name="nama_ruangan" class="form-control" placeholder="Contoh: Lab Komputer Jaringan, Ruang Teori 10" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kapasitas (Jumlah Mahasiswa)</label>
                            <input type="number" name="kapasitas" class="form-control" placeholder="Contoh: 30" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Peruntukan Ruangan</label>
                            <select name="jenis_ruangan" class="form-select" required>
                                <option value="Teori">Ruang Kelas Teori</option>
                                <option value="Laboratorium">Laboratorium Spesifik</option>
                                <option value="Aula">Aula / Gedung Serbaguna</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Gedung / Lantai</label>
                            <input type="text" name="lokasi_gedung" class="form-control" placeholder="Contoh: Gedung Utama Lt. 2, Kampus UMB" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('room.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Data Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection