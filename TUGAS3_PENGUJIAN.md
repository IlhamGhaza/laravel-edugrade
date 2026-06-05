# TUGAS 3 — PENGUJIAN DAN DOKUMENTASI PROGRAM

## Aplikasi Pengolahan Nilai Siswa "EduGrade"

**Nama Aplikasi:** EduGrade  
**Framework:** Laravel 13 + Filament 5  
**Bahasa Pemrograman:** PHP 8.3  
**Metode Pengujian:** Black Box Testing  
**Tanggal:** Juni 2026  

---

## Daftar Isi

1. [Dokumentasi Tahapan Pengujian Aplikasi](#1-dokumentasi-tahapan-pengujian-aplikasi)
2. [Skenario dan Test Case Pengujian](#2-skenario-dan-test-case-pengujian)
3. [Hasil Pengujian Aplikasi](#3-hasil-pengujian-aplikasi)
4. [Bukti Pengujian Berhasil/Gagal](#4-bukti-pengujian-berhasilgagal)
5. [Dokumentasi Debugging](#5-dokumentasi-debugging)
6. [Dokumentasi Kode Program](#6-dokumentasi-kode-program)
7. [Penjelasan Fungsi, Modul, dan Class](#7-penjelasan-fungsi-modul-dan-class)
8. [Evaluasi Hasil Pengujian Aplikasi](#8-evaluasi-hasil-pengujian-aplikasi)

---

## 1. Dokumentasi Tahapan Pengujian Aplikasi

### 1.1 Metode Pengujian

Pengujian dilakukan menggunakan metode **Black Box Testing**, yaitu pengujian fungsionalitas aplikasi tanpa melihat struktur internal kode. Fokus pengujian ada pada input, proses, dan output yang dihasilkan oleh sistem.

### 1.2 Tahapan Pengujian

| Tahap | Kegiatan | Keterangan |
|-------|----------|------------|
| 1 | Persiapan Lingkungan | Setup database, migrasi, seeder data demo |
| 2 | Pengujian Login & Autentikasi | Uji login dengan berbagai role (admin, guru, siswa) |
| 3 | Pengujian CRUD Data | Uji Create, Read, Update, Delete pada setiap resource |
| 4 | Pengujian Validasi Input | Uji input nilai dengan data valid dan tidak valid |
| 5 | Pengujian Perhitungan Nilai Akhir | Verifikasi rumus 30% Tugas + 30% UTS + 40% UAS |
| 6 | Pengujian Laporan | Uji tampilan laporan, filter, dan export |
| 7 | Pengujian Hak Akses | Verifikasi setiap role hanya bisa mengakses fitur sesuai haknya |
| 8 | Pengujian Koneksi Database | Verifikasi relasi, constraint, dan integritas data |

### 1.3 Lingkungan Pengujian

| Komponen | Spesifikasi |
|----------|-------------|
| OS | Windows |
| PHP | 8.3 |
| Laravel | 13.x |
| Database | MySQL |
| Browser | Chrome / Edge |
| Server | `php artisan serve` (localhost:8000) |

---

## 2. Skenario dan Test Case Pengujian

### 2.1 Pengujian Login

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-L01 | Login Admin berhasil | 1. Buka halaman `/admin/login` <br> 2. Masukkan email: `ilham@admin.com` <br> 3. Masukkan password: `12345678` <br> 4. Klik tombol Login | User berhasil login dan diarahkan ke dashboard admin dengan statistik lengkap (Total Siswa, Total Guru, Rata-rata Nilai, Total Data Nilai) | ✅ Berhasil |
| TC-L02 | Login Guru berhasil | 1. Buka halaman `/admin/login` <br> 2. Masukkan email: `guru1@edugrade.com` <br> 3. Masukkan password: `password` <br> 4. Klik tombol Login | User berhasil login dan diarahkan ke dashboard guru dengan statistik (Mata Pelajaran yang diampu, Total Nilai, Rata-rata) | ✅ Berhasil |
| TC-L03 | Login Siswa berhasil | 1. Buka halaman `/admin/login` <br> 2. Masukkan email: `siswa1@edugrade.com` <br> 3. Masukkan password: `password` <br> 4. Klik tombol Login | User berhasil login dan diarahkan ke dashboard siswa dengan statistik (Kelas, NIS, Rata-rata Nilai, Total Mapel) | ✅ Berhasil |
| TC-L04 | Login dengan password salah | 1. Buka halaman `/admin/login` <br> 2. Masukkan email: `ilham@admin.com` <br> 3. Masukkan password: `salah123` <br> 4. Klik tombol Login | Muncul pesan error "These credentials do not match our records." dan user tetap di halaman login | ✅ Berhasil |
| TC-L05 | Login dengan email tidak terdaftar | 1. Buka halaman `/admin/login` <br> 2. Masukkan email: `tidakada@test.com` <br> 3. Masukkan password: `password` <br> 4. Klik tombol Login | Muncul pesan error validasi dan user tetap di halaman login | ✅ Berhasil |
| TC-L06 | Login dengan field kosong | 1. Buka halaman `/admin/login` <br> 2. Kosongkan field email dan password <br> 3. Klik tombol Login | Muncul pesan validasi "field wajib diisi" untuk email dan password | ✅ Berhasil |
| TC-L07 | Logout dari sistem | 1. Login sebagai admin <br> 2. Klik avatar/profil di kanan atas <br> 3. Klik "Sign out" | User berhasil logout dan diarahkan ke halaman login | ✅ Berhasil |

### 2.2 Pengujian Input Data Siswa

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-S01 | Tambah siswa baru berhasil | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Klik tombol "New Siswa" <br> 4. Pilih User: (user dengan role siswa) <br> 5. Isi Nama: "Test Siswa" <br> 6. Pilih Kelas: "10" <br> 7. Klik tombol "Create" | Data siswa tersimpan, NIS otomatis di-generate dengan format `NIS-YYYYMMDD-XXXXX`, diarahkan ke halaman list siswa | ✅ Berhasil |
| TC-S02 | Tambah siswa tanpa mengisi nama | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa > New Siswa <br> 3. Pilih User <br> 4. Kosongkan Nama <br> 5. Pilih Kelas <br> 6. Klik "Create" | Muncul pesan validasi error "Nama wajib diisi", data tidak tersimpan | ✅ Berhasil |
| TC-S03 | Tambah siswa tanpa memilih user | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa > New Siswa <br> 3. Tidak memilih User <br> 4. Isi Nama dan Kelas <br> 5. Klik "Create" | Muncul pesan validasi error "User wajib dipilih", data tidak tersimpan | ✅ Berhasil |
| TC-S04 | Edit data siswa | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Klik ikon Edit pada salah satu siswa <br> 4. Ubah Nama menjadi "Nama Baru" <br> 5. Klik "Save" | Data siswa berhasil diperbarui dengan nama baru, NIS tidak berubah | ✅ Berhasil |
| TC-S05 | Hapus data siswa (soft delete) | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Klik ikon Delete pada salah satu siswa <br> 4. Konfirmasi penghapusan | Data siswa di-soft delete, tidak muncul di list tapi masih ada di database | ✅ Berhasil |
| TC-S06 | Lihat detail siswa | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Klik ikon View pada salah satu siswa | Halaman detail menampilkan NIS, Nama, Kelas, dan User yang terhubung | ✅ Berhasil |
| TC-S07 | Cari siswa berdasarkan nama | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Ketik nama siswa di kolom search | Tabel menampilkan hanya siswa yang namanya cocok dengan kata kunci | ✅ Berhasil |
| TC-S08 | Filter siswa berdasarkan kelas | 1. Login sebagai admin <br> 2. Navigasi ke menu Siswa <br> 3. Klik filter Kelas <br> 4. Pilih kelas tertentu | Tabel menampilkan hanya siswa dari kelas yang dipilih | ✅ Berhasil |

### 2.3 Pengujian Input Data Guru

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-G01 | Tambah guru baru berhasil | 1. Login sebagai admin <br> 2. Navigasi ke menu Guru <br> 3. Klik "New Guru" <br> 4. Pilih Akun User (role guru) <br> 5. Isi Nama Guru <br> 6. Pilih Mata Pelajaran <br> 7. Klik "Create" | Data guru tersimpan, ID Guru otomatis di-generate dengan format `GR-YYYYMMDD-XXXXX` | ✅ Berhasil |
| TC-G02 | Tambah guru dengan multiple mata pelajaran | 1. Login sebagai admin <br> 2. Navigasi ke menu Guru > New Guru <br> 3. Pilih User, Isi Nama <br> 4. Pilih 2 atau lebih mata pelajaran <br> 5. Klik "Create" | Guru tersimpan dengan relasi ke multiple mata pelajaran di tabel pivot guru_mapel | ✅ Berhasil |
| TC-G03 | Tambah guru tanpa memilih user | 1. Login sebagai admin <br> 2. Navigasi ke Guru > New Guru <br> 3. Tidak memilih User <br> 4. Isi field lainnya <br> 5. Klik "Create" | Muncul pesan validasi error "Akun User wajib dipilih" | ✅ Berhasil |
| TC-G04 | Edit data guru | 1. Login sebagai admin <br> 2. Navigasi ke menu Guru <br> 3. Klik Edit pada guru <br> 4. Ubah nama atau mata pelajaran <br> 5. Klik "Save" | Data guru berhasil diperbarui | ✅ Berhasil |
| TC-G05 | Hapus data guru | 1. Login sebagai admin <br> 2. Navigasi ke menu Guru <br> 3. Klik Delete pada guru <br> 4. Konfirmasi | Data guru di-soft delete | ✅ Berhasil |

### 2.4 Pengujian Input Data Mata Pelajaran

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-M01 | Tambah mata pelajaran baru | 1. Login sebagai admin <br> 2. Navigasi ke menu Mata Pelajaran <br> 3. Klik "New Mata Pelajaran" <br> 4. Isi Kode Mapel: "FIS" <br> 5. Isi Nama Mapel: "Fisika" <br> 6. Klik "Create" | Data mata pelajaran tersimpan ke database | ✅ Berhasil |
| TC-M02 | Tambah mapel dengan kode duplikat | 1. Login sebagai admin <br> 2. Navigasi ke Mata Pelajaran > New <br> 3. Isi Kode Mapel: "MTK" (sudah ada) <br> 4. Isi Nama Mapel <br> 5. Klik "Create" | Muncul error karena kode_mapel harus unik (unique constraint violation) | ✅ Berhasil |
| TC-M03 | Edit mata pelajaran | 1. Login sebagai admin <br> 2. Navigasi ke Mata Pelajaran <br> 3. Edit salah satu mapel <br> 4. Ubah Nama Mapel <br> 5. Klik "Save" | Data mata pelajaran berhasil diperbarui | ✅ Berhasil |

### 2.5 Pengujian Input Nilai

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-N01 | Input nilai berhasil (lulus) | 1. Login sebagai guru atau admin <br> 2. Navigasi ke menu Nilai Siswa <br> 3. Klik "New Nilai" <br> 4. Pilih Guru (otomatis jika login guru) <br> 5. Pilih Mata Pelajaran <br> 6. Pilih Semester: "Ganjil" <br> 7. Pilih Siswa <br> 8. Isi Tugas: 80, UTS: 75, UAS: 85 <br> 9. Klik "Create" | Data nilai tersimpan. Nilai Akhir otomatis dihitung: (80×0.3)+(75×0.3)+(85×0.4) = 24+22.5+34 = **80.50**. Status: **Lulus** | ✅ Berhasil |
| TC-N02 | Input nilai berhasil (tidak lulus) | 1. Login sebagai guru <br> 2. Navigasi ke Nilai Siswa > New Nilai <br> 3. Pilih data relasi <br> 4. Isi Tugas: 50, UTS: 45, UAS: 55 <br> 5. Klik "Create" | Nilai Akhir: (50×0.3)+(45×0.3)+(55×0.4) = 15+13.5+22 = **50.50**. Status: **Tidak Lulus** | ✅ Berhasil |
| TC-N03 | Input nilai batas lulus (nilai akhir = 70) | 1. Login sebagai guru <br> 2. Input nilai Tugas: 70, UTS: 70, UAS: 70 <br> 3. Klik "Create" | Nilai Akhir: (70×0.3)+(70×0.3)+(70×0.4) = 21+21+28 = **70.00**. Status: **Lulus** (karena ≥ 70) | ✅ Berhasil |
| TC-N04 | Input nilai batas tidak lulus (nilai akhir = 69.9) | 1. Login sebagai guru <br> 2. Input nilai Tugas: 69, UTS: 70, UAS: 70 <br> 3. Klik "Create" | Nilai Akhir: (69×0.3)+(70×0.3)+(70×0.4) = 20.7+21+28 = **69.70**. Status: **Tidak Lulus** | ✅ Berhasil |
| TC-N05 | Input nilai di luar rentang (> 100) | 1. Login sebagai guru <br> 2. Input Tugas: 150 <br> 3. Coba submit | Muncul validasi error "Nilai tidak boleh lebih dari 100". Data tidak tersimpan | ✅ Berhasil |
| TC-N06 | Input nilai negatif (< 0) | 1. Login sebagai guru <br> 2. Input Tugas: -10 <br> 3. Coba submit | Muncul validasi error "Nilai tidak boleh kurang dari 0". Data tidak tersimpan | ✅ Berhasil |
| TC-N07 | Input nilai kosong | 1. Login sebagai guru <br> 2. Kosongkan semua field nilai <br> 3. Klik "Create" | Muncul validasi error "Nilai wajib diisi" untuk setiap field | ✅ Berhasil |
| TC-N08 | Input nilai non-numeric | 1. Login sebagai guru <br> 2. Input Tugas: "abc" <br> 3. Coba submit | Muncul validasi error "Harus berupa angka" | ✅ Berhasil |
| TC-N09 | Edit nilai yang sudah ada | 1. Login sebagai guru <br> 2. Navigasi ke Nilai Siswa <br> 3. Klik Edit pada salah satu nilai <br> 4. Ubah Nilai Tugas dari 85 menjadi 90 <br> 5. Klik "Save" | Nilai berhasil diperbarui, Nilai Akhir dan Status dihitung ulang otomatis | ✅ Berhasil |
| TC-N10 | Hapus data nilai | 1. Login sebagai guru/admin <br> 2. Navigasi ke Nilai Siswa <br> 3. Klik Hapus pada salah satu nilai <br> 4. Konfirmasi | Data nilai berhasil di-soft delete | ✅ Berhasil |
| TC-N11 | Cascade select guru → mapel | 1. Login sebagai admin <br> 2. Buat Nilai baru <br> 3. Pilih Guru "Budi Santoso" | Dropdown Mata Pelajaran hanya menampilkan "Matematika" dan "IPA" (mapel yang diampu Budi Santoso) | ✅ Berhasil |
| TC-N12 | Siswa yang sudah dinilai tidak muncul di dropdown | 1. Login sebagai guru <br> 2. Buat Nilai baru <br> 3. Pilih Guru, Mapel, dan Semester yang sama dengan nilai yang sudah ada | Dropdown Siswa tidak menampilkan siswa yang sudah dinilai untuk kombinasi tersebut | ✅ Berhasil |
| TC-N13 | Guru hanya bisa input nilai atas namanya sendiri | 1. Login sebagai guru (guru1@edugrade.com) <br> 2. Buat Nilai baru <br> 3. Cek field Guru | Field Guru otomatis terisi "Budi Santoso" dan di-disable (tidak bisa diubah) | ✅ Berhasil |
| TC-N14 | Dependent dropdown Kelas -> Siswa | 1. Login sebagai guru <br> 2. Buat Nilai baru <br> 3. Pilih Kelas tertentu | Dropdown Siswa hanya menampilkan siswa yang terdaftar pada kelas yang dipilih | ✅ Berhasil |

### 2.6 Pengujian Perhitungan Nilai Akhir

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-H01 | Perhitungan dengan nilai sempurna | Input: Tugas=100, UTS=100, UAS=100 | Nilai Akhir = (100×0.3)+(100×0.3)+(100×0.4) = 30+30+40 = **100.00**, Status: **Lulus** | ✅ Berhasil |
| TC-H02 | Perhitungan dengan nilai nol | Input: Tugas=0, UTS=0, UAS=0 | Nilai Akhir = (0×0.3)+(0×0.3)+(0×0.4) = 0+0+0 = **0.00**, Status: **Tidak Lulus** | ✅ Berhasil |
| TC-H03 | Perhitungan dengan nilai campuran | Input: Tugas=85, UTS=78, UAS=82 | Nilai Akhir = (85×0.3)+(78×0.3)+(82×0.4) = 25.5+23.4+32.8 = **81.70**, Status: **Lulus** | ✅ Berhasil |
| TC-H04 | Perhitungan tepat batas lulus | Input: Tugas=70, UTS=70, UAS=70 | Nilai Akhir = **70.00**, Status: **Lulus** | ✅ Berhasil |
| TC-H05 | Perhitungan di bawah batas lulus | Input: Tugas=60, UTS=55, UAS=65 | Nilai Akhir = (60×0.3)+(55×0.3)+(65×0.4) = 18+16.5+26 = **60.50**, Status: **Tidak Lulus** | ✅ Berhasil |
| TC-H06 | Perhitungan via Model Event (seeder) | Jalankan `php artisan db:seed` dan cek record di tabel `nilais` | Field `nilai_akhir` dan `status` otomatis terisi melalui model event `saving` | ✅ Berhasil |
| TC-H07 | Realtime calculation di form | 1. Buka form input nilai <br> 2. Isi Tugas: 80 <br> 3. Pindah ke field UTS (blur) <br> 4. Cek preview nilai akhir | Nilai akhir dihitung dan ditampilkan secara realtime saat berpindah field | ✅ Berhasil |

### 2.7 Pengujian Laporan

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-R01 | Tampilan laporan admin | 1. Login sebagai admin <br> 2. Navigasi ke menu Laporan Nilai | Tampilan menunjukkan statistik (Total Data, Rata-rata), filter (Nama, Kelas, Mapel, Semester, Status), dan tabel lengkap semua nilai | ✅ Berhasil |
| TC-R02 | Filter laporan berdasarkan kelas | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Pilih Filter Kelas: "XII-IPA-1" | Tabel hanya menampilkan nilai siswa dari kelas XII-IPA-1 | ✅ Berhasil |
| TC-R03 | Filter laporan berdasarkan mata pelajaran | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Pilih Filter Mapel: "Matematika" | Tabel hanya menampilkan nilai mata pelajaran Matematika | ✅ Berhasil |
| TC-R04 | Filter laporan berdasarkan semester | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Pilih Filter Semester: "Ganjil" | Tabel hanya menampilkan nilai semester Ganjil | ✅ Berhasil |
| TC-R05 | Filter laporan berdasarkan status | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Pilih Filter Status: "Tidak Lulus" | Tabel hanya menampilkan siswa dengan status Tidak Lulus | ✅ Berhasil |
| TC-R06 | Cari siswa di laporan | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Ketik "Andi" di kolom pencarian | Tabel hanya menampilkan nilai siswa bernama "Andi Prasetyo" | ✅ Berhasil |
| TC-R07 | Reset filter laporan | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Terapkan beberapa filter <br> 4. Klik tombol "Reset" | Semua filter direset, tabel menampilkan semua data nilai | ✅ Berhasil |
| TC-R08 | Export laporan ke Excel | 1. Login sebagai admin <br> 2. Buka Laporan Nilai <br> 3. Klik tombol "Export Excel" | File `.xlsx` ter-download berisi data sesuai filter yang aktif dengan kolom: NIS, Nama, Kelas, Semester, Guru, Mapel, Tugas, UTS, UAS, Nilai Akhir, Status | ✅ Berhasil |
| TC-R09 | Laporan guru (hanya nilai yang dia input) | 1. Login sebagai guru (guru1@edugrade.com) <br> 2. Navigasi ke Laporan Nilai | Hanya menampilkan nilai yang diinput oleh Budi Santoso (MTK dan IPA) | ✅ Berhasil |
| TC-R10 | Laporan siswa (hanya nilai pribadi) | 1. Login sebagai siswa (siswa1@edugrade.com) <br> 2. Navigasi ke Laporan Nilai | Hanya menampilkan nilai milik Andi Prasetyo | ✅ Berhasil |
| TC-R11 | Laporan kosong (siswa belum ada nilai) | 1. Login sebagai siswa baru (belum punya nilai) <br> 2. Navigasi ke Laporan Nilai | Menampilkan empty state: "Belum ada data nilai" | ✅ Berhasil |

### 2.8 Pengujian Hak Akses (Authorization)

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-A01 | Admin dapat akses semua menu | 1. Login sebagai admin <br> 2. Cek sidebar navigasi | Menampilkan semua menu: Pengguna, Mata Pelajaran, Guru, Siswa, Nilai Siswa, Laporan Nilai | ✅ Berhasil |
| TC-A02 | Guru hanya melihat menu yang diizinkan | 1. Login sebagai guru <br> 2. Cek sidebar navigasi | Menampilkan menu: Siswa (view only), Guru (view only), Mata Pelajaran (view only), Nilai Siswa, Laporan Nilai. Tidak menampilkan menu Pengguna | ✅ Berhasil |
| TC-A03 | Siswa hanya melihat menu Laporan Nilai | 1. Login sebagai siswa <br> 2. Cek sidebar navigasi | Hanya menampilkan menu: Laporan Nilai. Tidak ada menu CRUD lainnya | ✅ Berhasil |
| TC-A04 | Guru tidak bisa CRUD Siswa | 1. Login sebagai guru <br> 2. Buka menu Siswa | Guru hanya bisa melihat (View) data siswa, tidak ada tombol Create/Edit/Delete | ✅ Berhasil |
| TC-A05 | Guru hanya lihat nilai yang dia input | 1. Login sebagai guru1 (Budi Santoso) <br> 2. Buka Nilai Siswa | Hanya menampilkan nilai yang guru_id-nya milik Budi Santoso | ✅ Berhasil |
| TC-A06 | Siswa tidak bisa akses halaman Nilai resource | 1. Login sebagai siswa <br> 2. Coba akses URL `/admin/nilais` | Halaman tidak dapat diakses (403 Forbidden atau redirect) | ✅ Berhasil |
| TC-A07 | Non-login user diarahkan ke halaman login | 1. Buka URL `/admin` tanpa login | Otomatis diarahkan ke halaman `/admin/login` | ✅ Berhasil |
| TC-A08 | Export Excel hanya untuk admin | 1. Login sebagai guru <br> 2. Coba akses route `/export-laporan-nilai` | Mengembalikan response 403 Forbidden | ✅ Berhasil |

### 2.9 Pengujian Koneksi Database

| TC-ID | Nama Case Test | Langkah Uji | Hasil yang Diharapkan | Status |
|-------|---------------|-------------|----------------------|--------|
| TC-D01 | Migrasi database berhasil | 1. Jalankan `php artisan migrate` | Semua tabel berhasil dibuat: users, siswas, gurus, mata_pelajarans, guru_mapel, nilais, dll. | ✅ Berhasil |
| TC-D02 | Seeder database berhasil | 1. Jalankan `php artisan db:seed` | Data demo berhasil di-insert: 3 mapel, 1 admin, 2 guru, 3 siswa, 6 nilai | ✅ Berhasil |
| TC-D03 | Foreign key constraint berfungsi | 1. Coba hapus user yang memiliki siswa terkait tanpa cascade | Data siswa ikut terhapus karena cascadeOnDelete | ✅ Berhasil |
| TC-D04 | Unique constraint NIS | 1. Coba insert siswa dengan NIS yang sudah ada | Muncul error constraint violation, data tidak tersimpan | ✅ Berhasil |
| TC-D05 | Unique constraint kode_mapel | 1. Coba insert mata pelajaran dengan kode yang sudah ada | Muncul error constraint violation, data tidak tersimpan | ✅ Berhasil |
| TC-D06 | Unique constraint guru_mapel | 1. Coba attach guru ke mapel yang sudah di-attach | Muncul error karena unique constraint `[guru_id, mapel_id]` | ✅ Berhasil |
| TC-D07 | Soft delete berfungsi | 1. Hapus siswa via Filament <br> 2. Cek tabel siswas di database | Field `deleted_at` terisi timestamp, record masih ada di database | ✅ Berhasil |
| TC-D08 | Restore soft-deleted record | 1. Aktifkan filter "Trashed" <br> 2. Pilih record yang dihapus <br> 3. Klik "Restore" | Record dikembalikan, `deleted_at` menjadi null | ✅ Berhasil |

---

## 3. Hasil Pengujian Aplikasi

### 3.1 Ringkasan Hasil Pengujian

| Modul Pengujian | Jumlah Test Case | Berhasil | Gagal | Persentase |
|----------------|-----------------|----------|-------|------------|
| Login & Autentikasi | 7 | 7 | 0 | 100% |
| Input Data Siswa | 8 | 8 | 0 | 100% |
| Input Data Guru | 5 | 5 | 0 | 100% |
| Input Data Mata Pelajaran | 3 | 3 | 0 | 100% |
| Input Nilai | 13 | 13 | 0 | 100% |
| Perhitungan Nilai Akhir | 7 | 7 | 0 | 100% |
| Laporan | 11 | 11 | 0 | 100% |
| Hak Akses | 8 | 8 | 0 | 100% |
| Koneksi Database | 8 | 8 | 0 | 100% |
| **TOTAL** | **70** | **70** | **0** | **100%** |

### 3.2 Kesimpulan Hasil Pengujian

Seluruh **70 test case** yang diujikan menghasilkan status **BERHASIL (Pass)**. Tidak ditemukan kegagalan pada saat pengujian dilakukan. Semua fitur berjalan sesuai dengan kebutuhan yang telah didefinisikan.

---

## 4. Bukti Pengujian Berhasil/Gagal

### 4.1 Bukti Login Berdasarkan Role

**Login sebagai Admin:**

[gambar]

**Login sebagai Guru:**

[gambar]

**Login sebagai Siswa:**

[gambar]

**Login Gagal (password salah):**

[gambar]

### 4.2 Bukti Input Data Siswa

**Form Tambah Siswa:**

[gambar]

**List Data Siswa:**

[gambar]

### 4.3 Bukti Input Data Guru

**Form Tambah Guru dengan Multiple Mata Pelajaran:**

[gambar]

### 4.4 Bukti Input Nilai

**Form Input Nilai (Guru Login):**

[gambar]

**Tabel Nilai dengan Status Berwarna:**

[gambar]

**Detail View Nilai:**

[gambar]

### 4.5 Bukti Perhitungan Nilai Akhir

**Perhitungan Realtime di Form:**

[gambar]

**Nilai Akhir Tersimpan di Database:**

[gambar]

### 4.6 Bukti Laporan Nilai

**Laporan Admin (dengan Filter):**

[gambar]

**Laporan Guru (hanya nilainya):**

[gambar]

**Laporan Siswa (hanya nilai pribadi):**

[gambar]

### 4.7 Bukti Dashboard per Role

**Dashboard Admin:**

[gambar]

**Dashboard Guru:**

[gambar]

**Dashboard Siswa:**

[gambar]

### 4.8 Bukti Export Excel

**File Excel Hasil Export:**

[gambar]

### 4.9 Bukti Koneksi Database

**Migrasi Berhasil:**

[gambar]

**Seeder Berhasil:**

[gambar]

---

## 5. Dokumentasi Debugging

### 5.1 Bug #1: NIS Duplikat pada Soft Delete

**Gejala:**  
Saat menambahkan siswa baru setelah menghapus siswa sebelumnya, muncul error:
```
SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'NIS-20260603-00003' for key 'siswas_nis_unique'
```

**Penyebab:**  
Method `count()` pada Eloquent secara default tidak menghitung record yang di-soft-delete, sehingga nomor urut NIS bisa sama.

**Solusi:**
```diff
- $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' . str_pad(static::count() + 1, 5, '0', STR_PAD_LEFT);
+ $siswa->nis = 'NIS-' . now()->format('Ymd') . '-' . str_pad(static::withTrashed()->count() + 1, 5, '0', STR_PAD_LEFT);
```

**Hasil:** NIS selalu unik karena menghitung semua record termasuk yang dihapus.

[gambar]

### 5.2 Bug #2: Null Reference pada Guru tanpa Profil

**Gejala:**  
Error saat guru login tetapi belum memiliki data profil di tabel `gurus`:
```
Trying to get property 'id' of non-object
```

**Penyebab:**  
`Guru::where('user_id', $user->id)->first()` mengembalikan `null`, lalu `$guru->id` mengakses property dari null.

**Solusi:**
```php
if ($guru) {
    $query->where('guru_id', $guru->id);
} else {
    $query->whereRaw('1 = 0'); // Force empty result
}
```

**Hasil:** Guru tanpa profil tidak error, hanya menampilkan data kosong.

### 5.3 Bug #3: Nilai Akhir dan Status null dari Seeder

**Gejala:**  
Data nilai yang dimasukkan melalui `DatabaseSeeder` memiliki `nilai_akhir = null` dan `status = null` karena perhitungan hanya terjadi di form Filament.

**Penyebab:**  
Tidak ada perhitungan otomatis di level model, hanya di level form.

**Solusi:**  
Menambahkan model event `saving` pada model `Nilai`:
```php
protected static function booted(): void
{
    static::saving(function (Nilai $nilai) {
        $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
        $nilai->status = $nilai->tentukanStatus();
    });
}
```

**Hasil:** Semua cara memasukkan data (form, seeder, tinker) selalu menghasilkan `nilai_akhir` dan `status` yang benar.

---

## 6. Dokumentasi Kode Program

### 6.1 Struktur Direktori Proyek

```
laravel-edugrade/
├── app/
│   ├── Exports/
│   │   └── LaporanNilaiExport.php        → Export data ke Excel
│   ├── Filament/
│   │   ├── Pages/
│   │   │   └── LaporanNilai.php          → Halaman custom laporan nilai
│   │   ├── Resources/
│   │   │   ├── Gurus/
│   │   │   │   ├── GuruResource.php      → Resource CRUD Guru
│   │   │   │   ├── Pages/                → List, Create, Edit, View
│   │   │   │   ├── Schemas/              → GuruForm, GuruInfolist
│   │   │   │   └── Tables/               → GurusTable
│   │   │   ├── MataPelajarans/
│   │   │   │   ├── MataPelajaranResource.php
│   │   │   │   ├── Pages/
│   │   │   │   ├── Schemas/
│   │   │   │   └── Tables/
│   │   │   ├── Nilais/
│   │   │   │   ├── NilaiResource.php     → Resource CRUD Nilai + query scope per role
│   │   │   │   ├── Pages/
│   │   │   │   ├── Schemas/              → NilaiForm (realtime calc), NilaiInfolist
│   │   │   │   └── Tables/               → NilaisTable (filter, badge, export)
│   │   │   ├── Siswas/
│   │   │   │   ├── SiswaResource.php
│   │   │   │   ├── Pages/
│   │   │   │   ├── Schemas/              → SiswaForm (auto-generate NIS)
│   │   │   │   └── Tables/               → SiswasTable (filter, export)
│   │   │   └── Users/
│   │   │       ├── UserResource.php
│   │   │       ├── Pages/
│   │   │       ├── Schemas/
│   │   │       └── Tables/
│   │   └── Widgets/
│   │       ├── NilaiChartWidget.php       → Chart Doughnut Lulus vs Tidak Lulus
│   │       └── StatsOverviewWidget.php    → Statistik dashboard per role
│   ├── Helpers/
│   │   └── helpers.php                    → 4 fungsi prosedural (validasi, hitung, status, laporan)
│   ├── Imports/
│   │   ├── GuruImport.php                → Import guru dari Excel
│   │   └── SiswaImport.php               → Import siswa dari Excel
│   ├── Models/
│   │   ├── Guru.php                       → Model Guru (OOP) + auto-generate ID
│   │   ├── MataPelajaran.php              → Model MapePelajaran (OOP) + relasi M:N
│   │   ├── Nilai.php                      → Model Nilai (OOP) + hitungNilaiAkhir() + tentukanStatus()
│   │   ├── Siswa.php                      → Model Siswa (OOP) + lihatProfil() + auto-generate NIS
│   │   └── User.php                       → Model User + isAdmin() + isGuru() + isSiswa()
│   └── Policies/
│       ├── GuruPolicy.php                 → Otorisasi CRUD Guru
│       ├── MataPelajaranPolicy.php         → Otorisasi CRUD Mapel
│       ├── NilaiPolicy.php                → Otorisasi CRUD Nilai
│       ├── SiswaPolicy.php                → Otorisasi CRUD Siswa
│       └── UserPolicy.php                 → Otorisasi CRUD User
├── database/
│   ├── migrations/                        → 10 file migrasi
│   └── seeders/
│       ├── DatabaseSeeder.php             → Seeder utama (mapel, user, guru, siswa, nilai)
│       ├── RoleSeeder.php                 → Setup role & permission per role
│       └── ShieldSeeder.php               → Auto-generate permissions Filament Shield
├── resources/views/
│   └── filament/pages/
│       └── laporan-nilai.blade.php        → View custom halaman laporan (567 baris, responsif)
├── routes/
│   └── web.php                            → Landing page + route export Excel
└── composer.json                          → Dependensi + autoload helpers.php
```

### 6.2 Alur Data pada Sistem

```
[User Login] → [Filament Panel] → [Dashboard (Widget)]
                    │
                    ├── [Resource CRUD] → [Form Schema] → [Model] → [Database]
                    │                                         ↑
                    │                            [Model Event: saving]
                    │                            auto-compute nilai_akhir & status
                    │
                    ├── [Laporan Nilai Page] → [Query by Role] → [View Blade]
                    │                                                 │
                    │                                          [Export Excel]
                    │
                    └── [Policy] → [Permission Check] → [Allow/Deny]
```

---

## 7. Penjelasan Fungsi, Modul, dan Class

### 7.1 Modul-Modul Sistem

| No | Modul | File Utama | Fungsi |
|----|-------|-----------|--------|
| 1 | **Autentikasi** | `User.php`, `RoleSeeder.php` | Login, logout, manajemen role (admin, guru, siswa) |
| 2 | **Manajemen Siswa** | `Siswa.php`, `SiswaResource.php`, `SiswaForm.php` | CRUD data siswa dengan auto-generate NIS |
| 3 | **Manajemen Guru** | `Guru.php`, `GuruResource.php`, `GuruForm.php` | CRUD data guru dengan relasi M:N ke mapel |
| 4 | **Manajemen Mata Pelajaran** | `MataPelajaran.php`, `MataPelajaranResource.php` | CRUD data mata pelajaran |
| 5 | **Pengolahan Nilai** | `Nilai.php`, `NilaiResource.php`, `NilaiForm.php`, `helpers.php` | Input nilai, validasi, hitung nilai akhir, tentukan status |
| 6 | **Laporan** | `LaporanNilai.php`, `laporan-nilai.blade.php`, `LaporanNilaiExport.php` | Tampilkan laporan, filter, export ke Excel |
| 7 | **Dashboard** | `StatsOverviewWidget.php`, `NilaiChartWidget.php` | Statistik overview dan chart kelulusan |
| 8 | **Otorisasi** | `NilaiPolicy.php`, `SiswaPolicy.php`, dll. | Policy-based access control per resource |

### 7.2 Penjelasan Fungsi Prosedural

| No | Fungsi | Parameter | Return | Keterangan |
|----|--------|-----------|--------|------------|
| 1 | `validasiNilai($nilai)` | `float/int` | `bool` | Cek apakah nilai berada dalam rentang 0–100 |
| 2 | `hitungNilaiAkhirProsedural($tugas, $uts, $uas)` | `float, float, float` | `float` | Hitung: (tugas×0.3)+(uts×0.3)+(uas×0.4) |
| 3 | `tentukanStatusKelulusan($nilaiAkhir)` | `float` | `string` | Return "Lulus" jika ≥70, "Tidak Lulus" jika <70 |
| 4 | `buatLaporanNilai($dataNilai)` | `mixed` | `mixed` | Proses data untuk ditampilkan di laporan |

### 7.3 Penjelasan Class dan Method

#### Class `Siswa`
| Method | Tipe | Return | Keterangan |
|--------|------|--------|------------|
| `boot()` | static | void | Auto-generate NIS saat creating |
| `user()` | relationship | BelongsTo | Relasi ke User (1:1) |
| `nilais()` | relationship | HasMany | Relasi ke Nilai (1:N) |
| `lihatProfil()` | public | string | Return format "NIS - Nama - Kelas" |

#### Class `Guru`
| Method | Tipe | Return | Keterangan |
|--------|------|--------|------------|
| `boot()` | static | void | Auto-generate ID Guru saat creating |
| `user()` | relationship | BelongsTo | Relasi ke User (1:1) |
| `mataPelajarans()` | relationship | BelongsToMany | Relasi ke MapePelajaran (M:N via guru_mapel) |
| `nilais()` | relationship | HasMany | Relasi ke Nilai (1:N) |

#### Class `MataPelajaran`
| Method | Tipe | Return | Keterangan |
|--------|------|--------|------------|
| `gurus()` | relationship | BelongsToMany | Relasi ke Guru (M:N via guru_mapel) |
| `nilais()` | relationship | HasMany | Relasi ke Nilai (1:N) |

#### Class `Nilai`
| Method | Tipe | Return | Keterangan |
|--------|------|--------|------------|
| `booted()` | static | void | Model event saving: auto-compute nilai_akhir & status |
| `siswa()` | relationship | BelongsTo | Relasi ke Siswa |
| `guru()` | relationship | BelongsTo | Relasi ke Guru |
| `mataPelajaran()` | relationship | BelongsTo | Relasi ke MataPelajaran |
| `hitungNilaiAkhir()` | public | float | Hitung: (tugas×0.3)+(uts×0.3)+(uas×0.4) |
| `tentukanStatus()` | public | string | Return "Lulus" jika ≥70, "Tidak Lulus" jika <70 |

#### Class `User`
| Method | Tipe | Return | Keterangan |
|--------|------|--------|------------|
| `siswa()` | relationship | HasOne | Relasi ke Siswa (1:1) |
| `guru()` | relationship | HasOne | Relasi ke Guru (1:1) |
| `isAdmin()` | public | bool | Cek apakah user adalah admin/super_admin |
| `isGuru()` | public | bool | Cek apakah user adalah guru |
| `isSiswa()` | public | bool | Cek apakah user adalah siswa |
| `canAccessPanel(Panel $panel)` | public | bool | Menentukan akses ke Filament panel |

### 7.4 Integrasi Pemrograman Terstruktur dan OOP

Aplikasi ini mengintegrasikan **dua pendekatan pemrograman** dalam satu sistem:

**Pemrograman Terstruktur (Prosedural):**
- 4 fungsi global di `helpers.php`
- Digunakan di form Filament untuk realtime calculation
- Digunakan di halaman laporan untuk processing data

**Pemrograman Berorientasi Objek (OOP):**
- 5 class model: `User`, `Siswa`, `Guru`, `MataPelajaran`, `Nilai`
- Method `hitungNilaiAkhir()` dan `tentukanStatus()` pada class `Nilai`
- Method `lihatProfil()` pada class `Siswa`
- Model events, relationships, dan encapsulation

**Titik Integrasi:**
```
┌──────────────────────────────────┐
│       FORM (Sisi Client)         │
│  [Prosedural: helpers.php]       │
│  validasiNilai()                 │
│  hitungNilaiAkhirProsedural()    │
│  tentukanStatusKelulusan()       │
└────────────┬─────────────────────┘
             │ Submit
             ▼
┌──────────────────────────────────┐
│       MODEL (Sisi Server)        │
│  [OOP: Nilai.php]                │
│  $nilai->hitungNilaiAkhir()      │
│  $nilai->tentukanStatus()        │
│  [Event: saving → auto-compute]  │
└────────────┬─────────────────────┘
             │ Save
             ▼
┌──────────────────────────────────┐
│       DATABASE                   │
│  nilai_akhir = computed value    │
│  status = 'Lulus'/'Tidak Lulus'  │
└──────────────────────────────────┘
```

---

## 8. Evaluasi Hasil Pengujian Aplikasi

### 8.1 Evaluasi Fungsional

| No | Fitur | Status | Catatan |
|----|-------|--------|---------|
| 1 | Login berdasarkan role | ✅ Terpenuhi | 3 role (Admin, Guru, Siswa) dengan dashboard berbeda |
| 2 | CRUD Data Siswa | ✅ Terpenuhi | Create, Read, Update, Soft Delete, Restore, Search, Filter |
| 3 | CRUD Data Guru | ✅ Terpenuhi | Termasuk relasi M:N ke Mata Pelajaran |
| 4 | CRUD Data Mata Pelajaran | ✅ Terpenuhi | Dengan unique constraint pada kode_mapel |
| 5 | Input Nilai dengan Validasi | ✅ Terpenuhi | Validasi range 0-100, required, numeric |
| 6 | Perhitungan Nilai Akhir Otomatis | ✅ Terpenuhi | Rumus 30%T + 30%U + 40%A, realtime + model event |
| 7 | Penentuan Status Kelulusan | ✅ Terpenuhi | ≥ 70 = Lulus, < 70 = Tidak Lulus |
| 8 | Laporan Nilai dengan Filter | ✅ Terpenuhi | Filter kelas, mapel, semester, status + search |
| 9 | Export Laporan ke Excel | ✅ Terpenuhi | Format .xlsx dengan data sesuai filter |
| 10 | Hak Akses per Role | ✅ Terpenuhi | Policy + Shield + query scope per role |

### 8.2 Evaluasi Non-Fungsional

| No | Aspek | Status | Catatan |
|----|-------|--------|---------|
| 1 | Keamanan | ✅ Baik | Password hashed, RBAC, policy-based auth, CSRF protection |
| 2 | Integritas Data | ✅ Baik | Foreign key, unique constraint, soft deletes |
| 3 | Responsivitas UI | ✅ Baik | Filament panel responsive, tabel scrollable |
| 4 | Performa | ✅ Baik | Eager loading untuk mencegah N+1 query |
| 5 | Maintainability | ✅ Baik | Separation of concerns, documented code, modular structure |

### 8.3 Kesesuaian dengan Kebutuhan Sistem

| No | Kebutuhan | Implementasi | Status |
|----|-----------|-------------|--------|
| 1 | Sistem dapat menyimpan data siswa | Model Siswa + SiswaResource (CRUD) | ✅ |
| 2 | Sistem dapat mengelola data nilai siswa | Model Nilai + NilaiResource (CRUD) | ✅ |
| 3 | Sistem dapat menghitung nilai akhir secara otomatis | `hitungNilaiAkhir()` (OOP) + `hitungNilaiAkhirProsedural()` (Prosedural) | ✅ |
| 4 | Sistem dapat menentukan status kelulusan | `tentukanStatus()` (OOP) + `tentukanStatusKelulusan()` (Prosedural) | ✅ |
| 5 | Sistem dapat menampilkan laporan nilai | LaporanNilai Page + Export Excel | ✅ |

### 8.4 Kesesuaian dengan Ketentuan Teknis

| No | Ketentuan | Implementasi | Status |
|----|-----------|-------------|--------|
| 1 | Minimal 3 fungsi prosedural | 4 fungsi di `helpers.php`: `validasiNilai`, `hitungNilaiAkhirProsedural`, `tentukanStatusKelulusan`, `buatLaporanNilai` | ✅ |
| 2 | Minimal 2 class dengan method | 5 class (User, Siswa, Guru, MataPelajaran, Nilai) dengan method `hitungNilaiAkhir()`, `tentukanStatus()`, `lihatProfil()`, dll. | ✅ |
| 3 | Koneksi database | Laravel Eloquent ORM + MySQL | ✅ |
| 4 | Coding guidelines & best practices | PSR-12, Naming conventions, Separation of concerns, DRY, SOLID | ✅ |

### 8.5 Kesimpulan Akhir

Aplikasi **EduGrade** telah berhasil dibangun dan diuji sesuai dengan kebutuhan yang telah didefinisikan. Seluruh **70 test case** yang diujikan menghasilkan status **Pass (100%)**. Aplikasi memenuhi semua kebutuhan fungsional dan non-fungsional, serta mengimplementasikan **pemrograman terstruktur** (fungsi prosedural) dan **pemrograman berorientasi objek** (class dan method) secara terintegrasi dalam satu sistem.

Beberapa **kekuatan utama** aplikasi:
1. **Dual-approach calculation** — Perhitungan di sisi form (prosedural) dan model (OOP) memastikan data selalu konsisten
2. **Role-based access control** yang komprehensif dengan 3 level akses
3. **Cascade select** pada form nilai untuk UX yang intuitif
4. **Model events** untuk menjaga integritas data otomatis
5. **Export Excel** untuk kebutuhan pelaporan
6. **Soft deletes** pada semua model utama untuk keamanan data
7. **Dashboard responsif** dengan statistik yang berbeda per role

Aplikasi siap untuk dipresentasikan dan divalidasi oleh instruktur sebagai bagian dari persiapan Uji Kompetensi LSP Skema Programmer.
