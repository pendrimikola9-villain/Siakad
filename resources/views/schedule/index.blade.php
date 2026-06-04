@extends('layouts.app')

@section('content')
<div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Jadwal Kuliah (SIPLAR)</h4>
    <a href="{{ route('jadwal.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
    </a>
</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>Waktu & Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen Pengampu</th>
                        <th>Ruangan / Lab</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $index => $jadwal)
                    <tr>
                        <td class="fw-bold text-center">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-primary mb-1">{{ $jadwal->hari }}</span><br>
                            <small class="fw-semibold text-dark">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }} WITA</small><br>
                            <small class="text-muted">Kelas: <strong>{{ $jadwal->kelas }}</strong></small>
                        </td>
                        <td>
                            <span class="d-block fw-bold text-dark">{{ $jadwal->nama_mk }}</span>
                            <small class="text-muted">Kode: {{ $jadwal->kode_mk }} | {{ $jadwal->sks }} SKS</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $jadwal->nama_dosen }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><i class="bi bi-door-open me-1"></i>{{ $jadwal->nama_ruangan }}</span>
                        </td>
                       <td class="text-center">
    <div class="d-flex justify-content-center gap-1">
        <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="btn btn-sm btn-warning text-white shadow-sm">
            <i class="bi bi-pencil"></i>
        </a>

        <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus jadwal kuliah ini?')">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">Belum ada data jadwal kuliah reguler.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection