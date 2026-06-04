@extends('layouts.app')

@section('content')
<div class="card shadow border-0 mt-4">
    <div class="card-header bg-white p-3 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Daftar Dosen</h4>
        <a href="{{ route('dosen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Dosen
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIDN / NIK</th>
                        <th>Nama Dosen</th>
                        <th>Kontak & Email</th>
                        <th>Info Akademik</th>
                        <th>Alamat Domisili</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturers as $index => $dosen)
                    <tr>
                        <td class="fw-bold text-center">{{ $index + 1 }}</td>
                        <td>
                            <span class="d-block fw-bold text-dark">{{ $dosen->nidn }}</span>
                            <small class="text-muted">NIK: {{ $dosen->nik_karyawan }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold d-block text-dark">{{ $dosen->nama_dosen }}</span>
                            <small class="text-muted">{{ $dosen->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $dosen->tempat_lahir }}</small>
                        </td>
                        <td>
                            <span class="d-block"><i class="bi bi-whatsapp text-success me-1"></i>{{ $dosen->no_hp }}</span>
                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $dosen->email_dosen }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-secondary d-block">{{ $dosen->pendidikan_terakhir }}</span>
                            <small class="text-muted">Jabatan: {{ $dosen->jabatan_fungsional }}</small><br>
                            <span class="badge bg-warning text-dark mt-1">{{ $dosen->bidang_keahlian }}</span>
                        </td>
                        <td>
                            <small class="text-muted d-inline-block text-truncate" style="max-width: 180px;" title="{{ $dosen->alamat_lengkap }}">
                                {{ $dosen->alamat_lengkap }}
                            </small>
                        </td>
                       <td>
    <div class="d-flex gap-1">
      <a href="{{ route('dosen.show', $dosen->id) }}" class="btn btn-sm btn-info text-white shadow-sm">
    <i class="bi bi-eye"></i>
</a>

        <a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-sm btn-warning text-white shadow-sm">
            <i class="bi bi-pencil"></i>
        </a>

        <form action="{{ route('dosen.destroy', $dosen->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus data dosen ini?')">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted">Belum ada data master dosen.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection