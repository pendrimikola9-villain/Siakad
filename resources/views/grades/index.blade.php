@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Input & Manajemen Nilai Mahasiswa</h2>
       @if(strtolower(Auth::user()->role) !== 'mahasiswa')
    <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNilai">
        <i class="bi bi-plus-square me-2"></i>Input Nilai Baru
    </button>
@endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-striped align-middle">
            <thead class="table-light">
    <tr>
        <th>No</th>
        <th>Nama Mahasiswa</th>
        <th>Matakuliah</th>
        <th>SKS</th>
        <th>Nilai</th>
        <th class="text-center">Grade</th>
        <th class="text-center">Status</th>
        @if(strtolower(Auth::user()->role) !== 'mahasiswa')
            <th class="text-center">Aksi</th>
        @endif
    </tr>
</thead>
            <tbody>
    @php $no = 1; @endphp
    @foreach($grades as $g)
        @php
            $roleUser = strtolower(Auth::user()->role);
            $nimUser = Auth::user()->email; // Karena kamu menyimpan NIM di kolom email user
            $mahasiswaNim = $g->mahasiswa->nim ?? '';
        @endphp

        @if($roleUser !== 'mahasiswa' || ($roleUser === 'mahasiswa' && $nimUser == $mahasiswaNim))
        <tr id="row-nilai-{{ $g->id }}">
            <td>{{ $no++ }}</td>
            <td>{{ $g->mahasiswa->nama ?? $g->nama }}</td>
            <td>{{ $g->course->nama_mk ?? $g->nama_mk }}</td>
            <td>{{ $g->course->sks ?? $g->sks ?? '2' }} SKS</td>
            <td>{{ $g->nilai }}</td>
            <td class="text-center">
                <span class="badge bg-primary fw-bold">{{ $g->grade }}</span>
            </td>
            
            <td class="text-center" id="status-kunci-{{ $g->id }}">
                @if(($g->status_kunci ?? 'Draft') == 'Locked')
                    <span class="badge bg-success"><i class="bi bi-lock-fill me-1"></i>Locked (Sah)</span>
                @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-file-earmark-text-fill me-1"></i>Draft</span>
                @endif
            </td>

            @if($roleUser !== 'mahasiswa')
            <td class="text-center">
                <div id="aksi-container-{{ $g->id }}" class="d-flex justify-content-center align-items-center gap-2">
                    @php
                        $statusKunci = $g->status_kunci ?? 'Draft';
                    @endphp

                    @if($statusKunci == 'Draft')
                        <a href="{{ route('grades.edit', $g->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex p-1 align-items-center" style="line-height: 1;" title="Edit Nilai">
                            <i class="bi bi-pencil-square fs-6"></i>
                        </a>

                        <form action="{{ route('grades.destroy', $g->id) }}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex p-1 align-items-center" style="line-height: 1;" title="Hapus Nilai">
                                <i class="bi bi-trash-fill fs-6"></i>
                            </button>
                        </form>
                        
                        @if($roleUser === 'dosen' || $roleUser === 'kaprodi')
                            <button type="button" class="btn btn-sm btn-success px-2 py-1 small" style="font-size: 0.75rem;" onclick="kunciNilaiPermanen({{ $g->id }}, '{{ $g->mahasiswa->nama ?? $g->nama }}')">
                                <i class="bi bi-lock-fill"></i> Kunci
                            </button>
                        @endif

                    @else
                        @if($roleUser === 'admin' || $roleUser === 'operator')
                            <button type="button" class="btn btn-sm btn-warning text-white fw-bold px-2 py-1 small" style="font-size: 0.75rem;" onclick="unlockNilaiAdmin({{ $g->id }}, '{{ $g->mahasiswa->nama ?? $g->nama }}')">
                                <i class="bi bi-key-fill"></i> Buka Kunci
                            </button>
                        @else
                            <span class="text-muted small"><i class="bi bi-shield-lock-fill"></i> Selesai</span>
                        @endif
                    @endif
                </div>
            </td>
            @endif
        </tr>
        @endif
    @endforeach
</tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNilai" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('grades.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Form Input Nilai</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" class="form-select" required>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach($mahasiswa as $m)
                                <option value="{{ $m->id }}">{{ $m->nim }} - {{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Mata Kuliah</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($courses as $c)
                                @if($c->status_validasi === 'ACC')
                                    <option value="{{ $c->id }}">{{ $c->kode_mk }} - {{ $c->nama_mk }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Input Nilai (0-100)</label>
                        <input type="number" name="nilai" class="form-control" placeholder="Contoh: 85" min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // 1. FUNGSI LOCK DOSEN / KAPRODI
    function kunciNilaiPermanen(id, namaMhs) {
        if (confirm(`Kunci nilai untuk ${namaMhs}? Setelah dikunci, nilai sah dan tidak bisa diubah oleh dosen.`)) {
            fetch(`/dosen/input-nilai/kunci/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update Badge Status di baris tabel
                    document.getElementById('status-kunci-' + id).innerHTML = '<span class="badge bg-success"><i class="bi bi-lock-fill me-1"></i>Locked (Sah)</span>';
                    
                    // Update Kontainer Tombol Aksi secara real-time
                    let aksiContainer = document.getElementById('aksi-container-' + id);
                    let roleUser = "{{ strtolower(Auth::user()->role) }}";

                    if (roleUser === 'admin' || roleUser === 'operator') {
                        aksiContainer.innerHTML = `
                            <button type="button" class="btn btn-sm btn-warning text-white fw-bold shadow-sm px-2 py-1 small" style="font-size: 0.75rem;" onclick="unlockNilaiAdmin(${id}, '${namaMhs}')">
                                <i class="bi bi-key-fill"></i> Buka Kunci
                            </button>
                        `;
                    } else {
                        aksiContainer.innerHTML = '<span class="text-muted small"><i class="bi bi-shield-lock-fill"></i> Selesai</span>';
                    }
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // 2. FUNGSI UNLOCK ADMIN / OPERATOR
    function unlockNilaiAdmin(id, namaMhs) {
        if (confirm(`Apakah Anda ingin membuka kembali kunci nilai untuk ${namaMhs}?`)) {
            fetch(`/admin/nilai/unlock/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Kembalikan Badge Status menjadi Draft
                    document.getElementById('status-kunci-' + id).innerHTML = '<span class="badge bg-warning text-dark"><i class="bi bi-file-earmark-text-fill me-1"></i>Draft</span>';
                    
                    // Munculkan kembali tombol Edit, Hapus, dan tombol Kunci (jika role-nya sesuai)
                    let aksiContainer = document.getElementById('aksi-container-' + id);
                    let roleUser = "{{ strtolower(Auth::user()->role) }}";
                    
                    // 🟢 PERBAIKAN: Menyisipkan ID dinamis pada route edit dan menyamakan padding tombol agar tetap rapi (p-1)
                    let htmlTombol = `
                        <a href="/grades/${id}/edit" class="btn btn-sm btn-outline-primary d-inline-flex p-1 align-items-center" style="line-height: 1;" title="Edit Nilai">
                            <i class="bi bi-pencil-square fs-6"></i>
                        </a>
                        <form action="/grades/${id}" method="POST" class="d-inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex p-1 align-items-center" style="line-height: 1;" title="Hapus Nilai">
                                <i class="bi bi-trash-fill fs-6"></i>
                            </button>
                        </form>
                    `;

                    if (roleUser === 'dosen' || roleUser === 'kaprodi') {
                        htmlTombol += `
                            <button type="button" class="btn btn-sm btn-success px-2 py-1 small" style="font-size: 0.75rem;" onclick="kunciNilaiPermanen(${id}, '${namaMhs}')">
                                <i class="bi bi-lock-fill"></i> Kunci
                            </button>
                        `;
                    }

                    aksiContainer.innerHTML = htmlTombol;
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

{{-- 🟢 PERBAIKAN: Posisi @endsection diletakkan di luar tag script paling akhir --}}
@endsection