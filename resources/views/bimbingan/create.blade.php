@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-primary text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i> Tambah Log Bimbingan Baru
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('bimbingan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Mahasiswa</label>
                            <select name="mahasiswa_id" class="form-select" required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->nim }} - {{ $mhs->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Dosen Pembimbing</label>
                            <select name="lecturer_id" class="form-select" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($lecturers as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Lokasi Ruangan Bimbingan</label>
                            <select name="room_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->nama_ruangan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Bimbingan</label>
                            <input type="date" name="tanggal_bimbingan" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Hasil Bimbingan</label>
                            <select name="status_bimbingan" class="form-select" required>
                                <option value="Revisi">Revisi</option>
                                <option value="ACC">ACC</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Topik / Catatan Pembahasan Bimbingan</label>
                            <textarea name="topik_bimbingan" class="form-control" rows="4" placeholder="Contoh: Pembahasan BAB 3 Metodologi Penelitian atau Revisi Layout Antarmuka Aplikasi" required></textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Log</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection