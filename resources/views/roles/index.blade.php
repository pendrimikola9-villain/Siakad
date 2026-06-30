@extends('layouts.app')

@section('content')
<div class="container-fluid py-2 animate__animated animate__fadeIn">

    <!-- HEADER & SESI LIVE -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark"><i class="bi bi-shield-check text-primary me-2"></i>Manajemen Hak Akses (Roles Control)</h2>
            <p class="text-secondary">Pengaturan hierarki tingkat hak akses pengguna berdasarkan lingkup operasional Fakultas dan Program Studi.</p>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-gradient text-white @if(Auth::user()->role === 'operator') bg-dark @elseif(Auth::user()->role === 'admin') bg-primary @elseif(Auth::user()->role === 'kaprodi') bg-warning text-dark @elseif(Auth::user()->role === 'dosen') bg-success @else bg-info text-white @endif rounded-4">
                <div class="card-body p-3 d-flex align-items-center">
                    <i class="bi bi-person-badge-fill fs-1 me-3"></i>
                    <div>
                        <small class="d-block opacity-75">Sesi Pengguna:</small>
                        <strong class="d-block text-truncate" style="max-width: 200px;">{{ Auth::user()->name }}</strong>
                        <span class="badge bg-white bg-opacity-25 text-capitalize mt-1">Role: {{ Auth::user()->role }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW CARD PENJELASAN (DITAMBAHKAN OPERATOR FAKULTAS) -->
    <div class="row g-3 animate__animated animate__fadeInUp mb-4">
        <!-- OPERATOR CARD (LEVEL FAKULTAS) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 @if(Auth::user()->role === 'operator') border border-dark border-3 shadow-lg @endif rounded-3">
                <div class="card-body p-4 text-center">
                    <div class="bg-dark bg-opacity-10 text-dark rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-building fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Operator Fakultas</h5>
                    <p class="text-muted small">Hak akses tertinggi tingkat Universitas/Fakultas. Berwenang memantau semua fitur global dan memiliki hak mutlak mengubah semua role user.</p>
                    <span class="badge bg-dark px-3 py-2">Fakultas Full Access</span>
                </div>
            </div>
        </div>

        <!-- ADMIN CARD (LEVEL PRODI) -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 @if(Auth::user()->role === 'admin') border border-primary border-3 shadow-lg @endif rounded-3">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-shield-lock fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Admin Program Studi</h5>
                    <p class="text-muted small">Mengelola master data operasional tingkat program studi (Mahasiswa, KRS, Jadwal). Dilarang mengubah status Operator Fakultas.</p>
                    <span class="badge bg-primary px-3 py-2">Prodi Full Access</span>
                </div>
            </div>
        </div>

        <!-- KAPRODI CARD -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 @if(Auth::user()->role === 'kaprodi') border border-warning border-3 shadow-lg @endif rounded-3">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-award fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Kaprodi</h5>
                    <p class="text-muted small">Akses manajerial bimbingan mahasiswa, pemantauan plot jadwal, persetujuan KRS, serta monitoring nilai dalam program studi.</p>
                    <span class="badge bg-warning text-dark px-3 py-2">Managerial Access</span>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL UTAMA: USER ROLE CONFIGURATOR (INTEGRASI IDE KAMU) -->
    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'operator'))
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-5 animate__animated animate__fadeInUp">
        <div class="card-header bg-white p-3 border-bottom px-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-people-fill text-primary me-2"></i>Konfigurasi Live Hak Akses Pengguna</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th width="5%" class="text-center py-3">No</th>
                            <th class="py-3">Nama Pengguna</th>
                            <th class="py-3">Email Terdaftar</th>
                            <th class="py-3 text-center">Role Saat Ini</th>
                            <th width="25%" class="text-center py-3">Ubah Hak Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $userItem)
                            <tr class="border-bottom border-light">
                                <td class="text-center fw-bold text-secondary">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-dark d-block mb-0">{{ $userItem->name }}</span>
                                    <small class="text-muted">ID Akun: #{{ $userItem->id }}</small>
                                </td>
                                <td><span class="text-secondary small">{{ $userItem->email }}</span></td>
                                <td class="text-center">
                                    @if($userItem->role === 'operator')
                                        <span class="badge bg-dark px-3 py-2 rounded-pill fw-bold"><i class="bi bi-building me-1"></i>Operator</span>
                                    @elseif($userItem->role === 'admin')
                                        <span class="badge bg-primary px-3 py-2 rounded-pill fw-bold"><i class="bi bi-shield-lock me-1"></i>Admin</span>
                                    @elseif($userItem->role === 'kaprodi')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold"><i class="bi bi-award me-1"></i>Kaprodi</span>
                                    @elseif($userItem->role === 'dosen')
                                        <span class="badge bg-success px-3 py-2 rounded-pill fw-bold"><i class="bi bi-person-badge me-1"></i>Dosen</span>
                                    @else
                                        <span class="badge bg-info text-white px-3 py-2 rounded-pill fw-bold"><i class="bi bi-mortarboard me-1"></i>Mahasiswa</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- 🔒 LOGIKA PENGUNCIAN ATURAN KAMU -->
                                    @if(Auth::user()->role === 'admin' && $userItem->role === 'operator')
                                        <!-- Admin Prodi dilarang merubah Operator Fakultas -->
                                        <button class="btn btn-sm btn-light rounded-3 px-3 fw-semibold border text-muted w-75" disabled>
                                            <i class="bi bi-lock-fill me-1"></i> Dikunci Admin
                                        </button>
                                    @else
                                        <!-- Form Eksekusi Pengubah Akses Langsung -->
                                        <form action="{{ route('roles.update', $userItem->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm mx-auto shadow-sm" style="max-width: 200px;">
                                                <select name="role" class="form-select text-capitalize" onchange="this.form.submit()">
                                                    <option value="operator" {{ $userItem->role == 'operator' ? 'selected' : '' }}>Operator</option>
                                                    <option value="admin" {{ $userItem->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="kaprodi" {{ $userItem->role == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                                                    <option value="dosen" {{ $userItem->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                                    <option value="mahasiswa" {{ $userItem->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                                </select>
                                            </div>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada data pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- MATRIKS KONTROL AKSES (ACL) -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-5">
        <div class="card-header bg-white p-3 border-bottom px-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-table text-secondary me-2"></i>Matriks Referensi Kontrol Akses (ACL)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light text-center text-dark">
                        <tr>
                            <th class="text-start" style="width: 35%;">Fitur / Modul Sistem</th>
                            <th style="width: 13%;" class="@if(Auth::user()->role === 'operator') table-dark fw-bold @endif">Operator</th>
                            <th style="width: 13%;" class="@if(Auth::user()->role === 'admin') table-primary fw-bold @endif">Admin</th>
                            <th style="width: 13%;" class="@if(Auth::user()->role === 'kaprodi') table-warning fw-bold @endif">Kaprodi</th>
                            <th style="width: 13%;" class="@if(Auth::user()->role === 'dosen') table-success fw-bold @endif">Dosen</th>
                            <th style="width: 13%;" class="@if(Auth::user()->role === 'mahasiswa') table-info fw-bold @endif">Mahasiswa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-semibold">Manajemen Hak Akses Global (User Role Control)</td>
                            <td class="text-center table-dark"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center @if(Auth::user()->role === 'admin') table-primary @endif"><span class="badge bg-warning text-dark">Batas Tertentu</span></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">CRUD Master Data Kampus (Set Server, Ruangan)</td>
                            <td class="text-center @if(Auth::user()->role === 'operator') table-dark @endif"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center @if(Auth::user()->role === 'admin') table-primary @endif"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-x-circle text-danger fs-5"></i></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Transaksi: Jadwal Kuliah (SIPLAR)</td>
                            <td class="text-center"><span class="badge bg-secondary text-white">Monitor</span></td>
                            <td class="text-center @if(Auth::user()->role === 'admin') table-primary @endif"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><span class="badge bg-light border text-dark">Lihat Saja</span></td>
                        </tr>
                        <tr>
                            <td class="fw-semibold">Transaksi: Log Bimbingan & Konsultasi (SIBIMBING)</td>
                            <td class="text-center"><span class="badge bg-secondary text-white">Monitor</span></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success fs-5"></i></td>
                            <td class="text-center"><span class="badge bg-warning text-dark">Monitor Sesi</span></td>
                            <td class="text-center"><span class="badge bg-success text-white">Validasi ACC</span></td>
                            <td class="text-center"><span class="badge bg-primary text-white">Input Pengajuan</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection