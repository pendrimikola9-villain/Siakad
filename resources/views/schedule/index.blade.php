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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Daftar Kelas & Informasi Ruangan</h5>
            
            @if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'))
                <button class="btn btn-primary px-3 py-2 rounded-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
                </button>
            @endif
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary border-bottom">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th class="py-3 px-3" width="20%">Waktu & Kelas</th>
                            <th class="py-3" width="25%">Mata Kuliah</th>
                            <th class="py-3" width="20%">Dosen Pengampu</th>
                            <th class="py-3" width="15%">Ruangan / Lab</th>
                            <th class="text-center py-3" width="20%">Status Dosen</th>
                            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'operator'))
                                <th width="15%" class="text-center py-3">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="border-0">
                        @forelse($schedules as $index => $jadwal)
                            <tr class="border-bottom border-light">
                                <td class="fw-bold text-center text-secondary py-3">{{ $index + 1 }}</td>
                                
                                <td class="py-3 px-3">
                                    <span class="badge bg-primary px-2.5 py-1.5 rounded-2 mb-2">{{ $jadwal->hari }}</span>
                                    <div class="fw-bold text-dark small mb-1">
                                        <i class="bi bi-clock me-1 text-primary"></i> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WITA
                                    </div>
                                    <div class="text-muted small">
                                        Kelas: <strong class="text-secondary">{{ $jadwal->kelas }}</strong>
                                    </div>
                                </td>
                                
                                <td class="py-3">
                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">{{ $jadwal->nama_mk }}</div>
                                    <span class="text-muted small">
                                        Kode: <span class="badge bg-light text-secondary border font-monospace">{{ $jadwal->kode_mk }}</span> | 
                                        <span class="badge bg-light text-dark border">{{ $jadwal->sks }} SKS</span>
                                    </span>
                                </td>
                                
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">
                                        <i class="bi bi-person-workspace text-muted me-1"></i> {{ $jadwal->nama_dosen }}
                                    </div>
                                </td>
                                
                                <td class="py-3">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-20 px-2.5 py-1.5 rounded-3 fw-bold">
                                        <i class="bi bi-door-open me-1"></i> {{ $jadwal->nama_ruangan }}
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
                                            <i class="bi bi-person-x-fill me-1"></i> {{ $jadwal->status_dosen }}
                                        </span>
                                    @endif

                                    @if(!empty($jadwal->keterangan_status))
                                        <div class="mt-2 text-start mx-auto p-2 bg-light rounded-3 border border-secondary border-opacity-10 text-secondary" style="font-size: 0.8rem; max-width: 200px;">
                                            <strong class="text-dark d-block mb-0.5"><i class="bi bi-info-circle-fill text-primary"></i> Catatan:</strong>
                                            {{ $jadwal->keterangan_status }}
                                        </div>
                                    @endif

                                    @if(Auth::check() && (Auth::user()->role === 'dosen' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin'))
                                        <button type="button" class="btn btn-sm btn-link text-primary p-0 d-block mx-auto mt-2 fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $jadwal->id }}">
                                            <i class="bi bi-pencil-square"></i> Ubah Status
                                        </button>
                                    @endif
                                </td>

            @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'operator'))
    <td class="text-center py-3">
        <div class="d-inline-flex align-items-center justify-content-center gap-2 w-100">
            
            <button type="button" class="btn btn-warning text-white shadow-sm d-flex align-items-center justify-content-center rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEditJadwal{{ $jadwal->id }}" style="width: 80px; height: 38px; font-size: 0.85rem;">
                <i class="bi bi-pencil me-1"></i> Edit
            </button>
            
            <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="m-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger shadow-sm d-flex align-items-center justify-content-center rounded-3 fw-bold" onclick="return confirm('Yakin ingin menghapus jadwal kuliah ini?')" style="width: 80px; height: 38px; font-size: 0.85rem;">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>

        </div>
    </td>
