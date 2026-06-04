<?php

namespace App\Filament\Pages;

use App\Models\Guru;
use App\Models\Nilai;
use App\Models\Siswa;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

/**
 * Halaman Laporan Nilai — menampilkan rekap nilai siswa.
 *
 * Hak akses berdasarkan role:
 * - Admin/Super Admin: lihat semua data nilai
 * - Guru: lihat nilai yang dia input
 * - Siswa: lihat nilai pribadinya saja
 *
 * Menggunakan HasPageShield dari Filament Shield untuk kontrol akses.
 */
class LaporanNilai extends Page
{
    use HasPageShield;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan Nilai';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Laporan Nilai Siswa';

    protected string $view = 'filament.pages.laporan-nilai';

    public function getViewData(): array
    {
        $user = auth()->user();
        $nilais = collect();

        if ($user->hasRole('siswa')) {
            // Siswa: hanya lihat nilai pribadinya
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $nilais = Nilai::with(['guru', 'siswa'])
                    ->where('siswa_id', $siswa->id)
                    ->get();
            }
        } elseif ($user->hasRole('guru')) {
            // Guru: lihat nilai yang dia input
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $nilais = Nilai::with(['guru', 'siswa'])
                    ->where('guru_id', $guru->id)
                    ->get();
            }
        } elseif ($user->hasRole(['admin', 'super_admin'])) {
            // Admin: lihat semua
            $nilais = Nilai::with(['guru', 'siswa'])->get();
        }

        // Penerapan pemrograman terstruktur (prosedural):
        $nilais = buatLaporanNilai($nilais);

        // Statistik summary
        $totalData = $nilais->count();
        $rataRata = $totalData > 0 ? round($nilais->avg('nilai_akhir'), 1) : 0;
        $totalLulus = $nilais->where('status', 'Lulus')->count();
        $persenLulus = $totalData > 0 ? round($totalLulus / $totalData * 100, 1) : 0;

        return [
            'nilais' => $nilais,
            'totalData' => $totalData,
            'rataRata' => $rataRata,
            'totalLulus' => $totalLulus,
            'persenLulus' => $persenLulus,
        ];
    }
}
