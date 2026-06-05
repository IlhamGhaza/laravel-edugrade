
## 8. Rancangan Database

### Tabel `users`

| Field      | Tipe Data | Keterangan         |
| ---------- | --------- | ------------------ |
| id         | bigint    | Primary key        |
| name       | varchar   | Nama pengguna      |
| email      | varchar   | Email login        |
| password   | varchar   | Password           |
| role       | enum      | admin, guru, siswa |
| created_at | timestamp | Waktu dibuat       |
| updated_at | timestamp | Waktu diubah       |

### Tabel `siswas`

| Field      | Tipe Data | Keterangan           |
| ---------- | --------- | -------------------- |
| id         | bigint    | Primary key          |
| user_id    | bigint    | Foreign key ke users |
| nis        | varchar   | NIS unik             |
| nama       | varchar   | Nama siswa           |
| kelas_id   | bigint    | Foreign key ke kelas |
| created_at | timestamp | Waktu dibuat         |
| updated_at | timestamp | Waktu diubah         |

### Tabel `kelas`

| Field      | Tipe Data | Keterangan           |
| ---------- | --------- | -------------------- |
| id         | bigint    | Primary key          |
| nama_kelas | varchar   | Nama kelas           |
| created_at | timestamp | Waktu dibuat         |
| updated_at | timestamp | Waktu diubah         |
| deleted_at | timestamp | Soft delete          |

### Tabel `mata_pelajarans`

| Field      | Tipe Data | Keterangan                      |
| ---------- | --------- | ------------------------------- |
| id         | bigint    | Primary key                     |
| kode_mapel | varchar   | Kode unik mapel (MTK, BIN, dll) |
| nama_mapel | varchar   | Nama mata pelajaran             |
| created_at | timestamp | Waktu dibuat                    |
| updated_at | timestamp | Waktu diubah                    |
| deleted_at | timestamp | Soft delete                     |

### Tabel `gurus`

| Field      | Tipe Data | Keterangan           |
| ---------- | --------- | -------------------- |
| id         | bigint    | Primary key          |
| user_id    | bigint    | Foreign key ke users |
| id_guru    | varchar   | ID guru unik         |
| nama_guru  | varchar   | Nama guru            |
| created_at | timestamp | Waktu dibuat         |
| updated_at | timestamp | Waktu diubah         |
| deleted_at | timestamp | Soft delete          |

### Tabel `guru_mapel` (Pivot)

| Field      | Tipe Data | Keterangan                      |
| ---------- | --------- | ------------------------------- |
| id         | bigint    | Primary key                     |
| guru_id    | bigint    | Foreign key ke gurus            |
| mapel_id   | bigint    | Foreign key ke mata_pelajarans  |
| created_at | timestamp | Waktu dibuat                    |
| updated_at | timestamp | Waktu diubah                    |

> Relasi many-to-many: 1 guru bisa mengajar banyak mapel, 1 mapel bisa diajar banyak guru.

### Tabel `guru_kelas` (Pivot)

| Field      | Tipe Data | Keterangan                      |
| ---------- | --------- | ------------------------------- |
| id         | bigint    | Primary key                     |
| guru_id    | bigint    | Foreign key ke gurus            |
| kelas_id   | bigint    | Foreign key ke kelas            |
| created_at | timestamp | Waktu dibuat                    |
| updated_at | timestamp | Waktu diubah                    |

### Tabel `kelas_mapel` (Pivot)

| Field      | Tipe Data | Keterangan                      |
| ---------- | --------- | ------------------------------- |
| id         | bigint    | Primary key                     |
| kelas_id   | bigint    | Foreign key ke kelas            |
| mapel_id   | bigint    | Foreign key ke mata_pelajarans  |
| created_at | timestamp | Waktu dibuat                    |
| updated_at | timestamp | Waktu diubah                    |

### Tabel `nilais`

| Field       | Tipe Data | Keterangan                      |
| ----------- | --------- | ------------------------------- |
| id          | bigint    | Primary key                     |
| siswa_id    | bigint    | Foreign key ke siswas           |
| guru_id     | bigint    | Foreign key ke gurus            |
| mapel_id    | bigint    | Foreign key ke mata_pelajarans  |
| nilai_tugas | decimal   | Nilai tugas                     |
| nilai_uts   | decimal   | Nilai UTS                       |
| nilai_uas   | decimal   | Nilai UAS                       |
| nilai_akhir | decimal   | Hasil perhitungan otomatis      |
| status      | varchar   | Lulus / Tidak Lulus             |
| created_at  | timestamp | Waktu dibuat                    |
| updated_at  | timestamp | Waktu diubah                    |

