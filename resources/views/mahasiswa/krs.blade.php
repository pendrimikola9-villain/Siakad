@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    <!-- HEADER PORTAL -->
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-book-half me-2"></i>Portal Rencana Studi (KRS) Cerdas</h3>
                    <p class="mb-0 opacity-75">Integrasi Sistem Pembayaran Keuangan, Filter Paket Semester, dan Inferensi Logika Fuzzy UMB.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-3 shadow-sm fs-6">
                        <i class="bi bi-calendar3 me-1"></i> TA: 2025/2026 Genap
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                <div>
                    <strong class="text-success">Berhasil!</strong>
                    <div class="text-secondary small fw-semibold">{{ session('success') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- PANEL DAFTAR PENGAJUAN (UNTUK DOSEN / OPERATOR / ADMIN) -->
    @if(Auth::check() && in_array(strtolower(Auth::user()->role), ['dosen', 'kaprodi', 'operator', 'admin']))
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 animate__animated animate__fadeInUp">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Pengajuan KRS & Finansial Mahasiswa</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th class="py-3 px-4">Mahasiswa</th>
                                <th>Finansial SPP</th>
                                <th class="text-center">Total SKS</th>
                                <th class="text-center">Status KRS</th>
                                <th class="text-center">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftarPengajuanKrs ?? [] as $mhsKrs)
                            <tr class="border-bottom border-light">
                            <td class="py-3 px-4">
    <!-- 🟢 Kita munculkan ID Mahasiswa secara nyata untuk dibaca -->
    <div class="fw-bold text-dark mb-0">{{ $mhsKrs->name }} (ID: {{ $mhsKrs->id_mahasiswa }})</div>
    <small class="text-muted">Angkatan {{ $mhsKrs->angkatan ?? '2024' }} | Kelas {{ $mhsKrs->kelas ?? '41 TI' }}</small>
</td>
                                <td>
                                    @if(($mhsKrs->finansial_status ?? '') === 'Lunas')
                                        <button class="btn btn-sm btn-success fw-bold px-3 rounded-pill border-0" disabled>LUNAS</button>
                                    @elseif(($mhsKrs->finansial_status ?? '') === 'Menunggu Validasi')
                                        <button type="button" class="btn btn-sm btn-warning fw-bold px-3 rounded-pill border-0 animate__animated animate__pulse animate__infinite" data-bs-toggle="modal" data-bs-target="#modalValidasiBayar{{ $mhsKrs->id_mahasiswa }}">
                                            BUTUH VERIFIKASI <i class="bi bi-search ms-1"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-danger fw-bold px-3 rounded-pill border-0" data-bs-toggle="modal" data-bs-target="#modalValidasiBayar{{ $mhsKrs->id_mahasiswa }}">
                                            BELUM BAYAR <i class="bi bi-exclamation-circle ms-1"></i>
                                        </button>
                                    @endif
                                </td>
                                <td class="text-center fw-bold text-primary">{{ $mhsKrs->total_sks ?? 0 }} SKS</td>
                                <td class="text-center">
                                    @if(($mhsKrs->status_krs ?? 'Pending') === 'Disetujui')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-2.5 py-1.5 rounded-pill fw-bold">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-20 px-2.5 py-1.5 rounded-pill fw-bold">Pending</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-2">
                                        @if(($mhsKrs->status_krs ?? 'Pending') !== 'Disetujui' && ($mhsKrs->finansial_status ?? '') === 'Lunas')
                                            <form action="{{ route('krs.approve', $mhsKrs->id_mahasiswa) }}" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-3 px-3 fw-bold">Setujui KRS</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('mahasiswa.krs') }}?student_id={{ $mhsKrs->id_mahasiswa }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 fw-bold"><i class="bi bi-eye"></i> Detail</a>
                                    </div>
                                </td>
                            </tr>

                            <!-- POP-UP MODAL VALIDASI FINANSIAL -->
                            <div class="modal fade" id="modalValidasiBayar{{ $mhsKrs->id_mahasiswa }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-4">
                                        <div class="modal-header border-bottom py-3">
                                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-cash-coin text-primary me-2"></i>Verifikasi Keuangan Mandiri</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center p-4">
                                            @if(($mhsKrs->finansial_status ?? '') === 'Menunggu Validasi')
                                                <p class="text-secondary small mb-3">Mahasiswa atas nama <strong>{{ $mhsKrs->name }}</strong> telah mengunggah berkas transfer bank berikut:</p>
                                                <!-- Simulasi Gambar Struk Transfer -->
                                                <div class="border rounded-3 p-3 bg-light mb-3">
                                                    <i class="bi bi-file-earmark-image text-muted fs-1 d-block mb-1"></i>
                                                    <span class="font-monospace text-dark d-block small fw-bold">{{ $mhsKrs->file_bukti }}</span>
                                                    <span class="badge bg-success text-white mt-2 small">✓ Valid File Format</span>
                                                </div>
                                                <div class="alert alert-info border-0 rounded-3 text-start small mb-0">
                                                    <i class="bi bi-info-circle-fill me-1"></i> Klik konfirmasi di bawah jika nominal transaksi dinyatakan sesuai dengan rekening koran UMB.
                                                </div>
                                            @else
                                                <i class="bi bi-slash-circle text-danger fs-1 mb-2 d-block"></i>
                                                <p class="text-secondary mb-0 fw-semibold">Mahasiswa bersangkutan belum mengunggah dokumen bukti transaksi perbankan apa pun ke portal akademik.</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer bg-light border-top p-3 rounded-bottom-4">
                                            <button type="button" class="btn btn-secondary fw-bold rounded-3" data-bs-dismiss="modal">Tutup</button>
                                            @if(($mhsKrs->finansial_status ?? '') === 'Menunggu Validasi')
                                                <a href="{{ route('mahasiswa.krs') }}?student_id={{ $mhsKrs->id_mahasiswa }}&aksi_bayar=luluskan_admin" class="btn btn-primary fw-bold rounded-3 px-4">
                                                    <i class="bi bi-check2-circle"></i> Konfirmasi Lunas
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">Belum ada mahasiswa yang mengajukan KRS.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- TAMPILAN LOCK & UPLOAD BUKTI (KHUSUS MAHASISWA) -->
    @if(Auth::check() && strtolower(Auth::user()->role) === 'mahasiswa' && ($statusBayar ?? 'Belum Bayar') !== 'Lunas')
        <div class="card border-0 shadow rounded-4 bg-white mb-4 animate__animated animate__pulse">
            <div class="card-body p-4 text-center">
                <i class="bi bi-lock-fill text-danger fs-1 mb-2 d-block"></i>
                <h5 class="fw-bold text-dark">Lembar Pengisian KRS Terkunci</h5>
                <p class="text-secondary small max-width-600 mx-auto">
                    Berdasarkan tata tertib administrasi keuangan UMB, Anda diwajibkan melakukan pembayaran SPP/UKT minimal semester berjalan untuk mengaktifkan hak pengisian KRS.
                </p>
                <div class="bg-light p-3 rounded-3 my-3 text-start d-inline-block border">
                    <small class="d-block text-muted fw-bold">Status Keuangan Saat Ini:</small>
                    @if(($statusBayar ?? 'Belum Bayar') === 'Belum Bayar')
                        <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Belum Mengunggah Bukti Pembayaran</span>
                    @else
                        <span class="text-warning fw-bold"><i class="bi bi-hourglass-split me-1"></i> Berkas Berhasil Terunggah (Menunggu Validasi Admin BAAK)</span>
                    @endif
                </div>
                <div class="d-flex justify-content-center gap-2">
                    @if(($statusBayar ?? 'Belum Bayar') === 'Belum Bayar')
                        <a href="{{ route('mahasiswa.krs') }}?aksi_bayar=upload" class="btn btn-primary px-4 py-2 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-1"></i> Unggah Bukti Pembayaran Bank
                        </a>
                    @else
                        <!-- 🟢 TOMBOL HAPUS BUKTI BAGI MAHASISWA -->
                        <a href="{{ route('mahasiswa.krs') }}?aksi_bayar=hapus_bukti" class="btn btn-outline-danger px-4 py-2 fw-bold rounded-3" onclick="return confirm('Apakah Anda yakin ingin menghapus dan membatalkan pengajuan bukti transaksi ini?')">
                            <i class="bi bi-trash3-fill me-1"></i> Hapus Bukti
                        </a>
                        <button class="btn btn-secondary px-4 py-2 fw-bold rounded-3" disabled>
                            <div class="spinner-border spinner-border-sm me-2"></div>Menunggu Validasi...
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- MONITORING INTEGRASI FUZZY & LEMBAR KRS -->
    @if(($statusBayar ?? 'Belum Bayar') === 'Lunas' || (Auth::check() && strtolower(Auth::user()->role) !== 'mahasiswa'))
    @if(Auth::check() && (strtolower(Auth::user()->role) === 'mahasiswa' || request()->has('student_id')))
        <div class="row g-3 mb-4 animate__animated animate__fadeIn">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-light rounded-3 text-primary me-3"><i class="bi bi-star-fill fs-4"></i></div>
                        <div>
                            <small class="text-muted d-block fw-semibold">IPK Kumulatif (Simulasi)</small>
                            <h4 class="fw-bold mb-0 text-dark">{{ number_format($ipk ?? 3.00, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-light rounded-3 text-success me-3"><i class="bi bi-person-badge-fill fs-4"></i></div>
                        <div>
                            <small class="text-muted d-block fw-semibold">Rata-Rata Kehadiran Kelas</small>
                            <h4 class="fw-bold mb-0 text-dark">{{ $persenHadir ?? 100 }}%</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-dark text-white p-3 h-100 shadow">
                    <div class="d-flex align-items-center">
                        <div class="p-3 bg-white bg-opacity-10 rounded-3 text-warning me-3"><i class="bi bi-cpu-fill fs-4"></i></div>
                        <div>
                            <small class="text-white-50 d-block fw-semibold">Rekomendasi Beban Fuzzy</small>
                            <h4 class="fw-bold mb-0 text-warning">{{ $jatahSksMaksimal ?? 18 }} SKS Max</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-body p-3 px-4 d-flex align-items-center justify-content-between row g-2">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-funnel-fill text-primary me-1"></i>Filter Paket Semester Kurikulum:</h6>
                </div>
                <div class="col-md-4 text-end">
                    <form action="{{ route('mahasiswa.krs') }}" method="GET" id="formFilterSemester" class="m-0">
                        @if(request()->has('student_id'))
                            <input type="hidden" name="student_id" value="{{ request('student_id') }}">
                        @endif
                        <select name="filter_semester" class="form-select fw-semibold text-dark border-2 rounded-3 shadow-none" onchange="document.getElementById('formFilterSemester').submit();">
                            <option value="">-- Tampilkan Semua Semester --</option>
                            <option value="1" {{ ($filterSemester ?? '') == '1' ? 'selected' : '' }}>Paket Semester 1</option>
                            <option value="2" {{ ($filterSemester ?? '') == '2' ? 'selected' : '' }}>Paket Semester 2</option>
                            <option value="3" {{ ($filterSemester ?? '') == '3' ? 'selected' : '' }}>Paket Semester 3</option>
                            <option value="4" {{ ($filterSemester ?? '') == '4' ? 'selected' : '' }}>Paket Semester 4</option>
                            <option value="5" {{ ($filterSemester ?? '') == '5' ? 'selected' : '' }}>Paket Semester 5</option>
                            <option value="6" {{ ($filterSemester ?? '') == '6' ? 'selected' : '' }}>Paket Semester 6</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <form action="{{ route('mahasiswa.krs.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ request('student_id', Auth::id()) }}">
            <input type="hidden" name="max_sks" value="{{ $jatahSksMaksimal ?? 18 }}">

            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden animate__animated animate__fadeInUp">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-list-check text-primary me-2"></i> Lembar Penentuan Kartu Rencana Studi
                    </h5>
                    <div class="text-end">
                        <span class="badge bg-light text-dark border p-2 fw-bold font-monospace">
                            SKS Dipilih: <span class="text-primary" id="sksTerpilihRealtime">{{ $totalSksDipilih ?? 0 }}</span> / {{ $jatahSksMaksimal ?? 18 }} SKS
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary border-bottom">
                                <tr>
                                    <th class="text-center py-3" style="width: 70px;">Ambil</th>
                                    <th class="py-3 px-4">Kode MK</th>
                                    <th class="py-3">Nama Mata Kuliah</th>
                                    <th class="text-center py-3">Beban SKS</th>
                                    <th class="text-center py-3">Rekomendasi Cerdas AI</th>
                                </tr>
                            </thead>
                            <tbody class="border-0">
                                @forelse($katalogMatkul as $item)
                                    <tr class="border-bottom border-light">
                                        <td class="text-center py-3">
                                            <div class="form-check d-flex justify-content-center p-0">
                                                <input type="checkbox" name="matkul[]" value="{{ $item->id }}" data-sks="{{ $item->sks }}" 
                                                       class="form-check-input checkbox-matkul border-secondary-subtle rounded-3 shadow-none" 
                                                       style="width: 1.35rem; height: 1.35rem; cursor: pointer;"
                                                       {{ in_array($item->id, $krsDiambil ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-monospace fw-bold text-primary">{{ $item->kode_mk }}</td>
                                        <td class="py-3">
                                            <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $item->nama_mk }}</div>
                                            <span class="text-muted" style="font-size: 0.75rem;">Fakultas Teknik / Informatika / Semester {{ $item->semester }}</span>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-3 py-2 rounded-pill fw-bold">
                                                {{ $item->sks }} SKS
                                            </span>
                                        </td>
                                        <td class="text-center py-3">
                                            <span class="badge {{ $item->rekomendasi_badge ?? 'bg-light text-secondary' }} px-3 py-2 rounded-3 text-uppercase" 
                                                  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $item->alasan ?? '' }}">
                                                <i class="bi bi-cpu-fill me-1"></i> {{ $item->rekomendasi_status ?? 'Reguler' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-folder-x fs-2 text-secondary d-block mb-2"></i> Tidak ada kelas ditawarkan untuk Paket Semester ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if(count($katalogMatkul) > 0)
                    <div class="card-footer bg-light border-0 d-flex justify-content-between align-items-center p-3 px-4">
                        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> Pilihan SKS divalidasi ketat oleh batasan Fuzzy SKS.</span>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow rounded-3">
                            <i class="bi bi-check-all me-1 fs-5 align-middle"></i> 
                            {{ Auth::user()?->role === 'mahasiswa' ? 'Simpan & Ajukan KRS' : 'Simpan Update KRS Mahasiswa' }}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    @endif
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    $(document).on('change', '.checkbox-matkul', function() {
        let totalSks = 0;
        $('.checkbox-matkul:checked').each(function() {
            totalSks += parseInt($(this).data('sks'));
        });
        $('#sksTerpilihRealtime').text(totalSks);

        let maxSks = parseInt("{{ $jatahSksMaksimal ?? 18 }}");
        if(totalSks > maxSks) {
            $('#sksTerpilihRealtime').removeClass('text-primary').addClass('text-danger fw-black animate__animated animate__shakeX');
        } else {
            $('#sksTerpilihRealtime').removeClass('text-danger').addClass('text-primary');
        }
    });
});
</script>
@endsection