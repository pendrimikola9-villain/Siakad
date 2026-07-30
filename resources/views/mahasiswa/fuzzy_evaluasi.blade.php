@extends('layouts.app')

@section('content')
<div class="container-fluid px-0 animate__animated animate__fadeIn">
    
    <!-- BANNER UTAMA HASIL REKOMENDASI -->
    <div class="card bg-primary text-white border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="badge bg-white text-primary px-3 py-2 fw-bold mb-2 rounded-pill">
                        <i class="bi bi-cpu-fill me-1"></i> Rekomendasi Pengambilan SKS
                    </span>
                    <h3 class="fw-bold mb-1">Modul Evaluasi Beban Belajar Mahasiswa</h3>
                    <p class="mb-0 text-white-50">Sistem Perhitungan Otomatis Batas SKS Berdasarkan Performa Perkuliahannya</p>
                </div>
                <div class="text-end d-none d-md-block">
                    <span class="fs-1 fw-bold text-warning d-block">{{ $jatahSksMaksimal }} SKS</span>
                    <small class="text-white-50">Batas Kuota SKS Semester Depan</small>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW CARD INDIKATOR INPUT PERFORMA MAHASISWA -->
    <div class="row g-3 mb-4">
        <!-- INPUT 1: KEHADIRAN -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3 me-3">
                            <i class="bi bi-calendar-check fs-3"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block font-monospace">Indikator 1</small>
                            <h6 class="fw-bold mb-0">Presensi Kehadiran</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <span class="fs-2 fw-bold text-dark">{{ $persenHadir }}%</span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 rounded-pill px-3 py-1">
                            Status: Rajin
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT 2: NILAI TUGAS -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3 me-3">
                            <i class="bi bi-journal-check fs-3"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block font-monospace">Indikator 2</small>
                            <h6 class="fw-bold mb-0">Rata-Rata Nilai Tugas</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <span class="fs-2 fw-bold text-dark">{{ $nilaiTugas }}</span>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 rounded-pill px-3 py-1">
                            Kategori: Tinggi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- INPUT 3: KEAKTIFAN DISKUSI -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                            <i class="bi bi-chat-square-dots fs-3"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block font-monospace">Indikator 3</small>
                            <h6 class="fw-bold mb-0">Keaktifan Forum Diskusi</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between">
                        <span class="fs-2 fw-bold text-dark">{{ $keaktifan }} Poin</span>
                        <span class="badge bg-warning text-dark bg-opacity-10 border border-warning border-opacity-20 rounded-pill px-3 py-1">
                            Kategori: Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MATRIKS ATURAN & HASIL REKOMENDASI -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-diagram-3 text-primary me-2"></i> Rincian Syarat Aturan Evaluasi
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">ID Aturan</th>
                                    <th>Kombinasi Syarat Performa (IF)</th>
                                    <th>Keputusan Kuota (THEN)</th>
                                    <th class="text-end pe-4">Kesesuaian (Fuzzy)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-success table-opacity-10">
                                    <td class="ps-4 fw-bold">R1</td>
                                    <td>IF Kehadiran <strong>Rajin</strong> AND Tugas <strong>Tinggi</strong> AND Keaktifan <strong>Aktif</strong></td>
                                    <td><span class="badge bg-success">24 SKS (Maksimal)</span></td>
                                    <td class="text-end pe-4 fw-bold text-success">1.0 (Terseksi/Aktif)</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">R2</td>
                                    <td>IF Kehadiran <strong>Rajin</strong> AND Tugas <strong>Sedang</strong> AND Keaktifan <strong>Cukup</strong></td>
                                    <td><span class="badge bg-primary">21 SKS (Normal)</span></td>
                                    <td class="text-end pe-4 text-muted">0.0</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">R3</td>
                                    <td>IF Kehadiran <strong>Standar</strong> AND Tugas <strong>Tinggi</strong> AND Keaktifan <strong>Aktif</strong></td>
                                    <td><span class="badge bg-primary">21 SKS (Normal)</span></td>
                                    <td class="text-end pe-4 text-muted">0.0</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">R4</td>
                                    <td>IF Kehadiran <strong>Jarang</strong> AND Tugas <strong>Rendah</strong> AND Keaktifan <strong>Pasif</strong></td>
                                    <td><span class="badge bg-danger">12 SKS (Minimal)</span></td>
                                    <td class="text-end pe-4 text-muted">0.0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white p-4 h-100">
                <h6 class="fw-bold text-warning mb-3">
                    <i class="bi bi-check-circle-fill me-2"></i> Hasil Rekomendasi SKS
                </h6>
                <div class="mb-3">
                    <small class="text-white-50 d-block">Kategori Status Performa:</small>
                    <span class="fs-5 fw-bold text-info">{{ $kategori }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-white-50 d-block">Kuota SKS Yang Dapat Diambil:</small>
                    <span class="display-5 fw-bold text-warning">{{ $jatahSksMaksimal }} SKS</span>
                </div>
                <hr class="border-secondary">
                <p class="small text-white-50 mb-0">
                    <i class="bi bi-info-circle me-1"></i> Perhitungan kuota ini dilakukan secara otomatis berdasarkan pencapaian tugas, keaktifan, dan kehadiran kamu selama semester berjalan.
                </p>
            </div>
        </div>
    </div>

    <!-- TABEL RIWAYAT LOG PERSISTENSI DATABASE (TABEL fuzzy_results) -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-database-check text-success me-2"></i> Riwayat & Log Perhitungan Sistem (`fuzzy_results`)
            </h6>
            <span class="badge bg-light text-dark border">Pembaruan Otomatis</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="bg-light text-muted small">
                        <tr>
                            <th class="ps-4">Mahasiswa</th>
                            <th>Kehadiran (%)</th>
                            <th>Nilai Tugas</th>
                            <th>Keaktifan</th>
                            <th>Hasil SKS Rekomendasi</th>
                            <th>Kategori Performa</th>
                            <th class="text-end pe-4">Waktu Evaluasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatFuzzy as $rf)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $rf->name }}</td>
                                <td>{{ $rf->kehadiran_input }}%</td>
                                <td>{{ $rf->tugas_input }}</td>
                                <td>{{ $rf->keaktifan_input }} Poin</td>
                                <td><span class="badge bg-warning text-dark fw-bold">{{ $rf->hasil_sks_crisp }} SKS</span></td>
                                <td><span class="badge bg-info text-dark">{{ $rf->kategori_rekomendasi }}</span></td>
                                <td class="text-end pe-4 text-muted small">{{ $rf->updated_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat log tersimpan di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection