<?php

if (!function_exists('validasiNilai')) {
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
}

if (!function_exists('hitungNilaiAkhirProsedural')) {
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
}

if (!function_exists('tentukanStatusKelulusan')) {
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
}

if (!function_exists('buatLaporanNilai')) {
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
}
