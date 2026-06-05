<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanNilaiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $nilais;

    public function __construct($nilais)
    {
        $this->nilais = $nilais;
    }

    public function collection()
    {
        return $this->nilais;
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Semester',
            'Guru',
            'Mata Pelajaran',
            'Tugas',
            'UTS',
            'UAS',
            'Nilai Akhir',
            'Status'
        ];
    }

    public function map($nilai): array
    {
        return [
            $nilai->siswa->nis ?? '-',
            $nilai->siswa->nama ?? '-',
            $nilai->siswa->kelas ?? '-',
            $nilai->semester ?? '-',
            $nilai->guru->nama_guru ?? '-',
            $nilai->mataPelajaran->nama_mapel ?? '-',
            $nilai->nilai_tugas,
            $nilai->nilai_uts,
            $nilai->nilai_uas,
            $nilai->nilai_akhir,
            $nilai->status,
        ];
    }
}
