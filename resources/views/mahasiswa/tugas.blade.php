@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <!-- KARTU HEADER -->
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-journal-arrow-up me-2"></i>Bahan & Tugas Perkuliahan</h3>
                    <p class="mb-0 opacity-75">Unduh materi perkuliahan, buat tugas baru, atau kumpulkan file tugas Anda tepat waktu.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'operator', 'dosen', 'kaprodi']))
                        <button class="btn btn-light text-primary fw-bold px-3 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
                            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Bahan / Tugas
                        </button>
                    @else
                        <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                            <i class="bi bi-clock-history me-1"></i> Cek Batas Waktu
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3 animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill text-success me-2"></i>{{ session('success') }}
        </div>
    @endif

    <!-- GRID DAFTAR DATA -->
    <div class="row g-4 animate__animated animate__fadeInUp">
        @forelse($daftarTugas as $dt)
        @php
            $nama_matkul = \Illuminate\Support\Facades\DB::table('courses')->where('id', $dt->course_id)->value('nama_mk') ?? 'Mata Kuliah Bebas';
            // Mencari tahu tipe item, jika di database belum ada kolomnya, default ke 'Tugas' agar kodingan aman
            $kategori = $dt->kategori ?? 'Tugas'; 
        @endphp
        <div class="col-md-6">
            <!-- Warna border kiri dinamis: Biru untuk Materi, Hijau untuk Tugas -->
            <div class="card border-0 shadow-sm rounded-3 bg-white h-100 border-start {{ $kategori === 'Materi' ? 'border-primary' : 'border-success' }} border-4">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 small">{{ $nama_matkul }}</span>
                                <!-- Badge status penanda jenis Bahan Kuliah -->
                                <span class="badge {{ $kategori === 'Materi' ? 'bg-primary' : 'bg-success' }} px-2 py-1 small text-capitalize">{{ $kategori }}</span>
                            </div>
                            @if($kategori === 'Tugas')
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> Deadline: {{ isset($dt->deadline) ? date('d M Y, H:i', strtotime($dt->deadline)) : '-' }} WITA</small>
                            @else
                                <small class="text-muted"><i class="bi bi-eye me-1"></i> Materi Umum</small>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mt-2 mb-1">{{ $dt->judul_tugas ?? $dt->judul ?? 'Judul Tidak Tersedia' }}</h5>
                        <p class="text-secondary small mt-2">{{ $dt->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="row g-2">
                            <!-- Tombol Unduh Materi berlaku untuk kedua jenis kategori -->
                            <div class="col-{{ $kategori === 'Materi' && Auth::check() && strtolower(Auth::user()->role) === 'mahasiswa' ? '12' : '6' }}">
                                <a href="{{ asset('storage/' . ($dt->file_materi ?? $dt->file_path ?? '')) }}" class="btn btn-outline-secondary btn-sm w-100" download>
                                    <i class="bi bi-download me-1"></i> Unduh File
                                </a>
                            </div>
                            
                          <!-- 🟢 KODE BARU (Menampilkan Bukti untuk Mahasiswa & Tombol Rekap untuk Dosen/Kaprodi): -->
@if($kategori === 'Tugas')
<div class="col-6">
    @if(Auth::check() && strtolower(Auth::user()->role) === 'mahasiswa')
        {{-- Cek jika mahasiswa sudah punya record file jawaban di DB --}}
        @if(isset($dt->file_jawaban) && $dt->file_jawaban)
            <div class="bg-success bg-opacity-10 border border-success border-opacity-20 rounded p-1 text-center">
                <small class="text-success fw-bold d-block" style="font-size: 0.7rem;"><i class="bi bi-check-circle-fill me-1"></i> Terkirim</small>
                <a href="{{ asset($dt->file_jawaban) }}" target="_blank" class="text-success fw-bold small text-decoration-none" style="font-size: 0.75rem;">
                    <i class="bi bi-download me-1"></i> Bukti File
                </a>
            </div>
        @elseif(isset($dt->nilai_tugas) && $dt->nilai_tugas)
            <div class="bg-light rounded p-1 text-center border">
                <small class="text-muted d-block" style="font-size: 0.75rem;">Nilai Dosen:</small>
                <strong class="text-success fs-6">{{ $dt->nilai_tugas }} / 100</strong>
            </div>
        @else
            <button class="btn btn-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalKumpulTugas{{ $dt->id }}">
                <i class="bi bi-upload me-1"></i> Kumpul Tugas
            </button>
        @endif
    @else
        {{-- Tombol untuk Dosen / Kaprodi / Admin / Operator untuk melihat siapa yang sudah kumpul --}}
        <button class="btn btn-warning btn-sm w-100 text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalRekapTugas{{ $dt->id }}">
            <i class="bi bi-people-fill me-1"></i> Cek Pengumpul
        </button>
    @endif
</div>
@elseif($kategori === 'Materi' && Auth::check() && strtolower(Auth::user()->role) !== 'mahasiswa')
                            <div class="col-6">
                                <div class="bg-light rounded p-1 text-center border">
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Status Akses:</small>
                                    <strong class="text-primary small">Materi Dibagikan</strong>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL KUMPUL TUGAS (HANYA AKTIF JIKA TIPE TUGAS) -->
       <!-- 🟢 MODAL DAFTAR MAHASISWA YANG SUDAH KUMPUL TUGAS (KHUSUS DOSEN & KAPRODI) -->
        @if($kategori === 'Tugas' && Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'operator', 'dosen', 'kaprodi']))
        <div class="modal fade" id="modalRekapTugas{{ $dt->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-people-fill text-warning me-2"></i> Rekap Pengumpulan: {{ $dt->judul_tugas ?? $dt->judul }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        @php
                            // Mengambil daftar mahasiswa yang sudah kumpul dari tabel submissions
                            $jawabanMahasiswa = \Illuminate\Support\Facades\DB::table('submissions')
                                ->join('users', 'submissions.user_id', '=', 'users.id')
                                ->where('submissions.assignment_id', $dt->id)
                                ->select('submissions.*', 'users.name', 'users.email')
                                ->get();
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th>Nama Mahasiswa</th>
                                        <th>Waktu Kumpul</th>
                                        <th>Berkas Jawaban</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jawabanMahasiswa as $jm)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $jm->name }}</td>
                                            <td class="small text-muted">{{ date('d M Y, H:i', strtotime($jm->updated_at)) }} WITA</td>
                                            <td>
                                                <a href="{{ asset($jm->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill fw-bold">
                                                    <i class="bi bi-file-earmark-arrow-down me-1"></i> Periksa Jawaban
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success border border-success px-3 py-1">Telah Mengumpulkan</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox d-block fs-3 mb-1"></i>
                                                Belum ada mahasiswa yang mengumpulkan tugas ini.
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
        @endif

        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 text-center bg-white">
                <p class="text-muted mb-0">Belum ada bahan perkuliahan atau tugas yang dibagikan oleh dosen.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- MODAL TAMBAH BAHAN / TUGAS BARU (ADA PILIHAN KATEGORI) -->
@if(Auth::check() && in_array(strtolower(Auth::user()->role), ['admin', 'operator', 'dosen', 'kaprodi']))
<div class="modal fade" id="modalTambahTugas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-journal-plus me-2"></i>Buat Bahan & Tugas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Pilih Jenis Berkas</label>
                        <select name="kategori" class="form-select rounded-3" id="selectKategori" required>
                            <option value="Materi">Materi Kuliah Biasa (Hanya Unduh)</option>
                            <option value="Tugas">Tugas Kuliah (Butuh Pengumpulan & Nilai)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Pilih Mata Kuliah</label>
                        <select name="course_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @php
                                $courses = \Illuminate\Support\Facades\DB::table('courses')->orderBy('nama_mk', 'asc')->get();
                            @endphp
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->nama_mk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Judul Bahan / Tugas</label>
                        <input type="text" name="judul_tugas" class="form-control rounded-3" placeholder="Contoh: Slide Pertemuan 3 - Arsitektur MVC" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Deskripsi / Keterangan</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Tulis deskripsi ringkas materi atau instruksi tugas..." required></textarea>
                    </div>
                    <!-- Skenario input deadline dinamis via Javascript di bawah -->
                    <div class="mb-3" id="inputDeadlineGroup">
                        <label class="form-label fw-bold text-secondary">Batas Waktu Pengerjaan (Deadline)</label>
                        <input type="datetime-local" name="deadline" id="inputDeadline" class="form-control rounded-3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Upload Dokumen Berkas (.pdf, .zip, .rar)</label>
                        <input type="file" name="file_materi" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">Publish Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Membuat form deadline otomatis bersembunyi jika dosen memilih mengunggah 'Materi' saja
    document.addEventListener("DOMContentLoaded", function() {
        const selectKategori = document.getElementById('selectKategori');
        const deadlineGroup = document.getElementById('inputDeadlineGroup');
        const inputDeadline = document.getElementById('inputDeadline');

        function sesuaikanForm() {
            if(selectKategori.value === 'Materi') {
                deadlineGroup.style.display = 'none';
                inputDeadline.removeAttribute('required');
            } else {
                deadlineGroup.style.display = 'block';
                inputDeadline.setAttribute('required', 'required');
            }
        }
        selectKategori.addEventListener('change', sesuaikanForm);
        sesuaikanForm(); // Jalankan inisialisasi awal
    });
</script>
@endif
@endsection