<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

/**
 * Import data Siswa dari file Excel.
 *
 * Format kolom Excel yang diharapkan:
 * | nama | kelas | user_id (opsional) |
 *
 * - NIS akan di-generate otomatis oleh model event.
 * - user_id bersifat opsional; jika diisi harus merupakan ID user yang valid.
 */
class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public int $importedCount = 0;

    public function model(array $row): ?Siswa
    {
        $this->importedCount++;

        return new Siswa([
            'nama'    => $row['nama'],
            'kelas'   => $row['kelas'],
            'user_id' => $row['user_id'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'    => ['required', 'string', 'max:255'],
            'kelas'   => ['required', 'string', 'in:10,11,12'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama.required'  => 'Kolom nama wajib diisi.',
            'nama.max'       => 'Nama siswa maksimal 255 karakter.',
            'kelas.required' => 'Kolom kelas wajib diisi.',
            'kelas.in'       => 'Kelas harus salah satu dari: 10, 11, 12.',
            'user_id.exists'  => 'User ID :input tidak ditemukan di database.',
        ];
    }
}
