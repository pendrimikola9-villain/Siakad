@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-dark"><i class="bi bi-check2-square text-warning me-2"></i>Validasi Kurikulum Mata Kuliah</h2>
            <p class="text-secondary">Persetujuan dan aktivasi mata kuliah yang akan ditawarkan pada Kartu Rencana Studi (KRS) mahasiswa semester ini.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-5">
        <div class="card-header bg-white p-3 border-bottom px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-book text-secondary me-2"></i>Daftar Matakuliah Program Studi</h5>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Semester Genap 2026</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th width="15%" class="py-3">Kode MK</th>
                            <th class="py-3">Nama Mata Kuliah</th>
                            <th width="10%" class="text-center py-3">SKS</th>
                            <th width="15%" class="text-center py-3">Semester</th>
                            <th width="15%" class="text-center py-3">Status Validasi</th>
                            <th width="15%" class="text-center py-3">Aksi</th>
                        </tr>
                    </thead>
              <tbody>
                        @forelse($courses as $index => $mk)
                            <tr class="border-bottom border-light" id="row-{{ $mk->id }}">
                                <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                                <td><span class="fw-bold text-primary">{{ $mk->kode_mk }}</span></td>
                                <td>
                                    <span class="fw-bold text-dark d-block mb-0">{{ $mk->nama_mk }}</span>
                                    <small class="text-muted">Kelompok: Teori / Praktikum</small>
                                </td>
                                <td class="text-center"><span class="badge bg-light text-dark border px-3 py-1 fw-bold">{{ $mk->sks }} SKS</span></td>
                                <td class="text-center fw-semibold text-secondary">Semester {{ $mk->semester }}</td>
                                
                                <td class="text-center" id="status-container-{{ $mk->id }}">
                                    @if($mk->status_validasi == 'ACC')
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold border border-success border-opacity-25">
                                            <i class="bi bi-patch-check-fill me-1"></i> Terpilih (ACC)
                                        </span>
                                    @elseif($mk->status_validasi == 'Ditolak')
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold border border-danger border-opacity-25" data-bs-toggle="tooltip" title="Alasan: {{ $mk->catatan_tolak ?? '-' }}">
                                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold border border-warning border-opacity-25">
                                            <i class="bi bi-hourglass-split me-1"></i> Pending (Butuh ACC)
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-3 fw-bold px-3 d-flex align-items-center gap-1" onclick="prosesValidasi({{ $mk->id }}, 'ACC')">
                                            <i class="bi bi-check-circle"></i> ACC
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 fw-bold px-3 d-flex align-items-center gap-1" onclick="bukaModalTolak({{ $mk->id }}, '{{ $mk->nama_mk }}')">
                                            <i class="bi bi-x-circle"></i> Tolak
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data mata kuliah master yang di-input Admin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTolakKurikulum" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-danger text-white rounded-top-4">
                <h5 class="modal-title" id="modalTolakLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i> Tolak Pengajuan Mata Kuliah</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">Anda akan menolak mata kuliah: <strong id="nama-mk-modal" class="text-danger"></strong></p>
                <div class="form-group">
                    <label for="catatan_tolak" class="fw-bold small text-secondary mb-1">Alasan Penolakan / Catatan Perbaikan:</label>
                    <textarea class="form-control rounded-3" id="catatan_tolak" rows="3" placeholder="Contoh: SKS tidak sesuai kurikulum baru atau kode MK duplikat..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 p-3 bg-light rounded-bottom-4">
                <button type="button" class="btn btn-secondary fw-semibold px-3 rounded-3" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-bold px-3 rounded-3" onclick="submitPenolakan()">Kirim & Tolak</button>
            </div>
        </div>
    </div>
</div>

<script>
    let idMkTolakTerpilih = null;
    let modalTolak = null;

    document.addEventListener("DOMContentLoaded", function() {
        modalTolak = new bootstrap.Modal(document.getElementById('modalTolakKurikulum'));
    });

    function prosesValidasi(id, status) {
        if(status === 'ACC') {
            executeAjaxValidasi(id, 'ACC', null);
        }
    }

    function bukaModalTolak(id, namaMk) {
        idMkTolakTerpilih = id;
        document.getElementById('nama-mk-modal').innerText = namaMk;
        document.getElementById('catatan_tolak').value = ''; 
        modalTolak.show();
    }

    function submitPenolakan() {
        let catatan = document.getElementById('catatan_tolak').value;
        if(catatan.trim() === "") {
            alert("Harap isi catatan alasan penolakan terlebih dahulu!");
            return;
        }
        executeAjaxValidasi(idMkTolakTerpilih, 'Ditolak', catatan);
        modalTolak.hide();
    }

    function executeAjaxValidasi(id, status, catatan) {
        // 🔍 SEKARANG URL NYA DISESUAIKAN DENGAN ROUTE BARU
        fetch(`/kurikulum/update-status/${id}`, { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                catatan: catatan
            })
        })
        // ... (sisa kode fetch di bawahnya biarkan tetap sama)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                let container = document.getElementById('status-container-' + id);
                if(status === 'ACC') {
                    container.innerHTML = `
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold border border-success border-opacity-25 animate__animated animate__fadeIn">
                            <i class="bi bi-patch-check-fill me-1"></i> Terpilih (ACC)
                        </span>
                    `;
                } else {
                    container.innerHTML = `
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold border border-danger border-opacity-25 animate__animated animate__fadeIn" title="Alasan: ${catatan}">
                            <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                        </span>
                    `;
                }
            } else {
                alert('Gagal memperbarui data di database.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi sistem.');
        });
    }
</script>
@endsection