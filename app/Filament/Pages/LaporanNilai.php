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

    public $searchSiswa = '';
    public $filterKelas = '';
    public $filterMapel = '';
    public $filterSemester = '';
    public $filterStatus = '';

    protected $queryString = [
        'searchSiswa' => ['except' => ''],
        'filterKelas' => ['except' => ''],
        'filterMapel' => ['except' => ''],
        'filterSemester' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function resetFilters()
    {
        $this->searchSiswa = '';
        $this->filterKelas = '';
        $this->filterMapel = '';
        $this->filterSemester = '';
        $this->filterStatus = '';
    }

    public function getFilteredNilais()
    {
        $user = auth()->user();
        $query = Nilai::with(['guru', 'siswa', 'mataPelajaran']);

        if ($user->hasRole('siswa')) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            } else {
                return collect();
            }
        } elseif ($user->hasRole('guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('guru_id', $guru->id);
            } else {
                return collect();
            }
        }

        // Apply filters only for admin/super_admin
        if ($user->hasRole(['admin', 'super_admin'])) {
            if (!empty($this->searchSiswa)) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . $this->searchSiswa . '%');
                });
            }

            if (!empty($this->filterKelas)) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('kelas_id', $this->filterKelas);
                });
            }

            if (!empty($this->filterMapel)) {
                $query->where('mapel_id', $this->filterMapel);
            }

            if (!empty($this->filterSemester)) {
                $query->where('semester', $this->filterSemester);
            }
        }

        $nilais = $query->get();
        $nilais = buatLaporanNilai($nilais);

        if ($user->hasRole(['admin', 'super_admin']) && !empty($this->filterStatus)) {
            $nilais = $nilais->filter(function ($nilai) {
                return $nilai->status === $this->filterStatus;
            });
        }

        return $nilais;
    }

    public function getViewData(): array
    {
        $nilais = $this->getFilteredNilais();

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
            'mataPelajarans' => \App\Models\MataPelajaran::all(),
            'kelasList' => \App\Models\Kelas::pluck('nama_kelas', 'id'),
        ];
    }
}
