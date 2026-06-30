@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary fw-bold">Input & Manajemen Nilai Mahasiswa</h2>
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNilai">
            <i class="bi bi-plus-square me-2"></i>Input Nilai Baru
        </button>
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
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grades as $key => $g)
                    <tr id="row-nilai-{{ $g->id }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $g->mahasiswa->nama ?? $g->nama }}</td>
                        <td>{{ $g->course->nama_mk ?? $g->nama_mk }}</td>
                        <td>{{ $g->course->sks ?? $g->sks ?? '2' }} SKS</td>
                        <td>{{ $g->nilai }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $g->grade }}</span>
                        </td>
                        
                        <td class="text-center" id="status-kunci-{{ $g->id }}">
                            @if(($g->status_kunci ?? 'Draft') == 'Locked')
                                <span class="badge bg-success">Locked (Sah)</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div id="aksi-container-{{ $g->id }}">
                                @if(($g->status_kunci ?? 'Draft') == 'Draft')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="kunciNilaiPermanen({{ $g->id }}, '{{ $g->mahasiswa->nama ?? $g->nama }}')">
                                        <i class="bi bi-unlock-fill"></i> Kunci
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-warning text-white fw-bold shadow-sm" onclick="unlockNilaiAdmin({{ $g->id }}, '{{ $g->mahasiswa->nama ?? $g->nama }}')">
                                        <i class="bi bi-key-fill"></i> Buka Kunci
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
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
    // 1. FUNGSI LOCK DOSEN
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
                    document.getElementById('status-kunci-' + id).innerHTML = '<span class="badge bg-success">Locked (Sah)</span>';
                    document.getElementById('aksi-container-' + id).innerHTML = `
                        <button type="button" class="btn btn-sm btn-warning text-white fw-bold shadow-sm" onclick="unlockNilaiAdmin(${id}, '${namaMhs}')">
                            <i class="bi bi-key-fill"></i> Buka Kunci
                        </button>
                    `;
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // 2. FUNGSI UNLOCK ADMIN
    function unlockNilaiAdmin(id, namaMhs) {
        if (confirm(`Apakah Anda (Admin) ingin membuka kembali kunci nilai untuk ${namaMhs}?`)) {
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
                    document.getElementById('status-kunci-' + id).innerHTML = '<span class="badge bg-warning text-dark">Draft</span>';
                    document.getElementById('aksi-container-' + id).innerHTML = `
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="kunciNilaiPermanen(${id}, '${namaMhs}')">
                            <i class="bi bi-unlock-fill"></i> Kunci
                        </button>
                    `;
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endsection