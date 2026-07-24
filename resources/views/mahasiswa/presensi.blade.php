@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 animate__animated animate__fadeIn">
    
    <div class="card border-0 shadow-sm rounded-4 bg-gradient bg-primary text-white mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold mb-1"><i class="bi bi-calendar-check me-2"></i>Sistem Presensi Informasi Akademik</h3>
                    <p class="mb-0 opacity-75">Manajemen lembar kehadiran mahasiswa terintegrasi KRS mata kuliah.</p>
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

    <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-funnel text-primary me-2"></i>Pilih Kelas Pembelajaran</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary">Mata Kuliah Aktif:</label>
                    <select class="form-select rounded-3 p-2.5 border-2 border-light shadow-sm text-dark fw-semibold" id="selectMatkul">
                        <option value="">-- Pilih Mata Kuliah untuk Membuka Lembar Presensi --</option>
                        @foreach($rekapAbsen as $ra)
                            <option value="{{ $ra->course_id }}">{{ $ra->nama_mk }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Filter Angkatan:</label>
                    <select class="form-select rounded-3 p-2.5 border-2 border-light shadow-sm text-dark fw-semibold" id="selectAngkatan">
                        <option value="">Semua Angkatan</option>
                        @foreach($daftarAngkatan as $angkatan)
                            <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Tanggal Hari Ini:</label>
                    <input type="date" id="inputTanggal" class="form-control rounded-3 p-2.5 bg-light fw-semibold text-dark border-2 border-light" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden d-none mb-5" id="panelLembarAbsen">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-primary me-2"></i>Rekapitulasi & Lembar Presensi Kelas</h5>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold" id="badgeNamaMatkul"></span>
        </div>

        <form action="{{ route('presensi.storeMassal') }}" method="POST" class="m-0">
            @csrf
            <input type="hidden" name="course_id" id="hiddenCourseId">
            <input type="hidden" name="tanggal" id="hiddenTanggal">

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light border-bottom text-secondary small text-uppercase">
                            <tr>
                                <th class="text-center py-3" width="5%">No</th>
                                <th class="py-3" width="25%">Nama Mahasiswa</th>
                                <th class="py-3" width="15%">NPM / NIM</th>
                                <th class="text-center py-3" width="20%">Riwayat Kumulatif (H / S / I / A)</th>
                                <th class="text-center py-3" width="15%">Persentase (%)</th>
                                <th class="text-center py-3" width="20%">Input / Ubah Status Hari Ini</th>
                            </tr>
                        </thead>
                        <tbody id="wadahMahasiswaUtama" class="border-0 text-dark">
                            </tbody>
                    </table>
                </div>
            </div>

            @if(Auth::check() && (Auth::user()->role === 'dosen' || Auth::user()->role === 'kaprodi' || Auth::user()->role === 'operator' || Auth::user()->role === 'admin'))
            <div class="card-footer bg-light border-top py-3 px-4 text-end" id="footerSimpan">
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan & Perbarui Presensi
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log("AJAX Presensi Ready!");
    let roleUser = "{{ Auth::user()->role ?? 'mahasiswa' }}";

    // Memicu pencarian otomatis saat Matkul, Angkatan, atau Tanggal diubah
    $(document).on('change', '#selectMatkul, #selectAngkatan, #inputTanggal', function() {
        let courseId = $('#selectMatkul').val();
        let angkatan = $('#selectAngkatan').val();
        let tanggal = $('#inputTanggal').val();
        let textMatkul = $("#selectMatkul option:selected").text();
        
        $('#hiddenCourseId').val(courseId);
        $('#hiddenTanggal').val(tanggal);

        if (courseId && courseId !== "") {
            $('#panelLembarAbsen').removeClass('d-none');
            $('#badgeNamaMatkul').text(textMatkul);
            
            $('#wadahMahasiswaUtama').html('<tr><td colspan="6" class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat riwayat & progress bar kehadiran...</td></tr>');

            $.ajax({
                url: "{{ route('presensi.getMahasiswa') }}",
                type: "GET",
                data: { course_id: courseId, tanggal: tanggal, angkatan: angkatan },
                dataType: "json",
                success: function(response) {
                    let html = '';
                    if (response.mahasiswa && response.mahasiswa.length > 0) {
                        response.mahasiswa.forEach(function(mhs, index) {
                            
                            // Hitung warna bar persentase dinamis
                            let barColor = mhs.persentase >= 80 ? 'bg-success' : 'bg-danger';

                            html += `
                            <tr>
                                <td class="text-center fw-bold text-secondary py-3">${index + 1}</td>
                                <td class="fw-semibold">${mhs.name}</td>
                                <td class="font-monospace text-muted small">${mhs.npm}</td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 mx-0.5">${mhs.hadir}H</span>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 mx-0.5">${mhs.sakit}S</span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 mx-0.5">${mhs.izin}I</span>
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 mx-0.5">${mhs.alfa}A</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="me-2 fw-bold small">${mhs.persentase}%</span>
                                        <div class="progress" style="height: 6px; width: 70px;">
                                            <div class="progress-bar ${barColor}" style="width: ${mhs.persentase}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">`;

                            // Cek Hak Akses Menu Aksi
                            if (roleUser === 'dosen' || roleUser === 'kaprodi' || roleUser === 'operator' || roleUser === 'admin') {
                                html += `
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden" role="group">
                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="H_${mhs.id}" value="Hadir" ${mhs.status_hari_ini === 'Hadir' ? 'checked' : ''}>
                                    <label class="btn btn-outline-success btn-xs px-2.5 py-1 small" for="H_${mhs.id}">H</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="S_${mhs.id}" value="Sakit" ${mhs.status_hari_ini === 'Sakit' ? 'checked' : ''}>
                                    <label class="btn btn-outline-primary btn-xs px-2.5 py-1 small" for="S_${mhs.id}">S</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="I_${mhs.id}" value="Izin" ${mhs.status_hari_ini === 'Izin' ? 'checked' : ''}>
                                    <label class="btn btn-outline-warning btn-xs px-2.5 py-1 small" for="I_${mhs.id}">I</label>

                                    <input type="radio" class="btn-check" name="status[${mhs.id}]" id="A_${mhs.id}" value="Alfa" ${mhs.status_hari_ini === 'Alfa' ? 'checked' : ''}>
                                    <label class="btn btn-outline-danger btn-xs px-2.5 py-1 small" for="A_${mhs.id}">A</label>
                                </div>`;
                            } else {
    // TAMPILAN MAHASISWA: Tombol dikunci, hanya memunculkan Badge Status Kehadiran hari ini
    let badgeHariIni = 'bg-success';
    if(mhs.status_hari_ini === 'Sakit') badgeHariIni = 'bg-primary';
    if(mhs.status_hari_ini === 'Izin') badgeHariIni = 'bg-warning';
    if(mhs.status_hari_ini === 'Alfa') badgeHariIni = 'bg-danger';
    if(mhs.status_hari_ini === 'Belum Absen') badgeHariIni = 'bg-secondary text-white';

    html += `<span class="badge ${badgeHariIni} px-3 py-1.5 rounded-pill fw-bold text-uppercase shadow-sm small">${mhs.status_hari_ini}</span>`;
}

                            html += `</td></tr>`;
                        });
                        // Footer tombol simpan otomatis disembunyikan jika role-nya mahasiswa
if(roleUser === 'mahasiswa') {
    $('#footerSimpan').hide();
} else {
    $('#footerSimpan').show();
}
                    } else {
                        html = '<tr><td colspan="6" class="text-center py-5 text-danger fw-semibold"><i class="bi bi-person-x-fill fs-3 d-block mb-2 text-secondary"></i>Tidak ada data mahasiswa terdaftar pada filter kelas ini.</td></tr>';
                        $('#footerSimpan').hide();
                    }
                    $('#wadahMahasiswaUtama').html(html);
                },
                error: function() {
                    $('#wadahMahasiswaUtama').html('<tr><td colspan="6" class="text-center py-5 text-danger">Gagal memuat rekap data dari server.</td></tr>');
                }
            });
        } else {
            $('#panelLembarAbsen').addClass('d-none');
        }
    });
});
</script>
@endsection