### Diagram Relasi (ERD)

```
┌──────────┐       ┌────────────┐       ┌──────────────────┐
│  gurus   │◄──M──►│ guru_mapel │◄──M──►│ mata_pelajarans  │
├──────────┤       ├────────────┤       ├──────────────────┤
│ id       │       │ id         │       │ id               │
│ user_id  │       │ guru_id    │       │ kode_mapel       │
│ id_guru  │       │ mapel_id   │       │ nama_mapel       │
│ nama_guru│       └────────────┘       └──────────────────┘
└──────────┘                                   │
      │                                        │ FK
      │ M                                      ▼
      │                                  ┌──────────┐
      ▼                                  │  nilais  │
┌────────────┐                           ├──────────┤
│ guru_kelas │                           │ siswa_id │
└────────────┘                           │ guru_id  │
      │ M                                │ mapel_id │
      ▼                                  └──────────┘
┌──────────┐ M     ┌─────────────┐ M           ▲
│  kelas   │◄─────►│ kelas_mapel │◄────────────┘
├──────────┤       └─────────────┘
│ id       │              
│nama_kelas│              
└──────────┘              
      │ 1
      ▼ M
┌──────────┐
│  siswas  │
├──────────┤
│ kelas_id │
└──────────┘
```

---


## 11. Rancangan Fungsi / Procedure Terstruktur

### 1. Fungsi Validasi Nilai

```php
function validasiNilai($nilai)
{
    return $nilai >= 0 && $nilai <= 100;
}
```

### 2. Fungsi Hitung Nilai Akhir

```php
function hitungNilaiAkhir($tugas, $uts, $uas)
{
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}
```

### 3. Fungsi Tentukan Status Kelulusan

```php
function tentukanStatusKelulusan($nilaiAkhir)
{
    return $nilaiAkhir >= 70 ? 'Lulus' : 'Tidak Lulus';
}
```

### 4. Fungsi Buat Laporan Nilai

```php
function buatLaporanNilai($dataNilai)
{
    return $dataNilai;
}
```

---

## 12. Rancangan Class dan Method OOP

### Class `Siswa`

```php
class Siswa
{
    public $nis;
    public $nama;
    public $kelas_id;

    public function __construct($nis, $nama, $kelas_id)
    {
        $this->nis = $nis;
        $this->nama = $nama;
        $this->kelas_id = $kelas_id;
    }

    public function lihatProfil()
    {
        // Relasi kelas dipanggil di sini untuk mendapatkan nama kelas
        return $this->nis . ' - ' . $this->nama . ' - ' . $this->kelas->nama_kelas;
    }
}
```

### Class `Kelas`

```php
class Kelas
{
    public $namaKelas;

    public function __construct($namaKelas)
    {
        $this->namaKelas = $namaKelas;
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}
```

### Class `MataPelajaran`

```php
class MataPelajaran
{
    public $kodMapel;
    public $namaMapel;

    public function __construct($kodeMapel, $namaMapel)
    {
        $this->kodeMapel = $kodeMapel;
        $this->namaMapel = $namaMapel;
    }

    // Relasi many-to-many ke Guru
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'mapel_id', 'guru_id');
    }

    // Relasi one-to-many ke Nilai
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}
```

### Class `Nilai`

```php
class Nilai
{
    public $nilaiTugas;
    public $nilaiUts;
    public $nilaiUas;

    public function __construct($nilaiTugas, $nilaiUts, $nilaiUas)
    {
        $this->nilaiTugas = $nilaiTugas;
        $this->nilaiUts = $nilaiUts;
        $this->nilaiUas = $nilaiUas;
    }

    public function hitungNilaiAkhir()
    {
        return ($this->nilaiTugas * 0.30) 
             + ($this->nilaiUts * 0.30) 
             + ($this->nilaiUas * 0.40);
    }

    public function tentukanStatus()
    {
        return $this->hitungNilaiAkhir() >= 70 ? 'Lulus' : 'Tidak Lulus';
    }

    // Relasi ke MataPelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
```