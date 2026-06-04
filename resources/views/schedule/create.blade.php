@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-primary text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-calendar-plus me-2"></i> Tambah Jadwal Kuliah Baru
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Mata Kuliah</label>
                            <select name="course_id" class="form-select" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($courses as $mk)
                                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->sks }} SKS)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Dosen Pengampu</label>
                            <select name="lecturer_id" class="form-select" required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($lecturers as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->nama_dosen }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Ruangan / Lab</label>
                            <select name="room_id" class="form-select" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->nama_ruangan }} (Kap: {{ $ruang->kapasitas }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari</label>
                            <select name="hari" class="form-select" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kelas</label>
                            <input type="text" name="kelas" class="form-control" placeholder="Contoh: A, B, atau TIF 4A" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection