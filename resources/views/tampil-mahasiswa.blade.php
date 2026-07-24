@extends('layouts.app')

@section('content')
<div class="card shadow border-0">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-bold">Daftar Mahasiswa</h4>

        @php
            // Ambil role user & konversi ke lowercase agar aman dari perbedaan huruf besar/kecil
            $userRole = strtolower(Auth::user()?->role ?? Auth::user()?->level ?? '');
            $isAdminOrOperator = in_array($userRole, ['admin', 'operator']);
        @endphp

        {{-- 🟢 Tombol Tambah Mahasiswa HANYA muncul untuk Admin / Operator --}}
        @if($isAdminOrOperator)
            <a href="{{ route('create-mahasiswa') }}" class="btn btn-primary fw-semibold">
                <i class="bi bi-plus-circle me-1"></i> Tambah Mahasiswa
            </a>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $m)
                    <tr>
                        <td class="fw-bold">{{ $m->nim }}</td>
                        <td>{{ $m->nama }}</td>
                        <td>{{ $m->prodi }}</td>
                        <td>
                            <span class="badge {{ $m->status_mahasiswa == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $m->status_mahasiswa }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                {{-- 1. Tombol Detail (Mata Biru) -> BISA DIAKSES SEMUA ROLE --}}
                                <a href="{{ route('show-mahasiswa', $m->id) }}" class="btn btn-sm btn-info text-white shadow-sm" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- 🟢 2. Tombol Edit & Hapus HANYA muncul untuk Admin / Operator --}}
                                @if($isAdminOrOperator)
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('edit-mahasiswa', $m->id) }}" class="btn btn-sm btn-warning text-dark shadow-sm" title="Edit Data">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('delete-mahasiswa', $m->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm" title="Hapus Data" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-muted">Belum ada data mahasiswa.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection