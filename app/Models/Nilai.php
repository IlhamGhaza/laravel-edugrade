<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model Nilai — Representasi OOP dari data nilai siswa.
 *
 * Mengimplementasikan:
 * - hitungNilaiAkhir()  → rumus 30% Tugas + 30% UTS + 40% UAS
 * - tentukanStatus()    → status Lulus jika nilai akhir >= 70
 *
 * Model event 'saving' memastikan nilai_akhir dan status selalu
 * dihitung ulang secara otomatis sebelum disimpan ke database.
 */
class Nilai extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['siswa_id', 'guru_id', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'status'];

    /**
     * Casts untuk memastikan tipe data yang benar.
     */
    protected $casts = [
        'nilai_tugas' => 'decimal:2',
        'nilai_uts'   => 'decimal:2',
        'nilai_uas'   => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    // =========================================================================
    //  MODEL EVENTS — Auto-compute nilai_akhir & status saat saving
    // =========================================================================

    protected static function booted(): void
    {
        static::saving(function (Nilai $nilai) {
            // Gunakan method OOP untuk menghitung
            $nilai->nilai_akhir = $nilai->hitungNilaiAkhir();
            $nilai->status = $nilai->tentukanStatus();
        });
    }

    // =========================================================================
    //  RELATIONSHIPS
    // =========================================================================

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    // =========================================================================
    //  OOP METHODS — Implementasi Pemrograman Berorientasi Objek
    // =========================================================================

    /**
     * Menghitung nilai akhir menggunakan rumus:
     * Nilai Akhir = (30% × Tugas) + (30% × UTS) + (40% × UAS)
     *
     * @return float
     */
    public function hitungNilaiAkhir(): float
    {
        return ($this->nilai_tugas * 0.30) + ($this->nilai_uts * 0.30) + ($this->nilai_uas * 0.40);
    }

    /**
     * Menentukan status kelulusan berdasarkan nilai akhir.
     * Siswa dinyatakan lulus jika nilai akhir >= 70.
     *
     * @return string 'Lulus' | 'Tidak Lulus'
     */
    public function tentukanStatus(): string
    {
        return $this->hitungNilaiAkhir() >= 70 ? 'Lulus' : 'Tidak Lulus';
    }
}
