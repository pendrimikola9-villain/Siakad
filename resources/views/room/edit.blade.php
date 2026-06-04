@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-warning text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-edit me-2"></i> Edit Data Ruangan
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('room.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Ruangan / Laboratorium</label>
                            <input type="text" name="nama_ruangan" class="form-control" value="{{ $room->nama_ruangan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kapasitas (Jumlah Mahasiswa)</label>
                            <input type="number" name="kapasitas" class="form-control" value="{{ $room->kapasitas }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Peruntukan Ruangan</label>
                            <select name="jenis_ruangan" class="form-select" required>
                                <option value="Teori" {{ $room->jenis_ruangan == 'Teori' ? 'selected' : '' }}>Ruang Kelas Teori</option>
                                <option value="Laboratorium" {{ $room->jenis_ruangan == 'Laboratorium' ? 'selected' : '' }}>Laboratorium Spesifik</option>
                                <option value="Aula" {{ $room->jenis_ruangan == 'Aula' ? 'selected' : '' }}>Aula / Gedung Serbaguna</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Gedung / Lantai</label>
                            <input type="text" name="lokasi_gedung" class="form-control" value="{{ $room->lokasi_gedung }}" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('room.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection