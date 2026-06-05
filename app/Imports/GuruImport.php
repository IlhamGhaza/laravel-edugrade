<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

/**
 * Import data Guru dari file Excel.
 *
 * Format kolom Excel yang diharapkan:
 * | nama_guru | user_id (opsional) |
 *
 * - id_guru akan di-generate otomatis oleh model event.
 * - user_id bersifat opsional; jika diisi harus merupakan ID user yang valid.
 */
class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public int $importedCount = 0;

    public function model(array $row): ?Guru
    {
        $this->importedCount++;

        return new Guru([
            'nama_guru' => $row['nama_guru'],
            'user_id'   => $row['user_id'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_guru' => ['required', 'string', 'max:255'],
            'user_id'   => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'nama_guru.required' => 'Kolom nama_guru wajib diisi.',
            'nama_guru.max'      => 'Nama guru maksimal 255 karakter.',
            'user_id.exists'     => 'User ID :input tidak ditemukan di database.',
        ];
    }
}
