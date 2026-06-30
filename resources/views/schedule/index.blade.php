@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-calendar3 me-2"></i>Jadwal Kuliah (SIPLAR)</h3>
                    <p class="mb-0 opacity-75">Manajemen plot jadwal kelas, waktu perkuliahan reguler, serta lokasi ruangan kelas.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                        <i class="bi bi-clock me-1"></i> Zona Waktu WITA
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Daftar Kelas & Informasi Ruangan</h5>
            
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('jadwal.create') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
                </a>
            @endif
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary border-bottom">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th class="py-3 px-3">Waktu & Kelas</th>
                            <th class="py-3">Mata Kuliah</th>
                            <th class="py-3">Dosen Pengampu</th>
                            <th class="py-3">Ruangan / Lab</th>
                            <th class="text-center py-3" style="width: 220px;">Status Dosen</th>
                            
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <th width="12%" class="text-center py-3">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($schedules as $index => $jadwal)
                            <tr class="border-bottom border-light">
                                <td class="fw-bold text-center text-secondary py-3">{{ $index + 1 }}</td>
                                <td class="py-3 px-3">
                                    <span class="badge bg-primary px-2 py-1 rounded-2 mb-1">{{ $jadwal->hari }}</span><br>
                                    <small class="fw-bold text-dark d-block mb-0.5">
                                        <i class="bi bi-clock me-1 text-muted"></i>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WITA
                                    </small>
                                    <small class="text-muted">Kelas: <strong class="text-secondary">{{ $jadwal->kelas }}</strong></small>
                                </td>
                                <td class="py-3">
                                    <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $jadwal->nama_mk }}</div>
                                    <span class="font-monospace text-muted small" style="font-size: 0.75rem;">
                                        Kode: {{ $jadwal->kode_mk }} | 
                                        <span class="badge bg-light text-dark border ms-1">{{ $jadwal->sks }} SKS</span>
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">
                                        <i class="bi bi-person-badge text-secondary me-1"></i>{{ $jadwal->nama_dosen }}
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold">
                                        <i class="bi bi-door-open me-1"></i>{{ $jadwal->nama_ruangan }}
                                    </span>
                                </td>

                                <td class="text-center py-3">
                                    @if(($jadwal->status_dosen ?? 'Berhadir') === 'Berhadir')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-20 rounded-pill fw-bold">
                                            <i class="bi bi-person-check-fill me-1"></i> Berhadir
                                        </span>
                                    @elseif(($jadwal->status_dosen ?? '') === 'Kelas Online')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-20 rounded-pill fw-bold">
                                            <i class="bi bi-laptop me-1"></i> Kelas Online
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-20 rounded-pill fw-bold">
                                            <i class="bi bi-person-x-fill me-1"></i> {{ $jadwal->status_dosen ?? 'Berhalangan' }}
                                        </span>
                                    @endif

                                    @if(Auth::check() && (Auth::user()->role === 'dosen' || Auth::user()->role === 'kaprodi'))
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 ms-2 fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $jadwal->id }}">
                                            <i class="bi bi-pencil-square"></i> Ubah
                                        </button>
                                    @endif
                                </td>

                                @if(Auth::check() && Auth::user()->role === 'admin')
                                    <td class="text-center py-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning text-white shadow-sm rounded-2">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger shadow-sm rounded-2" onclick="return confirm('Yakin ingin menghapus jadwal kuliah ini?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>

                            @if(Auth::check() && (Auth::user()->role === 'dosen' || Auth::user()->role === 'kaprodi'))
                                <div class="modal fade" id="updateStatusModal{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header bg-light border-bottom p-3 px-4">
                                                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-bell-fill text-warning me-2"></i>Update Kehadiran Kelas</h5>
                                                <button type="button" class="btn-close" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="#" method="POST">
                                                @csrf
                                                <div class="modal-body p-4">
                                                    <p class="text-secondary small mb-3">Mata Kuliah: <strong class="text-dark">{{ $jadwal->nama_mk }} (Kelas {{ $jadwal->kelas }})</strong></p>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-secondary">Pilih Status Hari Ini:</label>
                                                        <select name="status_dosen" class="form-select rounded-3">
                                                            <option value="Berhadir" {{ ($jadwal->status_dosen ?? '') == 'Berhadir' ? 'selected' : '' }}>🟢 Berhadir di Ruangan</option>
                                                            <option value="Kelas Online" {{ ($jadwal->status_dosen ?? '') == 'Kelas Online' ? 'selected' : '' }}>🟡 Dialihkan ke Kelas Online</option>
                                                            <option value="Berhalangan (Sakit)" {{ ($jadwal->status_dosen ?? '') == 'Berhalangan (Sakit)' ? 'selected' : '' }}>🔴 Berhalangan: Sakit</option>
                                                            <option value="Berhalangan (Dinas Luar)" {{ ($jadwal->status_dosen ?? '') == 'Berhalangan (Dinas Luar)' ? 'selected' : '' }}>🔴 Berhalangan: Ada Tugas Dinas</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-1">
                                                        <label class="form-label fw-bold text-secondary">Pesan / Instruksi Tambahan (Opsional):</label>
                                                        <textarea name="keterangan_status" class="form-control rounded-3" rows="3" placeholder="Contoh: Tugas sudah diupload ke menu Bahan & Tugas, silahkan dikerjakan ya..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0 p-3 px-4">
                                                    <button type="button" class="btn btn-secondary rounded-3 px-3 py-2 small fw-bold" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-3 px-3 py-2 small fw-bold shadow-sm">Kirim & Kirim Notifikasi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @empty
                            <tr>
                                <td colspan="{{ Auth::check() && Auth::user()->role === 'admin' ? '7' : '6' }}" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-calendar-x fs-1 text-black-50 d-block mb-3"></i>
                                        <span class="fw-semibold d-block text-secondary">Belum ada data jadwal kuliah reguler.</span>
                                        @if(Auth::check() && Auth::user()->role === 'admin')
                                            <small class="text-muted">Silahkan klik tombol "Tambah Jadwal" untuk mengisi plot kelas baru.</small>
                                        @else
                                            <small class="text-muted">Hubungi bagian Administrasi Akademik jika jadwal Anda kosong.</small>
                                        @endif
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