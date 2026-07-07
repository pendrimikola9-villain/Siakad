@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-whitefw-bold">
                    <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Nilai Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('grades.update', $grade->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Mahasiswa</label>
                            <input type="text" class="form-control bg-light" value="{{ $grade->mahasiswa->nama ?? $grade->nama }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Kuliah</label>
                            <input type="text" class="form-control bg-light" value="{{ $grade->course->nama_mk ?? $grade->nama_mk }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Input Nilai Baru (0-100)</label>
                            <input type="number" name="nilai" class="form-control" value="{{ $grade->nilai }}" min="0" max="100" required autofocus>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('grades.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Perbarui Nilai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection