@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 mt-4">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-0 rounded-top-3">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="fas fa-door-open me-2 text-primary"></i> Data Ruangan & Laboratorium
            </h5>
            <a href="{{ route('room.create') }}" class="btn btn-primary px-3 shadow-sm rounded-2 fw-semibold">
                <i class="fas fa-plus-circle me-1"></i> Tambah Ruangan
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="8%" class="text-center py-3">No</th>
                            <th>Nama Ruangan / Lab</th>
                            <th>Jenis Ruangan</th>
                            <th class="text-center">Kapasitas</th>
                            <th>Lokasi Gedung</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $index => $ruang)
                        <tr>
                            <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                            <td><span class="fw-semibold text-dark">{{ $ruang->nama_ruangan }}</span></td>
                            <td>
                                <span class="badge {{ $ruang->jenis_ruangan == 'Laboratorium' ? 'bg-info' : 'bg-secondary' }} px-2.5 py-1.5 fs-7 fw-normal">
                                    {{ $ruang->jenis_ruangan }}
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-dark">{{ $ruang->kapasitas }} Kursi</td>
                            <td><span class="text-muted"><i class="fas fa-building me-1"></i>{{ $ruang->lokasi_gedung }}</span></td>
                            <td class="text-center">
    <div class="d-flex justify-content-center gap-1">
    

        <a href="{{ route('room.edit', $ruang->id) }}" class="btn btn-sm btn-warning text-white shadow-sm" title="Edit Ruangan">
    <i class="bi bi-pencil"></i>
</a>

        <form action="{{ route('room.destroy', $ruang->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus data ruangan ini?')" title="Hapus Ruangan">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">Belum ada data ruangan yang diinputkan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection