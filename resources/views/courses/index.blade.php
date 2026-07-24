@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold"><i class="bi bi-book me-2"></i>Daftar Matakuliah</h2>
        
        {{-- 🔒 HANYA ADMIN & OPERATOR YANG BISA TAMBAH MATAKULIAH --}}
        @if(in_array(auth()->user()->role, ['admin', 'operator']))
            <button class="btn btn-primary shadow-sm rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-2"></i>Tambah Matakuliah
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 bg-white overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th width="12%" class="py-3">Kode MK</th>
                            <th class="py-3">Nama Matakuliah</th>
                            <th width="10%" class="text-center py-3">SKS</th>
                            <th width="12%" class="text-center py-3">Semester</th>
                            <th width="20%" class="text-center py-3">Status Validasi</th>
                            
                            {{-- 🔒 KOLOM AKSI HANYA TAMPIL UNTUK ADMIN & OPERATOR --}}
                            @if(in_array(auth()->user()->role, ['admin', 'operator']))
                                <th width="15%" class="text-center py-3">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $key => $c)
                        <tr class="border-bottom border-light">
                            <td class="text-center fw-bold text-secondary">{{ $key + 1 }}</td>
                            <td><span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1">{{ $c->kode_mk }}</span></td>
                            <td class="fw-bold text-dark">{{ $c->nama_mk }}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border px-3 py-1 fw-bold">{{ $c->sks }} SKS</span></td>
                            <td class="text-center fw-semibold text-secondary">Semester {{ $c->semester }}</td>
                            
                            <td class="text-center">
                                @if($c->status_validasi == 'ACC')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold border border-success border-opacity-25">
                                        <i class="bi bi-patch-check-fill me-1"></i> Terpilih (ACC)
                                    </span>
                                @elseif($c->status_validasi == 'Ditolak')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold border border-danger border-opacity-25" data-bs-toggle="tooltip" title="Alasan: {{ $c->catatan_tolak ?? '-' }}">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold border border-warning border-opacity-25">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Kaprodi
                                    </span>
                                @endif
                            </td>

                            {{-- 🔒 TOMBOL AKSI HANYA TAMPIL UNTUK ADMIN & OPERATOR --}}
                            @if(in_array(auth()->user()->role, ['admin', 'operator']))
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-warning rounded-3 fw-bold px-2.5" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $c->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <form action="{{ route('courses.destroy', $c->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-3 fw-bold px-2.5" onclick="return confirm('Hapus matakuliah ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>

                        {{-- MODAL EDIT JUGA DIBUNGKUS AGAR TIDAK DIRENDER UNTUK ROLE LAIN --}}
                        @if(in_array(auth()->user()->role, ['admin', 'operator']))
                        <div class="modal fade" id="modalEdit{{ $c->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow rounded-4">
                                    <form action="{{ route('courses.update', $c->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-warning text-white rounded-top-4">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Matakuliah</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 text-start">
                                            <div class="mb-3">
                                                <label class="fw-bold small text-secondary mb-1">Kode Matakuliah</label>
                                                <input type="text" name="kode_mk" class="form-control rounded-3" value="{{ $c->kode_mk }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold small text-secondary mb-1">Nama Matakuliah</label>
                                                <input type="text" name="nama_mk" class="form-control rounded-3" value="{{ $c->nama_mk }}" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="fw-bold small text-secondary mb-1">Jumlah SKS</label>
                                                    <input type="number" name="sks" class="form-control rounded-3" value="{{ $c->sks }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="fw-bold small text-secondary mb-1">Semester</label>
                                                    <input type="number" name="semester" class="form-control rounded-3" value="{{ $c->semester }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                                            <button type="button" class="btn btn-secondary rounded-3 fw-semibold px-3" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning text-white rounded-3 fw-bold px-3">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 🔒 MODAL TAMBAH HANYA DITERAPKAN UNTUK ADMIN & OPERATOR --}}
@if(in_array(auth()->user()->role, ['admin', 'operator']))
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Matakuliah Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="fw-bold small text-secondary mb-1">Kode Matakuliah</label>
                        <input type="text" name="kode_mk" class="form-control rounded-3" placeholder="Contoh: INF4201" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold small text-secondary mb-1">Nama Matakuliah</label>
                        <input type="text" name="nama_mk" class="form-control rounded-3" placeholder="Contoh: Pemrograman Web 2" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-secondary mb-1">SKS</label>
                            <input type="number" name="sks" class="form-control rounded-3" placeholder="3" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="fw-bold small text-secondary mb-1">Semester</label>
                            <input type="number" name="semester" class="form-control rounded-3" placeholder="4" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3 rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3 fw-semibold px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold px-3">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection