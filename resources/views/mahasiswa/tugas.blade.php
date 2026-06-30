@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
    <div class="card-body p-4 position-relative">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-1"><i class="bi bi-journal-arrow-up me-2"></i>Bahan & Tugas</h3>
                <p class="mb-0 opacity-75">Unduh materi perkuliahan dan kumpulkan file tugas Anda tepat waktu.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                    <i class="bi bi-clock-history me-1"></i> Cek Batas Waktu
                </span>
            </div>
        </div>
    </div>
</div>

    <div class="row g-4 animate__animated animate__fadeInUp">
        @forelse($daftarTugas as $dt)
        @php
            $nama_matkul = \Illuminate\Support\Facades\DB::table('courses')->where('id', $dt->course_id)->value('nama_mk') ?? 'Pemrograman Web 2';
        @endphp
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start border-success border-4">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 small">{{ $nama_matkul }}</span>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i> Deadline: {{ date('d M Y, H:i', strtotime($dt->deadline)) }} WITA</small>
                        </div>
                        <h5 class="fw-bold text-dark mt-2 mb-1">{{ $dt->judul_tugas }}</h5>
                        <p class="text-secondary small mt-2">{{ $dt->deskripsi }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-secondary btn-sm w-100" onclick="alert('File materi ({{ $dt->file_materi }}) otomatis terunduh ke direktori komputer Anda.');">
                                    <i class="bi bi-download me-1"></i> Unduh Materi
                                </a>
                            </div>
                            <div class="col-6">
                                @if($dt->nilai_tugas)
                                    <div class="bg-light rounded p-1 text-center border">
                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Nilai Dosen:</small>
                                        <strong class="text-success fs-6">{{ $dt->nilai_tugas }} / 100</strong>
                                    </div>
                                @else
                                    <button class="btn btn-success btn-sm w-100" onclick="alert('Modul upload tugas multipart-form data berhasil dieksekusi.');">
                                        <i class="bi bi-upload me-1"></i> Kumpul Tugas
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 text-center bg-white">
                <p class="text-muted mb-0">Belum ada bahan perkuliahan atau tugas yang dibagikan oleh dosen.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection