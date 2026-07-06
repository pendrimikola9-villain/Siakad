@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-calendar-check me-2"></i>Presensi Kuliah</h3>
                    <p class="mb-0 opacity-75">Pantau persentase kehadiran Anda. Batas minimal kelayakan mengikuti UTS/UAS adalah <strong>80%</strong>.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                        <i class="bi bi-shield-exclamation me-1"></i> Batas Minimal: 80%
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 animate__animated animate__fadeInUp">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 bg-white">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">Mata Kuliah</th>
                                    <th class="text-center">Total Pertemuan</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center" style="width: 250px;">Persentase Kehadiran</th>
                                    <th class="text-center">Syarat UTS / UAS</th>
                                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'dosen' || Auth::user()->role === 'operator' || Auth::user()->role === 'kaprodi'))
                                        <th class="text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekapAbsen as $ra)
                                <tr>
                                    <td class="fw-semibold text-dark py-3 px-4">{{ $ra->nama_mk ?? 'Pemrograman Web 2' }}</td>
                                    <td class="text-center fw-bold text-secondary">{{ $ra->total_pertemuan ?? 0 }}x</td>
                                    <td class="text-center fw-bold text-success">{{ $ra->total_hadir ?? 0 }}x</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2 fw-bold" style="min-width: 45px;">{{ ($ra->persentase ?? 0) . '%' }}</span>
                                            <div class="progress w-100" style="height: 8px;">
                                                <div class="progress-bar {{ ($ra->persentase ?? 0) >= 80 ? 'bg-success' : 'bg-danger' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ ($ra->persentase ?? 0) . '%' }}" 
                                                     aria-valuenow="{{ $ra->persentase ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if(($ra->persentase ?? 0) >= 80)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-20">
                                                <i class="bi bi-check-circle-fill me-1"></i> Lolos (Siap Ujian)
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-20">
                                                <i class="bi bi-x-circle-fill me-1"></i> Cekal (&lt; 80%)
                                            </span>
                                        @endif
                                    </td>
                                    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'dosen' || Auth::user()->role === 'operator' || Auth::user()->role === 'kaprodi'))
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-primary rounded-3 fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalInputAbsen{{ $ra->course_id }}">
                                                <i class="bi bi-pencil-square me-1"></i> Kelola Absen
                                            </button>
                                        </td>
                                    @endif
                                </tr>

                                @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'dosen' || Auth::user()->role === 'operator' || Auth::user()->role === 'kaprodi'))
                                <div class="modal fade" id="modalInputAbsen{{ $ra->course_id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content border-0 shadow rounded-4">
                                            <div class="modal-header bg-light border-bottom py-3 px-4">
                                                <h5 class="modal-title fw-bold text-dark">
                                                    <i class="bi bi-person-check-fill text-primary me-2"></i> Lembar Absen: {{ $ra->nama_mk }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('presensi.storeMassal') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="course_id" value="{{ $ra->course_id }}">
                                                
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-secondary">Tanggal Perkuliahan</label>
                                                        <input type="date" name="tanggal" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold text-secondary">Angkatan</label>
                                                            <select class="form-select rounded-3 filter-angkatan" data-course="{{ $ra->course_id }}" name="angkatan" required>
                                                                <option value="">-- Pilih Angkatan --</option>
                                                                @foreach($daftarAngkatan ?? ['2023', '2024', '2025'] as $ang)
                                                                    <option value="{{ $ang }}">{{ $ang }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold text-secondary">Semester</label>
                                                            <select class="form-select rounded-3 filter-semester" data-course="{{ $ra->course_id }}" name="semester" required>
                                                                <option value="">-- Pilih Semester --</option>
                                                                @for($i = 1; $i <= 8; $i++)
                                                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <hr class="text-muted opacity-25">
                                                    <label class="form-label fw-bold text-dark d-block mb-2"><i class="bi bi-people me-1 text-primary"></i> Daftar Mahasiswa Kelas:</label>

                                                    <div class="attendance-list border rounded-3 p-2 bg-light bg-opacity-25" id="wadahMahasiswa{{ $ra->course_id }}" style="max-height: 250px; overflow-y: auto;">
                                                        <div class="text-center text-muted p-4 small">
                                                            <i class="bi bi-funnel d-block fs-3 mb-2 text-black-50"></i>
                                                            Silahkan pilih Angkatan dan Semester terlebih dahulu untuk menyaring daftar mahasiswa.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0 py-3 px-4">
                                                    <button type="button" class="btn btn-secondary rounded-3 fw-bold px-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-3 fw-bold shadow-sm px-4">Simpan Presensi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-exclamation-circle me-1"></i> Belum ada data riwayat absensi berjalan pada database Anda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.filter-angkatan, .filter-semester').on('change', function() {
        let courseId = $(this).data('course');
        let angkatan = $('#modalInputAbsen' + courseId + ' .filter-angkatan').val();
        let semester = $('#modalInputAbsen' + courseId + ' .filter-semester').val();
        let wadah = $('#wadahMahasiswa' + courseId);

        if(angkatan && semester) {
            wadah.html('<div class="text-center p-4 small text-secondary"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Mencari mahasiswa aktif...</div>');

            $.ajax({
                url: "{{ route('presensi.getMahasiswa') }}",
                type: "GET",
                data: {
                    angkatan: angkatan,
                    semester: semester,
                    course_id: courseId
                },
                success: function(response) {
                    let html = '';
                    if(response.mahasiswa.length > 0) {
                        response.mahasiswa.forEach(function(mhs) {
                            html += `
                            <div class="d-flex align-items-center justify-content-between p-2 border-bottom border-light bg-white mb-1 rounded-3 shadow-sm">
                                <div class="fw-semibold text-dark small">${mhs.name} <br><small class="text-muted font-monospace">${mhs.npm ?? 'NPM N/A'}</small></div>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="H${response.course_id}_${mhs.id}" value="Hadir" checked>
                                    <label class="btn btn-outline-success btn-xs px-2 py-1 small" for="H${response.course_id}_${mhs.id}">H</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="S${response.course_id}_${mhs.id}" value="Sakit">
                                    <label class="btn btn-outline-primary btn-xs px-2 py-1 small" for="S${response.course_id}_${mhs.id}">S</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="I${response.course_id}_${mhs.id}" value="Izin">
                                    <label class="btn btn-outline-warning btn-xs px-2 py-1 small" for="I${response.course_id}_${mhs.id}">I</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="A${response.course_id}_${mhs.id}" value="Alfa">
                                    <label class="btn btn-outline-danger btn-xs px-2 py-1 small" for="A${response.course_id}_${mhs.id}">A</label>
                                </div>
                            </div>`;
                        });
                    } else {
                        html = '<div class="text-center text-danger p-4 small"><i class="bi bi-x-circle d-block fs-4 mb-2"></i>Tidak ada mahasiswa aktif pada kriteria Angkatan & Semester ini.</div>';
                    }
                    wadah.html(html);
                }
            });
        }
    });
});
</script>
@endsection