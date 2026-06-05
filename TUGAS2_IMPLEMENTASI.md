# TUGAS 2 — IMPLEMENTASI PROGRAM

## Aplikasi Pengolahan Nilai Siswa "EduGrade"

**Nama Aplikasi:** EduGrade  
**Framework:** Laravel 13 + Filament 5 (Admin Panel)  
**Bahasa Pemrograman:** PHP 8.3  
**Database:** MySQL  
**Tanggal:** Juni 2026  

---

## Daftar Isi

1. [Halaman Login Berdasarkan Role](#1-halaman-login-berdasarkan-role)
2. [Form Input Data Siswa dan Nilai](#2-form-input-data-siswa-dan-nilai)
3. [Proses Perhitungan Nilai Akhir](#3-proses-perhitungan-nilai-akhir)
4. [Laporan Hasil Nilai Siswa](#4-laporan-hasil-nilai-siswa)
5. [Bukti Pengujian Database](#5-bukti-pengujian-database)
6. [Catatan Error / Debugging](#6-catatan-error--debugging)
7. [Perbaikan Error dan Hasil Setelah Diperbaiki](#7-perbaikan-error-dan-hasil-setelah-diperbaiki)
8. [Potongan Kode Fungsi / Procedure](#8-potongan-kode-fungsi--procedure)
9. [Potongan Kode Class dan Method](#9-potongan-kode-class-dan-method)
10. [Penjelasan Library atau Komponen yang Digunakan](#10-penjelasan-library-atau-komponen-yang-digunakan)
11. [Penjelasan Coding Guidelines dan Best Practices](#11-penjelasan-coding-guidelines-dan-best-practices)

---

## 1. Halaman Login Berdasarkan Role

### 1.1 Sistem Autentikasi

Aplikasi EduGrade menggunakan **Filament Panel** sebagai sistem admin panel yang sudah memiliki halaman login built-in. Sistem login terintegrasi dengan **Spatie Laravel Permission** dan **Filament Shield** untuk manajemen role dan permission.

[gambar]

### 1.2 Tiga Role Pengguna

Sistem memiliki 3 role utama yang didefinisikan di `database/seeders/RoleSeeder.php`:

| No | Role | Hak Akses |
|----|------|-----------|
| 1 | **Super Admin / Admin** | Akses penuh ke semua resource (CRUD User, Siswa, Guru, Mata Pelajaran, Nilai, Laporan) |
| 2 | **Guru** | CRUD Nilai, View Siswa, View Guru, View Mata Pelajaran, View Laporan Nilai |
| 3 | **Siswa** | View Laporan Nilai (hanya nilai pribadi) |

### 1.3 Implementasi Hak Akses pada Model User

File: `app/Models/User.php`

```php
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasPanelShield;

    // Helper methods untuk cek role
    public function isAdmin(): bool
    {
        return $this->hasRole(['admin', 'super_admin']);
    }

    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    public function isSiswa(): bool
    {
        return $this->hasRole('siswa');
    }

    // Semua user boleh akses panel Filament
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
```

### 1.4 Permission yang Di-assign per Role

**Role Admin:**
- Semua permission di-sync dari tabel permissions (`$admin->syncPermissions($allPermissions)`)

**Role Guru:**
```php
$guruPermissionNames = [
    'ViewAny:Nilai', 'View:Nilai', 'Create:Nilai', 'Update:Nilai', 'Delete:Nilai',
    'ViewAny:Siswa', 'View:Siswa',
    'ViewAny:Guru', 'View:Guru',
    'View:LaporanNilai',
    'ViewAny:MataPelajaran', 'View:MataPelajaran',
];
```

**Role Siswa:**
```php
$siswaPermissionNames = [
    'View:LaporanNilai',
];
```

### 1.5 Demo Akun Login

| Email | Password | Role |
|-------|----------|------|
| ilham@admin.com | 12345678 | super_admin |
| guru1@edugrade.com | password | guru |
| guru2@edugrade.com | password | guru |
| siswa1@edugrade.com | password | siswa |
| siswa2@edugrade.com | password | siswa |
| siswa3@edugrade.com | password | siswa |

### 1.6 Tampilan Dashboard Berdasarkan Role

Setiap role memiliki **dashboard yang berbeda** berdasarkan widget statistik di `app/Filament/Widgets/StatsOverviewWidget.php`:

**Dashboard Admin:**
- Total Siswa terdaftar
- Total Guru pengajar
- Rata-rata Nilai seluruh siswa dengan persentase lulus
- Total Data Nilai tersimpan

**Dashboard Guru:**
- Mata Pelajaran yang diampu
- Total Nilai yang sudah diinput
- Rata-rata nilai beserta jumlah lulus/tidak lulus

**Dashboard Siswa:**
- Kelas dan NIS
- Rata-rata Nilai pribadi dengan jumlah mapel lulus
- Total Mata Pelajaran yang sudah dinilai

[gambar]

---

## 2. Form Input Data Siswa dan Nilai

### 2.1 Form Input Data Siswa

File: `app/Filament/Resources/Siswas/Schemas/SiswaForm.php`

[gambar]

Form data siswa memiliki field berikut:

| No | Field | Tipe | Validasi | Keterangan |
|----|-------|------|----------|------------|
| 1 | User | Select (searchable) | Required | Hanya menampilkan user dengan role 'siswa' yang belum dipakai siswa lain |
| 2 | NIS | TextInput | Disabled/auto-generate | Format: `NIS-YYYYMMDD-XXXXX`, di-generate otomatis saat create |
| 3 | Nama | TextInput | Required, max 255 | Nama lengkap siswa |
| 4 | Kelas | Select | Required | Relasi ke model Kelas |

**Auto-generate NIS** pada model `app/Models/Siswa.php`:

```php
protected static function boot()
{
    parent::boot();

    static::creating(function ($siswa) {
        if (empty($siswa->nis)) {
            $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' 
                        . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
        }
    });
}
```

### 2.2 Form Input Data Guru

File: `app/Filament/Resources/Gurus/Schemas/GuruForm.php`

[gambar]

| No | Field | Tipe | Validasi | Keterangan |
|----|-------|------|----------|------------|
| 1 | Akun User | Select (searchable) | Required | Hanya user dengan role 'guru' yang belum terpakai |
| 2 | ID Guru | TextInput | Disabled/auto-generate | Format: `GR-YYYYMMDD-XXXXX` |
| 3 | Nama Guru | TextInput | Required, max 255 | Nama lengkap guru |
| 4 | Mata Pelajaran | Select Multiple (searchable) | Optional | Relasi many-to-many ke MataPelajaran |

### 2.3 Form Input Nilai

File: `app/Filament/Resources/Nilais/Schemas/NilaiForm.php`

[gambar]

Form nilai terdiri dari **2 section** utama:

**Section 1: Data Relasi (4 kolom)**

| No | Field | Tipe | Validasi | Keterangan |
|----|-------|------|----------|------------|
| 1 | Guru | Select (searchable, live) | Required | Guru login otomatis terpilih & disabled; Admin bisa pilih semua guru |
| 2 | Mata Pelajaran | Select (searchable, live) | Required | Difilter berdasarkan guru yang dipilih (hanya mapel yang diampu guru) |
| 3 | Semester | Select | Required | Pilihan: Ganjil, Genap |
| 4 | Siswa | Select (searchable) | Required | Hanya menampilkan siswa yang belum dinilai untuk kombinasi guru+mapel+semester |

**Section 2: Input Nilai (3 kolom)**

| No | Field | Tipe | Validasi | Keterangan |
|----|-------|------|----------|------------|
| 1 | Nilai Tugas (30%) | TextInput | Required, numeric, 0-100 | Live update saat blur |
| 2 | Nilai UTS (30%) | TextInput | Required, numeric, 0-100 | Live update saat blur |
| 3 | Nilai UAS (40%) | TextInput | Required, numeric, 0-100 | Live update saat blur |

**Fitur-fitur Penting pada Form Nilai:**

1. **Cascade Select** — Saat guru dipilih, mapel dan siswa di-reset. Saat mapel berubah, siswa di-reset.
2. **Guru Auto-select** — Jika user login adalah guru, field guru otomatis terisi dan di-disable.
3. **Filter Siswa yang Sudah Dinilai** — Siswa yang sudah punya nilai untuk kombinasi guru+mapel+semester tidak ditampilkan lagi.
4. **Realtime Perhitungan** — Nilai akhir dan status dihitung otomatis menggunakan fungsi prosedural saat input nilai berubah.

```php
protected static function updateNilaiAkhir(Get $get, Set $set): void
{
    $tugas = (float) $get('nilai_tugas');
    $uts = (float) $get('nilai_uts');
    $uas = (float) $get('nilai_uas');

    // Validasi nilai menggunakan fungsi prosedural
    if (! validasiNilai($tugas) || ! validasiNilai($uts) || ! validasiNilai($uas)) {
        return;
    }

    // Penerapan pemrograman terstruktur (prosedural):
    $nilaiAkhir = hitungNilaiAkhirProsedural($tugas, $uts, $uas);
    $status = tentukanStatusKelulusan($nilaiAkhir);

    $set('nilai_akhir', round($nilaiAkhir, 2));
    $set('status', $status);
}
```

---

## 3. Proses Perhitungan Nilai Akhir

### 3.1 Rumus Perhitungan

```
Nilai Akhir = (30% × Tugas) + (30% × UTS) + (40% × UAS)
```

Ketentuan:
- Rentang nilai valid: **0 – 100**
- Siswa dinyatakan **Lulus** jika Nilai Akhir **≥ 70**
- Siswa dinyatakan **Tidak Lulus** jika Nilai Akhir **< 70**

### 3.2 Implementasi Prosedural (Pemrograman Terstruktur)

File: `app/Helpers/helpers.php`

```php
// 1. Fungsi Validasi Nilai
function validasiNilai($nilai)
{
    return $nilai >= 0 && $nilai <= 100;
}

// 2. Fungsi Hitung Nilai Akhir
function hitungNilaiAkhirProsedural($tugas, $uts, $uas)
{
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}

// 3. Fungsi Tentukan Status Kelulusan
function tentukanStatusKelulusan($nilaiAkhir)
{
    return $nilaiAkhir >= 70 ? 'Lulus' : 'Tidak Lulus';
}

// 4. Fungsi Buat Laporan Nilai
function buatLaporanNilai($dataNilai)
{
    return $dataNilai;
}
```

Keempat fungsi ini di-autoload melalui `composer.json`:

```json
"autoload": {
    "files": [
        "app/Helpers/helpers.php"
    ]
}
```

### 3.3 Implementasi OOP (Pemrograman Berorientasi Objek)

File: `app/Models/Nilai.php`

```php
class Nilai extends Model
{
    // Model event 'saving' — auto-compute saat disimpan ke database
    protected static function booted(): void
    {
        static::saving(function (Nilai $nilai) {
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
            $nilai->status = $nilai->tentukanStatus();
        });
    }

    // Method OOP: Hitung Nilai Akhir
    public function hitungNilaiAkhir(): float
    {
        return ($this->nilai_tugas * 0.30) + ($this->nilai_uts * 0.30) + ($this->nilai_uas * 0.40);
    }

    // Method OOP: Tentukan Status
    public function tentukanStatus(): string
    {
        return $this->hitungNilaiAkhir() >= 70 ? 'Lulus' : 'Tidak Lulus';
    }
}
```

### 3.4 Contoh Perhitungan

| Siswa | Tugas | UTS | UAS | Perhitungan | Nilai Akhir | Status |
|-------|-------|-----|-----|-------------|-------------|--------|
| Andi Prasetyo | 85 | 78 | 82 | (85×0.3)+(78×0.3)+(82×0.4) = 25.5+23.4+32.8 | **81.70** | ✅ Lulus |
| Citra Dewi | 92 | 88 | 95 | (92×0.3)+(88×0.3)+(95×0.4) = 27.6+26.4+38.0 | **92.00** | ✅ Lulus |
| Rizki Ramadhan | 60 | 55 | 65 | (60×0.3)+(55×0.3)+(65×0.4) = 18.0+16.5+26.0 | **60.50** | ❌ Tidak Lulus |

### 3.5 Dua Titik Perhitungan

Perhitungan terjadi di **dua titik** untuk memastikan konsistensi:

1. **Sisi Client (Realtime di Form)** — Menggunakan fungsi prosedural `hitungNilaiAkhirProsedural()` dan `tentukanStatusKelulusan()` dari `helpers.php` via method `updateNilaiAkhir()` di `NilaiForm.php`.

2. **Sisi Server (Model Event)** — Menggunakan method OOP `hitungNilaiAkhir()` dan `tentukanStatus()` pada model `Nilai` via event `saving`. Ini menjamin bahwa meskipun data diubah langsung dari Tinker, seeder, atau API, nilai akhir dan status tetap benar.

---

## 4. Laporan Hasil Nilai Siswa

### 4.1 Halaman Laporan Nilai

File: `app/Filament/Pages/LaporanNilai.php`  
View: `resources/views/filament/pages/laporan-nilai.blade.php`

[gambar]

Halaman laporan menampilkan:

- **Statistik Overview** — Card visual dengan Total Data Nilai dan Rata-rata Nilai
- **Filter Section** (hanya Admin) — Cari Nama Siswa, Filter Kelas, Mata Pelajaran, Semester, Status
- **Tabel Detail Nilai** — Menampilkan NIS, Siswa, Kelas, Semester, Guru, Mata Pelajaran, Tugas, UTS, UAS, Nilai Akhir, Status

### 4.2 Akses Berdasarkan Role

| Role | Tampilan Laporan |
|------|-----------------|
| Admin/Super Admin | Semua data nilai + Filter lengkap + Export Excel |
| Guru | Hanya nilai yang dia input |
| Siswa | Hanya nilai pribadinya |

### 4.3 Fitur Filter (Admin Only)

```php
// Filter yang tersedia:
public $searchSiswa = '';     // Pencarian nama siswa
public $filterKelas = '';     // Filter berdasarkan kelas
public $filterMapel = '';     // Filter berdasarkan mata pelajaran
public $filterSemester = '';  // Filter berdasarkan semester
public $filterStatus = '';    // Filter berdasarkan status lulus/tidak lulus
```

### 4.4 Export Laporan ke Excel

File: `app/Exports/LaporanNilaiExport.php`

Admin dapat mengexport laporan nilai ke format **Excel (.xlsx)** melalui tombol "Export Excel" pada halaman laporan. Export menggunakan library **Maatwebsite/Excel** dengan format kolom:

| Kolom Export |
|-------------|
| NIS, Nama Siswa, Kelas, Semester, Guru, Mata Pelajaran, Tugas, UTS, UAS, Nilai Akhir, Status |

### 4.5 Tabel Nilai (Resource Filament)

File: `app/Filament/Resources/Nilais/Tables/NilaisTable.php`

Tabel Nilai pada resource Filament juga menyediakan:
- **Kolom**: Siswa, Kelas (badge), Semester (badge berwarna), Guru, Mata Pelajaran (badge), Tugas, UTS, UAS, Nilai Akhir (bold), Status (badge hijau/merah)
- **Filter**: Kelas, Semester, Status Kelulusan, Guru, Mata Pelajaran (searchable)
- **Aksi**: View, Edit, Hapus (dengan konfirmasi)
- **Bulk Action**: Export Excel, Delete, Force Delete, Restore

[gambar]

### 4.6 Chart Widget Distribusi Kelulusan

File: `app/Filament/Widgets/NilaiChartWidget.php`

Widget chart **Doughnut** pada dashboard menampilkan perbandingan jumlah siswa Lulus vs Tidak Lulus.

[gambar]

---

## 5. Bukti Pengujian Database

### 5.1 Struktur Tabel Database

Migrasi database terdiri dari file-file berikut:

| No | File Migrasi | Tabel | Keterangan |
|----|-------------|-------|------------|
| 1 | `0001_01_01_000000_create_users_table.php` | `users` | Tabel utama user dengan soft deletes |
| 2 | `2026_06_03_064958_create_mata_pelajarans_table.php` | `mata_pelajarans` | Master data mata pelajaran |
| 3 | `2026_06_03_064959_create_siswas_table.php` | `siswas` | Data siswa, FK ke users |
| 4 | `2026_06_03_065000_create_gurus_table.php` | `gurus` | Data guru, FK ke users |
| 5 | `2026_06_03_065000_create_nilais_table.php` | `nilais` | Data nilai, FK ke siswas, gurus, mata_pelajarans |
| 6 | `2026_06_03_065001_create_guru_mapel_table.php` | `guru_mapel` | Pivot many-to-many guru↔mapel |
| 7 | `2026_06_04_150000_add_semester_to_nilais_table.php` | `nilais` | Menambah kolom semester |

### 5.2 Struktur Tabel

**Tabel `users`:**

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL  -- Soft Delete
);
```

**Tabel `siswas`:**

```sql
CREATE TABLE siswas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED UNIQUE NOT NULL, -- FK ke users, cascade delete
    nis VARCHAR(255) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    kelas_id BIGINT UNSIGNED NULL,       -- FK ke kelas, set null on delete
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

**Tabel `kelas`:**

```sql
CREATE TABLE kelas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

**Tabel `gurus`:**

```sql
CREATE TABLE gurus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED UNIQUE NOT NULL, -- FK ke users, cascade delete
    id_guru VARCHAR(255) UNIQUE NOT NULL,
    nama_guru VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

**Tabel `mata_pelajarans`:**

```sql
CREATE TABLE mata_pelajarans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_mapel VARCHAR(255) UNIQUE NOT NULL,
    nama_mapel VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

**Tabel `guru_mapel` (Pivot):**

```sql
CREATE TABLE guru_mapel (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    guru_id BIGINT UNSIGNED NOT NULL,    -- FK ke gurus, cascade delete
    mapel_id BIGINT UNSIGNED NOT NULL,   -- FK ke mata_pelajarans, cascade delete
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE (guru_id, mapel_id)           -- Unique constraint
);
```

**Tabel `nilais`:**

```sql
CREATE TABLE nilais (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    siswa_id BIGINT UNSIGNED NOT NULL,   -- FK ke siswas, cascade delete
    guru_id BIGINT UNSIGNED NOT NULL,    -- FK ke gurus, cascade delete
    mapel_id BIGINT UNSIGNED NOT NULL,   -- FK ke mata_pelajarans, cascade delete
    semester VARCHAR(255) NULL,
    nilai_tugas DECIMAL(5,2) DEFAULT 0,
    nilai_uts DECIMAL(5,2) DEFAULT 0,
    nilai_uas DECIMAL(5,2) DEFAULT 0,
    nilai_akhir DECIMAL(5,2) NULL,       -- Auto-calculated
    status VARCHAR(255) NULL,            -- Auto-determined
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);
```

### 5.3 Relasi Antar Tabel (ERD)

```
┌──────────┐       ┌────────────┐       ┌──────────────────┐
│  users   │       │   gurus    │◄──M──►│    guru_mapel     │◄──M──►│ mata_pelajarans │
├──────────┤       ├────────────┤       ├───────────────────┤       ├─────────────────┤
│ id       │──1:1─►│ id         │       │ id                │       │ id              │
│ name     │       │ user_id(FK)│       │ guru_id(FK)       │       │ kode_mapel      │
│ email    │       │ id_guru    │       │ mapel_id(FK)      │       │ nama_mapel      │
│ password │       │ nama_guru  │       └───────────────────┘       └─────────────────┘
└──────────┘       └────────────┘                                          │
     │                   │                                                 │ FK
     │ 1:1               │ FK                                              ▼
     ▼                   ▼                                          ┌──────────┐
┌──────────┐       ┌──────────┐                                     │  nilais  │
│  siswas  │       │  nilais  │◄────────────────────────────────────┤          │
├──────────┤       ├──────────┤                                     ├──────────┤
│ id       │──FK──►│ siswa_id │                                     │ siswa_id │
│ user_id  │       │ guru_id  │                                     │ guru_id  │
│ nis      │       │ mapel_id │                                     │ mapel_id │
│ nama     │       │ semester │                                     └──────────┘
│ kelas_id │       │ nilai_*  │
└──────────┘       └──────────┘
```

### 5.4 Seeder Data Demo

File: `database/seeders/DatabaseSeeder.php`

Data demo yang di-seed:

| Data | Jumlah | Detail |
|------|--------|--------|
| Mata Pelajaran | 3 | Matematika (MTK), Bahasa Indonesia (BIN), IPA |
| User Super Admin | 1 | Administrator |
| User Guru | 2 | Budi Santoso (MTK, IPA), Siti Nurhaliza (BIN) |
| User Siswa | 3 | Andi Prasetyo, Citra Dewi, Rizki Ramadhan |
| Data Nilai | 6 | Nilai MTK dan BIN untuk 3 siswa |

### 5.5 Koneksi Database

Koneksi database dikonfigurasi melalui file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_edugrade
DB_USERNAME=root
DB_PASSWORD=
```

Menjalankan migrasi dan seeder:

```bash
php artisan migrate
php artisan db:seed
```

[gambar]

---

## 6. Catatan Error / Debugging

### 6.1 Error 1: NIS Duplikat saat Soft Delete

**Deskripsi:** Saat siswa dihapus (soft delete) lalu ditambah siswa baru, NIS auto-generate bisa bentrok karena `count()` default tidak menghitung record yang di-soft-delete.

**Kode Error:**
```php
$siswa->nis = 'NIS-' . now()->format('Ymd') . '-' . str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
```

**Error Message:** `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'NIS-...' for key 'siswas_nis_unique'`

### 6.2 Error 2: Guru Tanpa Profil Melihat Semua Nilai

**Deskripsi:** Jika user dengan role guru login tetapi belum memiliki profil guru di tabel `gurus`, query Eloquent tidak difilter sehingga menampilkan semua nilai.

**Kode Error:**
```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();
    if ($user && $user->hasRole('guru')) {
        $guru = Guru::where('user_id', $user->id)->first();
        // Jika $guru null, filter tidak diterapkan → semua data terlihat
        $query->where('guru_id', $guru->id);
    }
    return $query;
}
```

**Error Message:** `Trying to get property 'id' of non-object`

### 6.3 Error 3: Nilai Akhir dan Status Tidak Otomatis Terisi

**Deskripsi:** Awalnya perhitungan nilai akhir hanya dilakukan di sisi form (client-side). Ketika data dimasukkan melalui seeder atau Tinker, field `nilai_akhir` dan `status` tetap null karena tidak melalui form.

---

## 7. Perbaikan Error dan Hasil Setelah Diperbaiki

### 7.1 Perbaikan Error 1: NIS Duplikat

**Solusi:** Menggunakan `withTrashed()->count()` untuk menghitung semua record termasuk yang di-soft-delete.

**Kode Perbaikan di `app/Models/Siswa.php`:**
```php
protected static function boot()
{
    parent::boot();

    static::creating(function ($siswa) {
        if (empty($siswa->nis)) {
            $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' 
                        . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
        }
    });
}
```

**Hasil:** NIS tidak pernah duplikat karena counter mencakup record yang dihapus.

### 7.2 Perbaikan Error 2: Null Safety untuk Guru

**Solusi:** Menambahkan pengecekan null sebelum menggunakan `$guru->id`, dan jika null, query di-force kosong.

**Kode Perbaikan di `app/Filament/Resources/Nilais/NilaiResource.php`:**
```php
if ($user && $user->hasRole('guru')) {
    $guru = \App\Models\Guru::where('user_id', $user->id)->first();
    if ($guru) {
        $query->where('guru_id', $guru->id);
    } else {
        // Guru belum punya profil, jangan tampilkan apa-apa
        $query->whereRaw('1 = 0');
    }
}
```

**Hasil:** Guru tanpa profil tidak melihat data apapun (aman), tidak ada error.

### 7.3 Perbaikan Error 3: Model Event Saving

**Solusi:** Menambahkan model event `saving` pada model `Nilai` agar `nilai_akhir` dan `status` selalu dihitung otomatis sebelum disimpan.

**Kode Perbaikan di `app/Models/Nilai.php`:**
```php
protected static function booted(): void
{
    static::saving(function (Nilai $nilai) {
        $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
        $nilai->status = $nilai->tentukanStatus();
    });
}
```

**Hasil:** Meskipun data dimasukkan dari seeder, Tinker, atau sumber lain, `nilai_akhir` dan `status` selalu terisi dengan benar.

---

## 8. Potongan Kode Fungsi / Procedure

### 8.1 Fungsi Prosedural (Pemrograman Terstruktur)

File: `app/Helpers/helpers.php`

Berikut adalah 4 fungsi prosedural yang diimplementasikan:

#### Fungsi 1: `validasiNilai()`

```php
/**
 * Memvalidasi nilai berada pada rentang 0-100
 * 
 * @param float|int $nilai
 * @return bool
 */
function validasiNilai($nilai)
{
    return $nilai >= 0 && $nilai <= 100;
}
```

**Penggunaan:** Dipanggil di `NilaiForm.php` pada method `updateNilaiAkhir()` sebelum menghitung nilai akhir.

#### Fungsi 2: `hitungNilaiAkhirProsedural()`

```php
/**
 * Menghitung nilai akhir dengan rumus: 30% tugas + 30% UTS + 40% UAS
 * 
 * @param float $tugas
 * @param float $uts
 * @param float $uas
 * @return float
 */
function hitungNilaiAkhirProsedural($tugas, $uts, $uas)
{
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}
```

**Penggunaan:** Dipanggil di `NilaiForm.php` untuk menghitung nilai akhir secara realtime pada form.

#### Fungsi 3: `tentukanStatusKelulusan()`

```php
/**
 * Menentukan status kelulusan berdasarkan nilai akhir (>= 70 lulus)
 * 
 * @param float $nilaiAkhir
 * @return string
 */
function tentukanStatusKelulusan($nilaiAkhir)
{
    return $nilaiAkhir >= 70 ? 'Lulus' : 'Tidak Lulus';
}
```

**Penggunaan:** Dipanggil di `NilaiForm.php` untuk menentukan status kelulusan secara realtime.

#### Fungsi 4: `buatLaporanNilai()`

```php
/**
 * Memformat laporan nilai
 * 
 * @param mixed $dataNilai
 * @return mixed
 */
function buatLaporanNilai($dataNilai)
{
    return $dataNilai;
}
```

**Penggunaan:** Dipanggil di `LaporanNilai.php` dan `routes/web.php` (export) untuk memproses data sebelum ditampilkan di laporan.

### 8.2 Integrasi Fungsi Prosedural di Form

File: `app/Filament/Resources/Nilais/Schemas/NilaiForm.php`

```php
protected static function updateNilaiAkhir(Get $get, Set $set): void
{
    $tugas = (float) $get('nilai_tugas');
    $uts = (float) $get('nilai_uts');
    $uas = (float) $get('nilai_uas');

    // ① Validasi menggunakan fungsi prosedural
    if (! validasiNilai($tugas) || ! validasiNilai($uts) || ! validasiNilai($uas)) {
        return;
    }

    // ② Hitung nilai akhir secara prosedural
    $nilaiAkhir = hitungNilaiAkhirProsedural($tugas, $uts, $uas);

    // ③ Tentukan status kelulusan secara prosedural
    $status = tentukanStatusKelulusan($nilaiAkhir);

    $set('nilai_akhir', round($nilaiAkhir, 2));
    $set('status', $status);
}
```

---

## 9. Potongan Kode Class dan Method

### 9.1 Class `Siswa`

File: `app/Models/Siswa.php`

```php
class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'nis', 'nama', 'kelas_id'];

    // Model Event: Auto-generate NIS saat creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($siswa) {
            if (empty($siswa->nis)) {
                $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' 
                    . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relasi: Siswa belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Siswa has many Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    // Method OOP: Lihat Profil
    public function lihatProfil()
    {
        return $this->nis . ' - ' . $this->nama . ' - ' . $this->kelas;
    }
}
```

**Atribut:** `user_id`, `nis`, `nama`, `kelas`  
**Method:** `user()`, `nilais()`, `lihatProfil()`

### 9.2 Class `Guru`

File: `app/Models/Guru.php`

```php
class Guru extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'id_guru', 'nama_guru'];

    // Model Event: Auto-generate ID Guru saat creating
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($guru) {
            if (empty($guru->id_guru)) {
                $guru->id_guru = 'GR-' . now()->format('Ymd') . '-' 
                    . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi Many-to-Many ke MataPelajaran
    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel', 'guru_id', 'mapel_id')
                    ->withTimestamps();
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
```

**Atribut:** `user_id`, `id_guru`, `nama_guru`  
**Method:** `user()`, `mataPelajarans()`, `nilais()`

### 9.3 Class `MataPelajaran`

File: `app/Models/MataPelajaran.php`

```php
class MataPelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['kode_mapel', 'nama_mapel'];

    // Relasi Many-to-Many ke Guru
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'mapel_id', 'guru_id')
                    ->withTimestamps();
    }

    // Relasi One-to-Many ke Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}
```

**Atribut:** `kode_mapel`, `nama_mapel`  
**Method:** `gurus()`, `nilais()`

### 9.4 Class `Nilai`

File: `app/Models/Nilai.php`

```php
class Nilai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'siswa_id', 'guru_id', 'mapel_id', 'semester',
        'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'status'
    ];

    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts'   => 'decimal:2',
        'nilai_uas'   => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    // Model Event: Auto-compute saat saving
    protected static function booted(): void
    {
        static::saving(function (Nilai $nilai) {
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
            $nilai->status = $nilai->tentukanStatus();
        });
    }

    // Relasi
    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class, 'mapel_id'); }

    // Method OOP: Hitung Nilai Akhir
    public function hitungNilaiAkhir(): float
    {
        return ($this->nilai_tugas * 0.30) + ($this->nilai_uts * 0.30) + ($this->nilai_uas * 0.40);
    }

    // Method OOP: Tentukan Status Kelulusan
    public function tentukanStatus(): string
    {
        return $this->hitungNilaiAkhir() >= 70 ? 'Lulus' : 'Tidak Lulus';
    }
}
```

**Atribut:** `siswa_id`, `guru_id`, `mapel_id`, `semester`, `nilai_tugas`, `nilai_uts`, `nilai_uas`, `nilai_akhir`, `status`  
**Method:** `siswa()`, `guru()`, `mataPelajaran()`, `hitungNilaiAkhir()`, `tentukanStatus()`

### 9.5 Class `User`

File: `app/Models/User.php`

```php
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, HasPanelShield;

    // Relasi One-to-One ke Siswa
    public function siswa() { return $this->hasOne(Siswa::class); }

    // Relasi One-to-One ke Guru
    public function guru() { return $this->hasOne(Guru::class); }

    // Helper methods
    public function isAdmin(): bool { return $this->hasRole(['admin', 'super_admin']); }
    public function isGuru(): bool { return $this->hasRole('guru'); }
    public function isSiswa(): bool { return $this->hasRole('siswa'); }

    public function canAccessPanel(Panel $panel): bool { return true; }
}
```

**Atribut:** `name`, `email`, `password`  
**Method:** `siswa()`, `guru()`, `isAdmin()`, `isGuru()`, `isSiswa()`, `canAccessPanel()`

---

## 10. Penjelasan Library atau Komponen yang Digunakan

### 10.1 Framework & Library Utama

| No | Library | Versi | Fungsi |
|----|---------|-------|--------|
| 1 | **Laravel** | ^13.8 | Framework utama PHP untuk MVC, Eloquent ORM, routing, middleware, migrasi |
| 2 | **Filament** | ~5.0 | Admin panel berbasis Livewire, menyediakan CRUD builder, form builder, table builder, pages, widgets |
| 3 | **Spatie Laravel Permission** | (via Filament Shield) | Manajemen role & permission untuk autentikasi berbasis role (RBAC) |
| 4 | **Filament Shield** | ^4.2 | Integrasi Filament dengan Spatie Permission, auto-generate permission untuk tiap resource |
| 5 | **Maatwebsite Excel** | ^3.1 | Export data ke format Excel (.xlsx) untuk fitur export laporan nilai |
| 6 | **pxlrbt Filament Excel** | ^3.6 | Integrasi Maatwebsite Excel dengan Filament untuk bulk export dari tabel |
| 7 | **Laravel Tinker** | ^3.0 | REPL interaktif untuk debugging dan testing query Eloquent |

### 10.2 Library Development

| No | Library | Versi | Fungsi |
|----|---------|-------|--------|
| 1 | **FakerPHP** | ^1.23 | Generator data palsu untuk seeder dan factory |
| 2 | **Laravel Pint** | ^1.27 | Code formatter/linter untuk PHP (PSR-12) |
| 3 | **PHPUnit** | ^12.5 | Framework testing untuk unit test dan feature test |
| 4 | **Laravel Pail** | ^1.2.5 | Log viewer real-time di terminal |
| 5 | **Mockery** | ^1.6 | Library mocking untuk testing |

### 10.3 Komponen Filament yang Digunakan

| No | Komponen | Kegunaan |
|----|----------|----------|
| 1 | `Resource` | Base class untuk CRUD resource (SiswaResource, GuruResource, NilaiResource, dll.) |
| 2 | `Schema / Form` | Form builder dengan field Select, TextInput, Section |
| 3 | `Table` | Table builder dengan TextColumn, SelectFilter, BulkAction |
| 4 | `Page` | Custom page untuk Laporan Nilai |
| 5 | `Widget` | StatsOverviewWidget untuk dashboard statistik, ChartWidget untuk grafik |
| 6 | `Infolist` | Detail view untuk record (NilaiInfolist, SiswaInfolist) |
| 7 | `HasPageShield` | Trait untuk kontrol akses halaman berdasarkan permission |

### 10.4 Frontend

| No | Teknologi | Fungsi |
|----|-----------|--------|
| 1 | **Blade** | Template engine Laravel untuk view |
| 2 | **Livewire** | Framework reactive untuk interaksi realtime tanpa reload (digunakan oleh Filament) |
| 3 | **Vite** | Build tool untuk asset frontend (CSS, JS) |
| 4 | **Alpine.js** | Framework JS ringan untuk interaktivitas (digunakan oleh Filament) |

---

## 11. Penjelasan Coding Guidelines dan Best Practices

### 11.1 Struktur Kode (Separation of Concerns)

Proyek ini menerapkan pemisahan tanggung jawab (Separation of Concerns) yang jelas:

```
app/
├── Models/           → Eloquent Model (data layer, relasi, business logic)
├── Filament/
│   ├── Resources/    → CRUD Resource (UI layer)
│   │   ├── Schemas/  → Form & Infolist schema
│   │   ├── Tables/   → Table schema
│   │   └── Pages/    → CRUD pages (List, Create, Edit, View)
│   ├── Pages/        → Custom pages (LaporanNilai)
│   └── Widgets/      → Dashboard widgets
├── Exports/          → Export logic (Excel)
├── Helpers/          → Helper functions (prosedural)
├── Policies/         → Authorization policies
└── Providers/        → Service providers
```

### 11.2 Naming Conventions

| Elemen | Konvensi | Contoh |
|--------|----------|--------|
| Model | Singular, PascalCase | `Siswa`, `Nilai`, `MataPelajaran` |
| Migration | Snake_case, deskriptif | `create_siswas_table`, `add_semester_to_nilais_table` |
| Table | Plural, snake_case | `siswas`, `gurus`, `nilais`, `guru_mapel` |
| Foreign Key | `{model}_id` | `siswa_id`, `guru_id`, `mapel_id` |
| Method | camelCase | `hitungNilaiAkhir()`, `tentukanStatus()` |
| Helper Function | camelCase | `validasiNilai()`, `hitungNilaiAkhirProsedural()` |
| Resource | PascalCase + "Resource" | `SiswaResource`, `NilaiResource` |

### 11.3 Best Practices yang Diterapkan

#### 1. **Eloquent ORM & Relationship**
- Menggunakan Eloquent ORM untuk semua interaksi database
- Mendefinisikan relasi (belongsTo, hasMany, belongsToMany) dengan jelas di model
- Menggunakan eager loading (`with()`) untuk menghindari N+1 query problem

#### 2. **Model Events**
- Menggunakan `creating` event untuk auto-generate NIS dan ID Guru
- Menggunakan `saving` event untuk auto-compute nilai_akhir dan status

#### 3. **Soft Deletes**
- Semua model utama menggunakan `SoftDeletes` trait
- Data yang dihapus tidak benar-benar hilang, bisa di-restore

#### 4. **Authorization (RBAC)**
- Menggunakan Spatie Permission untuk Role-Based Access Control
- Policy class per model untuk fine-grained permission
- Shield trait untuk kontrol akses per halaman

#### 5. **Validasi Input**
- Validasi di level form (required, numeric, min/max)
- Validasi di level helper function (`validasiNilai()`)
- Validasi di level database (unique constraint, foreign key)

#### 6. **DRY (Don't Repeat Yourself)**
- Helper functions digunakan di beberapa tempat (form dan seeder)
- Logika perhitungan terpusat di model (OOP) dan helper (prosedural)
- Schema form dan table dipisah ke class tersendiri agar reusable

#### 7. **Data Integrity**
- Foreign key constraints dengan cascade delete
- Unique constraints pada NIS, ID Guru, email, kode_mapel
- Unique constraint pada pivot `guru_mapel` (`[guru_id, mapel_id]`)

#### 8. **Security**
- Password di-hash menggunakan `Hash::make()` (bcrypt)
- Casting `password => hashed` di model User
- Role-based query scoping pada NilaiResource (`getEloquentQuery()`)
- Export route dilindungi pengecekan `auth()->check()` dan role

#### 9. **Dokumentasi Kode**
- Docblock pada setiap class dan method penting
- Komentar inline untuk logika yang kompleks
- PHPDoc `@param` dan `@return` type hints

#### 10. **Type Safety**
- Type casting pada model (`decimal:2`)
- Return type declarations (`float`, `string`, `bool`, `array`)
- PHP 8.3 modern syntax (null-safe operator `?->`, named arguments)
