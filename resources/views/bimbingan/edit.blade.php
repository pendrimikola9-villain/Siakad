@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-warning text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-edit me-2"></i> Edit Log Bimbingan Akademik
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('bimbingan.update', $log->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Mahasiswa</label>
                            <select name="mahasiswa_id" class="form-select" required>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}" {{ $log->mahasiswa_id == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->nim }} - {{ $mhs->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Dosen Pembimbing</label>
                            <select name="lecturer_id" class="form-select" required>
                                @foreach($lecturers as $dosen)
                                    <option value="{{ $dosen->id }}" {{ $log->lecturer_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Lokasi Ruangan</label>
                            <select name="room_id" class="form-select" required>
                                @foreach($rooms as $ruang)
                                    <option value="{{ $ruang->id }}" {{ $log->room_id == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Bimbingan</label>
                            <input type="date" name="tanggal_bimbingan" class="form-control" value="{{ $log->tanggal_bimbingan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Hasil Bimbingan</label>
                            <select name="status_bimbingan" class="form-select" required>
                                <option value="Revisi" {{ $log->status_bimbingan == 'Revisi' ? 'selected' : '' }}>Revisi</option>
                                <option value="ACC" {{ $log->status_bimbingan == 'ACC' ? 'selected' : '' }}>ACC</option>
                                <option value="Selesai" {{ $log->status_bimbingan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Topik / Catatan Pembahasan Bimbingan</label>
                            <textarea name="topik_bimbingan" class="form-control" rows="4" required>{{ $log->topik_bimbingan }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection