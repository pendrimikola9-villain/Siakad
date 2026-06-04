@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Daftar Matakuliah</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-2"></i>Tambah Matakuliah
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Nama Matakuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $key => $c)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><span class="badge bg-secondary">{{ $c->kode_mk }}</span></td>
                        <td>{{ $c->nama_mk }}</td>
                        <td>{{ $c->sks }} SKS</td>
                        <td>Semester {{ $c->semester }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <!-- Tombol Edit -->
                                <button class="btn btn-sm btn-warning text-white shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $c->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('courses.destroy', $c->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Hapus matakuliah ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $c->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('courses.update', $c->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title text-white">Edit Matakuliah</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <div class="mb-3">
                                            <label class="fw-bold">Kode Matakuliah</label>
                                            <input type="text" name="kode_mk" class="form-control" value="{{ $c->kode_mk }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Nama Matakuliah</label>
                                            <input type="text" name="nama_mk" class="form-control" value="{{ $c->nama_mk }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Jumlah SKS</label>
                                            <input type="number" name="sks" class="form-control" value="{{ $c->sks }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Semester</label>
                                            <input type="number" name="semester" class="form-control" value="{{ $c->semester }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-warning text-white">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Matakuliah Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Kode Matakuliah</label>
                        <input type="text" name="kode_mk" class="form-control" placeholder="Contoh: MK001" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Nama Matakuliah</label>
                        <input type="text" name="nama_mk" class="form-control" placeholder="Contoh: Pemrograman Web" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">SKS</label>
                        <input type="number" name="sks" class="form-control" placeholder="2" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Semester</label>
                        <input type="number" name="semester" class="form-control" placeholder="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection