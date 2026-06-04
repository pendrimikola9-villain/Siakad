@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="text-primary fw-bold">Struktur Manajemen Hak Akses (RBAC)</h2>
            <p class="text-secondary">Penjelasan mendalam mengenai skema tabel Role untuk mengontrol akses pengguna pada sistem akademik.</p>
        </div>
    </div>

    <!-- Bagian 1: Penjelasan Teknis Field -->
    <div class="card shadow border-0 mb-5">
        <div class="card-header bg-dark text-white p-3">
            <h5 class="mb-0"><i class="bi bi-database-fill-gear me-2"></i>Kamus Data Tabel: roles</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th width="15%">Nama Field</th>
                            <th width="15%">Tipe Data</th>
                            <th width="70%">Fungsi & Detail Teknis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><code>id</code></td>
                            <td class="text-center"><span class="badge bg-secondary">BigInt (PK)</span></td>
                            <td>
                                <strong>Primary Key & Auto-Increment.</strong> Digunakan sebagai referensi unik (Foreign Key) pada tabel <code>users</code>. 
                                Secara teknis, ini memastikan hubungan antar tabel (relasi) tetap konsisten dan mempercepat proses query data.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"><code>name</code></td>
                            <td class="text-center"><span class="badge bg-secondary">Varchar (50)</span></td>
                            <td>
                                <strong>Human-Readable Label.</strong> Nama role yang akan ditampilkan pada antarmuka pengguna (UI). 
                                Field ini bersifat fleksibel dan dapat diubah (misalnya dari "Admin" menjadi "Administrator Utama") tanpa merusak logika program.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"><code>slug</code></td>
                            <td class="text-center"><span class="badge bg-secondary">Varchar (50)</span></td>
                            <td>
                                <strong>System Key / Identifikasi Programmatic.</strong> String unik tanpa spasi (lowercase) yang digunakan dalam kodingan Laravel untuk pengecekan izin (Middleware). 
                                <em>Contoh:</em> Jika user memiliki slug <code>'admin'</code>, maka sistem memberikan izin untuk akses menu Dashboard.
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"><code>description</code></td>
                            <td class="text-center"><span class="badge bg-secondary">Text</span></td>
                            <td>
                                <strong>Audit & Dokumentasi Hak Akses.</strong> Menyimpan catatan detail mengenai batasan fungsionalitas. 
                                Membantu pengembang atau manajer sistem untuk mengetahui tugas spesifik dari role tersebut tanpa harus melihat kodingan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bagian 2: Contoh Implementasi Data -->
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Contoh Data & Level Otoritas</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Slug</th>
                        <th>Level Akses (Permission Scope)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td><span class="fw-bold">Administrator</span></td>
                        <td><code>admin</code></td>
                        <td><span class="text-danger fw-bold">Full Access:</span> Mengelola User, Mahasiswa, Matakuliah, dan Pengaturan Sistem.</td>
                    </tr>
                    <tr>
                        <td class="text-center">2</td>
                        <td><span class="fw-bold">Dosen</span></td>
                        <td><code>dosen</code></td>
                        <td><span class="text-warning fw-bold">Limited Access:</span> Mengelola nilai mahasiswa dan melihat jadwal mengajar.</td>
                    </tr>
                    <tr>
                        <td class="text-center">3</td>
                        <td><span class="fw-bold">Mahasiswa</span></td>
                        <td><code>mahasiswa</code></td>
                        <td><span class="text-success fw-bold">Read-Only:</span> Melihat profil pribadi, jadwal kuliah, dan riwayat nilai.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection