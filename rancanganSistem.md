
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
| kelas      | varchar   | Kelas siswa          |
| created_at | timestamp | Waktu dibuat         |
| updated_at | timestamp | Waktu diubah         |

### Tabel `gurus`

| Field          | Tipe Data | Keterangan           |
| -------------- | --------- | -------------------- |
| id             | bigint    | Primary key          |
| user_id        | bigint    | Foreign key ke users |
| id_guru        | varchar   | ID guru unik         |
| nama_guru      | varchar   | Nama guru            |
| mata_pelajaran | varchar   | Mata pelajaran       |
| created_at     | timestamp | Waktu dibuat         |
| updated_at     | timestamp | Waktu diubah         |

### Tabel `nilais`

| Field       | Tipe Data | Keterangan                 |
| ----------- | --------- | -------------------------- |
| id          | bigint    | Primary key                |
| siswa_id    | bigint    | Foreign key ke siswas      |
| guru_id     | bigint    | Foreign key ke gurus       |
| nilai_tugas | decimal   | Nilai tugas                |
| nilai_uts   | decimal   | Nilai UTS                  |
| nilai_uas   | decimal   | Nilai UAS                  |
| nilai_akhir | decimal   | Hasil perhitungan otomatis |
| status      | varchar   | Lulus / Tidak Lulus        |
| created_at  | timestamp | Waktu dibuat               |
| updated_at  | timestamp | Waktu diubah               |

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
    public $kelas;

    public function __construct($nis, $nama, $kelas)
    {
        $this->nis = $nis;
        $this->nama = $nama;
        $this->kelas = $kelas;
    }

    public function lihatProfil()
    {
        return $this->nis . ' - ' . $this->nama . ' - ' . $this->kelas;
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
}
```