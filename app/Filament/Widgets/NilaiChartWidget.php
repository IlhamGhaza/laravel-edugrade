<?php

namespace App\Filament\Widgets;

use App\Models\Nilai;
use App\Models\Guru;
use App\Models\Siswa;
use Filament\Widgets\ChartWidget;

/**
 * Chart widget distribusi status kelulusan.
 * Menampilkan perbandingan jumlah siswa Lulus vs Tidak Lulus.
 */
class NilaiChartWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Status Kelulusan';

    protected function getData(): array
    {
        $user = auth()->user();

        $query = Nilai::query();

        // Guru hanya lihat data yang dia input
        if ($user->hasRole('guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('guru_id', $guru->id);
            }
        }

        // Siswa hanya lihat data pribadinya
        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        }

        $lulus = (clone $query)->where('status', 'Lulus')->count();
        $tidakLulus = (clone $query)->where('status', 'Tidak Lulus')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa',
                    'data' => [$lulus, $tidakLulus],
                    'backgroundColor' => ['#10b981', '#f43f5e'],
                    'borderColor' => ['#059669', '#e11d48'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Lulus', 'Tidak Lulus'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