@endif
                            </tr>

                            <div class="modal fade" id="updateStatusModal{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header bg-light border-bottom p-3 px-4">
                                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-bell-fill text-warning me-2"></i>Update Kehadiran & Notifikasi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('jadwal.updateStatus', $jadwal->id) }}" method="POST">
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
                                                    <label class="form-label fw-bold text-secondary">Catatan / Instruksi Tugas untuk Mahasiswa:</label>
                                                    <textarea name="keterangan_status" class="form-control rounded-3" rows="3" placeholder="Tulis catatan di sini agar terkirim ke log notifikasi mahasiswa..." required>{{ $jadwal->keterangan_status ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0 p-3 px-4">
                                                <button type="button" class="btn btn-secondary rounded-3 px-3 py-2 small fw-bold" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-3 px-3 py-2 small fw-bold shadow-sm">Kirim & Siarkan Notifikasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                          @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'operator'))
                            <div class="modal fade" id="modalEditJadwal{{ $jadwal->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header bg-warning text-white p-3 px-4">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Plot Jadwal Kuliah</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('jadwal.update', $jadwal->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold text-secondary">Hari</label>
                                                        <select name="hari" class="form-select rounded-3" required>
                                                            <option value="Senin" {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
                                                            <option value="Selasa" {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                                            <option value="Rabu" {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                                            <option value="Kamis" {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                                            <option value="Jumat" {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                                            <option value="Sabtu" {{ $jadwal->hari == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold text-secondary">Kelas</label>
                                                        <input type="text" name="kelas" class="form-control rounded-3" value="{{ $jadwal->kelas }}" required>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold text-secondary">Jam Mulai</label>
                                                        <input type="time" name="jam_mulai" class="form-control rounded-3" value="{{ substr($jadwal->jam_mulai, 0, 5) }}" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold text-secondary">Jam Selesai</label>
                                                        <input type="time" name="jam_selesai" class="form-control rounded-3" value="{{ substr($jadwal->jam_selesai, 0, 5) }}" required>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-secondary">Mata Kuliah</label>
                                                    <select name="course_id" class="form-select rounded-3" required>
                                                        @foreach($courses as $c)
                                                            <option value="{{ $c->id }}" {{ $jadwal->course_id == $c->id ? 'selected' : '' }}>{{ $c->kode_mk }} - {{ $c->nama_mk }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-secondary">Dosen Pengampu</label>
                                                    <select name="lecturer_id" class="form-select rounded-3" required>
                                                        @foreach($lecturers as $l)
                                                            <option value="{{ $l->id }}" {{ $jadwal->lecturer_id == $l->id ? 'selected' : '' }}>{{ $l->nama_dosen ?? $l->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-secondary">Ruangan / Lab</label>
                                                    <select name="room_id" class="form-select rounded-3" required>
                                                        @foreach($rooms as $r)
                                                            <option value="{{ $r->id }}" {{ $jadwal->room_id == $r->id ? 'selected' : '' }}>{{ $r->nama_ruangan ?? $r->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0 p-3 px-4">
                                                <button type="button" class="btn btn-secondary rounded-3 px-3 py-2 fw-bold" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning text-white rounded-3 px-3 py-2 fw-bold shadow-sm">Update Jadwal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <i class="bi bi-calendar-x fs-1 text-black-50 d-block mb-3"></i>
                                        <span class="fw-semibold d-block text-secondary">Belum ada data jadwal kuliah reguler.</span>
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

@if(Auth::check() && (Auth::user()->role === 'kaprodi' || Auth::user()->role === 'admin' || Auth::user()->role === 'operator'))
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white p-3 px-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i>Form Plot Jadwal Kuliah</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('jadwal.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-secondary">Hari</label>
                            <select name="hari" class="form-select rounded-3" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-secondary">Kelas</label>
                            <input type="text" name="kelas" class="form-control rounded-3" placeholder="Contoh: 4A TI" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-secondary">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control rounded-3" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-secondary">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control rounded-3" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Mata Kuliah (Hanya Validasi ACC)</label>
                        <select name="course_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Matakuliah --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->kode_mk }} - {{ $c->nama_mk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Dosen Pengampu</label>
                        <select name="lecturer_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach($lecturers as $l)
                                <option value="{{ $l->id }}">{{ $l->nama_dosen ?? $l->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Ruangan / Laboratorium</label>
                        <select name="room_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->nama_ruangan ?? $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-3 py-2 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-3 py-2 fw-bold shadow-sm">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection