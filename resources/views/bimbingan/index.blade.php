@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-chat-square-text me-2"></i>Sistem Informasi Bimbingan & Konsultasi (SIBIMBING)</h3>
                    <p class="mb-0 opacity-75">Manajemen logbook bimbingan, verifikasi draf akademik (Skripsi/PKL/Magang), serta persetujuan janji temu mahasiswa.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <!-- 🟢 Perbaikan Toleransi Role Huruf Kecil/Besar -->
                    @if(Auth::check() && strtolower(Auth::user()->role) === 'mahasiswa')
                        <a href="{{ route('bimbingan.create') }}" class="btn bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Ajukan Konsultasi
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3">
            <i class="bi bi-check-circle-fill text-success me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-text text-primary me-2"></i>Daftar Logbook & Request Janji Temu</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary border-bottom">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th class="py-3 px-3">Identitas Mahasiswa</th>
                            <th class="py-3">Kategori & Topik</th>
                            <th class="text-center py-3">Janji Temu Hari Ini?</th>
                            <th class="py-3">Status Validasi</th>
                            <th width="15%" class="text-center py-3">Aksi Pemrosesan</th>
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($logs as $index => $log)
                            <tr class="border-bottom border-light">
                                <td class="fw-bold text-center text-secondary py-3">{{ $index + 1 }}</td>
                                <td class="py-3 px-3">
                                    <span class="d-block fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $log->nama_mahasiswa }}</span>
                                    <small class="text-muted font-monospace" style="font-size: 0.75rem;">NIM: {{ $log->nim ?? 'N/A' }}</small>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 px-2 py-1 rounded-2 small mb-1 fw-bold">
                                        {{ $log->jenis_konsultasi }}
                                    </span>
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ $log->topik_bimbingan }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar-event me-1"></i>{{ date('d M Y', strtotime($log->tanggal_bimbingan)) }}
                                    </small>
                                </td>
                                
                                <td class="text-center py-3">
                                    @if($log->request_pertemuan === 'Ya')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold animate__animated animate__pulse animate__infinite">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Butuh Pertemuan Hari Ini
                                        </span>
                                    @else
                                        <span class="text-muted small"><i class="bi bi-file-earmark-text me-1"></i>Hanya Logbook</span>
                                    @endif
                                </td>

                                <td class="py-3">
                                    @if($log->status_bimbingan === 'ACC')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-20 rounded-pill fw-bold">
                                            <i class="bi bi-check-circle-fill me-1"></i> Disetujui (ACC)
                                        </span>
                                    @elseif($log->status_bimbingan === 'Ditolak')
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-20 rounded-pill fw-bold" data-bs-toggle="tooltip" title="Alasan: {{ $log->alasan_penolakan }}">
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        </span>
                                        <small class="d-block text-muted text-truncate mt-1" style="max-width: 150px;">{{ $log->alasan_penolakan }}</small>
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary border-opacity-20 rounded-pill fw-bold">
                                            <i class="bi bi-hourglass-split me-1"></i> Menunggu Validasi
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- 🟢 Perbaikan Pengecekan Akses Global Menggunakan strtolower -->
                                        @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['dosen', 'kaprodi']) && $log->status_bimbingan === 'Menunggu Validasi')
                                            
                                            <!-- Form untuk Tombol ACC -->
                                            <form action="{{ route('bimbingan.status', [$log->id, 'ACC']) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-2 fw-bold px-2 py-1 text-white shadow-sm" title="Setujui & ACC">
                                                    <i class="bi bi-check-lg"></i> ACC
                                                </button>
                                            </form>
                                            
                                            <!-- Tombol Pemicu Modal Tolak -->
                                            <button type="button" class="btn btn-sm btn-danger rounded-2 fw-bold px-2 py-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $log->id }}" title="Tolak Pengajuan">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                        @else
                                            <span class="text-muted small"><i class="bi bi-lock-fill me-1"></i>Selesai / Terkunci</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- MODAL ALASAN PENOLAKAN -->
                            <div class="modal fade" id="modalTolak{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header bg-light border-bottom p-3 px-4">
                                            <h5 class="modal-title fw-bold text-danger"><i class="bi bi-chat-left-x-fill me-2"></i>Berikan Alasan Penolakan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- 🟢 Form Aksi Arahkan ke Rute Proses Update Status -->
                                        <form action="{{ route('bimbingan.status', [$log->id, 'Ditolak']) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <p class="text-secondary small mb-3">Tolak bimbingan dari mahasiswa: <strong class="text-dark">{{ $log->nama_mahasiswa }}</strong> pada topik <strong class="text-dark">"{{ $log->topik_bimbingan }}"</strong>.</p>
                                                
                                                <div class="mb-1">
                                                    <label class="form-label fw-bold text-secondary">Alasan Penolakan <span class="text-danger">*</span></label>
                                                    <textarea name="alasan_penolakan" class="form-control rounded-3" rows="4" placeholder="Tulis alasan penolakan di sini..." required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0 p-3 px-4">
                                                <button type="button" class="btn btn-secondary rounded-3 px-3 py-2 small fw-bold" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-3 px-3 py-2 small fw-bold shadow-sm">Kirim Penolakan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-chat-left-x fs-1 text-black-50 d-block mb-3"></i>
                                        <span class="fw-semibold d-block text-secondary">Belum ada riwayat pendaftaran bimbingan atau logbook konsultasi.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-light border-0 p-3 px-4">
            <a href="/" class="btn btn-secondary px-3 py-2 rounded-3 fw-bold small shadow-sm">
                <i class="bi bi-arrow-left-short fs-5 align-middle me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection