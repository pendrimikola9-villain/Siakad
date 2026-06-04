@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Input Nilai Mahasiswa</h2>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNilai">
            <i class="bi bi-plus-square me-2"></i>Input Nilai Baru
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
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Matakuliah</th>
                        <th>SKS</th>
                        <th>Nilai</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $key => $g)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->nama_mk }}</td>
                        <td>{{ $g->sks }}</td>
                        <td>{{ $g->nilai }}</td>
                        <td>
                            @if($g->nilai >= 80) <span class="badge bg-success">A</span>
                            @elseif($g->nilai >= 70) <span class="badge bg-primary">B</span>
                            @elseif($g->nilai >= 60) <span class="badge bg-warning text-dark">C</span>
                            @else <span class="badge bg-danger">D</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Nilai -->
<div class="modal fade" id="modalNilai" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('grades.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Form Input Nilai</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-select" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswa as $m)
                                <option value="{{ $m->id }}">{{ $m->nim }} - {{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Pilih Matakuliah</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Pilih Matakuliah --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_mk }} ({{ $c->sks }} SKS)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Input Nilai (0-100)</label>
                        <input type="number" name="nilai" class="form-control" placeholder="Contoh: 85" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection