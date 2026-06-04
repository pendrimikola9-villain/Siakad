@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    
    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-3">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Log Bimbingan Akademik (SIBIMBING)</h4>
            <a href="{{ route('bimbingan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Log Bimbingan
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Mahasiswa</th>
                            <th>Dosen Pembimbing</th>
                            <th>Topik Bimbingan</th>
                            <th>Ruangan</th>
                            <th class="text-center">Status</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td class="fw-bold text-center">{{ $index + 1 }}</td>
                            <td>
                                <span class="d-block fw-bold text-dark">{{ $log->nama_mahasiswa }}</span>
                                <small class="text-muted">NIM: {{ $log->nim }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $log->nama_dosen }}</span>
                            </td>
                            <td>
                                <span class="d-block text-dark fw-semibold">{{ $log->topik_bimbingan }}</span>
                                <small class="text-muted"><i class="bi bi-calendar-event me-1"></i>{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('d M Y') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $log->nama_ruangan }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $log->status_bimbingan == 'ACC' ? 'bg-success' : ($log->status_bimbingan == 'Revisi' ? 'bg-warning text-dark' : 'bg-primary') }}">
                                    {{ $log->status_bimbingan }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('bimbingan.edit', $log->id) }}" class="btn btn-sm btn-warning text-white shadow-sm">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('bimbingan.destroy', $log->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus log bimbingan ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-muted">Belum ada riwayat log bimbingan akademik.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection