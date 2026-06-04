@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        
        <div class="card-header bg-primary text-white py-3 px-4 rounded-top-3 border-0">
            <h5 class="mb-0 fw-semibold d-flex align-items-center">
                <i class="fas fa-user-plus me-2"></i> Tambah Data Dosen
            </h5>
        </div>

        <div class="card-body p-4 bg-white">
            <form action="{{ route('dosen.store') }}" method="POST">
                @csrf
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIDN</label>
                            <input type="text" name="nidn" class="form-control" placeholder="Contoh: 1122334455" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_dosen" class="form-control" placeholder="Nama Beserta Gelar" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota Kelahiran" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email_dosen" class="form-control" placeholder="dosen@umb.ac.id" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Nomor WhatsApp" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <textarea name="alamat_lengkap" class="form-control" rows="4" placeholder="Alamat Domisili Lengkap" required></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIK Karyawan</label>
                            <input type="text" name="nik_karyawan" class="form-control" placeholder="Contoh: UMB-001" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_terakhir" class="form-control" placeholder="Contoh: S2 Teknik Informatika" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan Fungsional</label>
                            <input type="text" name="jabatan_fungsional" class="form-control" placeholder="Contoh: Lektor / Asisten Ahli" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control" placeholder="Contoh: Web Development" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Ikatan Kerja</label>
                            <select name="status_kerja" class="form-select">
                                <option value="Dosen Tetap">Dosen Tetap</option>
                                <option value="Dosen LB">Dosen Luar Biasa (LB)</option>
                                <option value="Dosen DPK">Dosen Diperbantukan (DPK)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fakultas</label>
                            <input type="text" name="fakultas" class="form-control" placeholder="Contoh: Teknik / FKIP">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Mulai Tugas</label>
                            <input type="number" name="tahun_masuk" class="form-control" placeholder="Contoh: 2020">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('dosen.index') }}" class="btn btn-secondary px-4 py-2 shadow-sm">Batal</a>
                    <button type="submit" class="btn btn-success px-4 py-2 shadow-sm">Simpan Data Dosen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection