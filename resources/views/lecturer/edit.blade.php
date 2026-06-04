@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-warning text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-edit me-2"></i> Edit Data Dosen
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIDN</label>
                            <input type="text" name="nidn" class="form-control" value="{{ $dosen->nidn }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_dosen" class="form-control" value="{{ $dosen->nama_dosen }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" value="{{ $dosen->tempat_lahir }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" value="{{ $dosen->tanggal_lahir }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L" {{ $dosen->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $dosen->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email_dosen" class="form-control" value="{{ $dosen->email_dosen }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" value="{{ $dosen->no_hp }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat_lengkap" class="form-control" rows="4" required>{{ $dosen->alamat_lengkap }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIK Karyawan</label>
                            <input type="text" name="nik_karyawan" class="form-control" value="{{ $dosen->nik_karyawan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $dosen->pendidikan_terakhir }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Fungsional</label>
                            <input type="text" name="jabatan_fungsional" class="form-control" value="{{ $dosen->jabatan_fungsional }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control" value="{{ $dosen->bidang_keahlian }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Ikatan Kerja</label>
                            <select name="status_kerja" class="form-select">
                                <option value="Dosen Tetap" {{ $dosen->status_kerja == 'Dosen Tetap' ? 'selected' : '' }}>Dosen Tetap</option>
                                <option value="Dosen LB" {{ $dosen->status_kerja == 'Dosen LB' ? 'selected' : '' }}>Dosen Luar Biasa (LB)</option>
                                <option value="Dosen DPK" {{ $dosen->status_kerja == 'Dosen DPK' ? 'selected' : '' }}>Dosen Diperbantukan (DPK)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fakultas</label>
                            <input type="text" name="fakultas" class="form-control" value="{{ $dosen->fakultas }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Mulai Tugas</label>
                            <input type="number" name="tahun_masuk" class="form-control" value="{{ $dosen->tahun_masuk }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dosen.index') }}" class="btn btn-secondary px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2">Simpan Data Dosen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection