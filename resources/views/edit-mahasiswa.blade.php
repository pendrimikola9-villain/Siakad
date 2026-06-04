<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa | Sistem Akademik</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .card { border: none; border-radius: 15px; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark p-3">
                    <h5 class="mb-0">Ubah Data Mahasiswa: {{ $mahasiswa->nama }}</h5>
                </div>
                <div class="card-body p-4">
                    
               <form action="{{ route('update-mahasiswa', $mahasiswa->id) }}" method="POST">
    @csrf
    @method('PUT') ```

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <h6 class="text-muted border-bottom pb-2 mb-3">Identitas Pribadi</h6>
                                <div class="mb-3">
                                    <label class="form-label">NIM</label>
                                    <input type="text" name="nim" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
                                    <small class="text-muted">NIM tidak dapat diubah</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $mahasiswa->nama }}" required>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-6">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ $mahasiswa->tempat_lahir }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ $mahasiswa->tanggal_lahir }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select">
                                        <option value="Laki-laki" {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3">{{ $mahasiswa->alamat }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $mahasiswa->email }}">
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <h6 class="text-muted border-bottom pb-2 mb-3">Akademik & Orang Tua</h6>
                                <div class="mb-3">
                                    <label class="form-label">Program Studi</label>
                                    <input type="text" name="prodi" class="form-control" value="{{ $mahasiswa->prodi }}">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">Semester</label>
                                            <input type="number" name="semester" class="form-control" value="{{ $mahasiswa->semester }}">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label class="form-label">IPK</label>
                                            <input type="number" step="0.01" name="ipk_terakhir" class="form-control" value="{{ $mahasiswa->ipk_terakhir }}">
                                        </div>
                                    </div>
                                </div>
                              <div class="mb-3">
    <label class="form-label fw-bold">Dosen Pembimbing</label>
    <select name="dosen_pembimbing" class="form-select" required>
        @foreach($lecturers as $dosen)
            <option value="{{ $dosen->nama_dosen }}" {{ $mahasiswa->dosen_pembimbing == $dosen->nama_dosen ? 'selected' : '' }}>
                {{ $dosen->nama_dosen }}
            </option>
        @endforeach
    </select>
</div>
                                <div class="mb-3">
                                    <label class="form-label">No. HP Aktif</label>
                                    <input type="text" name="no_hp" class="form-control" value="{{ $mahasiswa->no_hp }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" class="form-control" value="{{ $mahasiswa->nama_ayah }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" class="form-control" value="{{ $mahasiswa->nama_ibu }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Mahasiswa</label>
                                    <select name="status_mahasiswa" class="form-select fw-bold">
                                        <option value="Aktif" {{ $mahasiswa->status_mahasiswa == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Cuti" {{ $mahasiswa->status_mahasiswa == 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                        <option value="Alumni" {{ $mahasiswa->status_mahasiswa == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-warning px-5 shadow-sm fw-bold">Update Data</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>