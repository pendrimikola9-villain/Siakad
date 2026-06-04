@extends('layouts.app') {{-- Sesuaikan dengan nama file layout utama kamu --}}

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Data Transaksi Nilai (Gabungan 3 Tabel)</h5>
            </div>
            <div class="card-body p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th>Nilai Angka</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataNilai as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <!-- Data dari Tabel Mahasiswa -->
                                <td>{{ $item->nama }}</td>
                                <!-- Data dari Tabel Courses -->
                                <td>{{ $item->nama_mk }}</td>
                                <td class="text-center">{{ $item->sks }}</td>
                                <!-- Data dari Tabel Transaksi (Grades) -->
                                <td class="text-center fw-bold text-primary">{{ $item->nilai }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data transaksi nilai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <a href="/" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection