<?php

namespace App\Filament\Widgets;

use App\Models\Guru;
use App\Models\Nilai;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Widget statistik overview di Dashboard.
 * Menampilkan data berdasarkan role pengguna:
 * - Admin: total siswa, guru, nilai, rata-rata
 * - Guru: total siswa yang dinilai, total nilai, rata-rata miliknya
 * - Siswa: rata-rata nilai, status kelulusan
 */
class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        if ($user->hasRole(['admin', 'super_admin'])) {
            return $this->getAdminStats();
        }

        if ($user->hasRole('guru')) {
            return $this->getGuruStats($user);
        }

        if ($user->hasRole('siswa')) {
            return $this->getSiswaStats($user);
        }

        return [];
    }

    /**
     * Statistik untuk Admin — overview seluruh sistem.
     */
    private function getAdminStats(): array
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalNilai = Nilai::count();
        $rataRata = Nilai::avg('nilai_akhir') ?? 0;
        $persenLulus = $totalNilai > 0
            ? round(Nilai::where('status', 'Lulus')->count() / $totalNilai * 100, 1)
            : 0;

        return [
            Stat::make('Total Siswa', $totalSiswa)
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('info'),
            Stat::make('Total Guru', $totalGuru)
                ->description('Guru pengajar')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('success'),
            Stat::make('Rata-rata Nilai', number_format($rataRata, 1))
                ->description($persenLulus . '% siswa lulus')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($rataRata >= 70 ? 'success' : 'warning'),
            Stat::make('Total Data Nilai', $totalNilai)
                ->description('Record nilai tersimpan')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('primary'),
        ];
    }

    /**
     * Statistik untuk Guru — overview nilai yang dia input.
     */
    private function getGuruStats($user): array
    {
        $guru = Guru::where('user_id', $user->id)->first();

        if (!$guru) {
            return [
                Stat::make('Info', 'Profil guru belum terhubung')
                    ->color('danger'),
            ];
        }

        $totalNilai = Nilai::where('guru_id', $guru->id)->count();
        $rataRata = Nilai::where('guru_id', $guru->id)->avg('nilai_akhir') ?? 0;
        $lulus = Nilai::where('guru_id', $guru->id)->where('status', 'Lulus')->count();
        $tidakLulus = $totalNilai - $lulus;

        return [
            Stat::make('Mata Pelajaran', $guru->mataPelajarans->pluck('nama_mapel')->join(', ') ?: '-')
                ->description('Mapel yang diampu')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('primary'),
            Stat::make('Total Nilai', $totalNilai)
                ->description('Nilai yang sudah diinput')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Rata-rata', number_format($rataRata, 1))
                ->description($lulus . ' lulus, ' . $tidakLulus . ' tidak lulus')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($rataRata >= 70 ? 'success' : 'warning'),
        ];
    }

    /**
     * Statistik untuk Siswa — overview nilai pribadi.
     */
    private function getSiswaStats($user): array
    {
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return [
                Stat::make('Info', 'Profil siswa belum terhubung')
                    ->color('danger'),
            ];
        }

        $nilais = Nilai::where('siswa_id', $siswa->id)->get();
        $totalMapel = $nilais->count();
        $rataRata = $nilais->avg('nilai_akhir') ?? 0;
        $lulus = $nilais->where('status', 'Lulus')->count();

        return [
            Stat::make('Kelas', $siswa->kelas)
                ->description('NIS: ' . $siswa->nis)
                ->descriptionIcon('heroicon-o-academic-cap')
                ->color('primary'),
            Stat::make('Rata-rata Nilai', number_format($rataRata, 1))
                ->description($lulus . ' dari ' . $totalMapel . ' mapel lulus')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color($rataRata >= 70 ? 'success' : 'warning'),
            Stat::make('Total Mata Pelajaran', $totalMapel)
                ->description('Mapel yang sudah dinilai')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info'),
        ];
    }
}
