@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-warning text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-edit me-2"></i> Edit Jadwal Kuliah
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('jadwal.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Mata Kuliah</label>
                            <select name="course_id" class="form-select" required>
                                @foreach($courses as $mk)
                                    <option value="{{ $mk->id }}" {{ $schedule->course_id == $mk->id ? 'selected' : '' }}>
                                        {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Dosen Pengampu</label>
                            <select name="lecturer_id" class="form-select" required>
                                @foreach($lecturers as $dosen)
                                    <option value="{{ $dosen->id }}" {{ $schedule->lecturer_id == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Ruangan / Lab</label>
                            <select name="room_id" class="form-select" required>
                                @foreach($rooms as $ruang)
                                    <option value="{{ $ruang->id }}" {{ $schedule->room_id == $ruang->id ? 'selected' : '' }}>
                                        {{ $ruang->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Hari</label>
                            <select name="hari" class="form-select" required>
                                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                                    <option value="{{ $h }}" {{ $schedule->hari == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" value="{{ substr($schedule->jam_mulai, 0, 5) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" value="{{ substr($schedule->jam_selesai, 0, 5) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kelas</label>
                            <input type="text" name="kelas" class="form-control" value="{{ $schedule->kelas }}" required>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('jadwal.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection