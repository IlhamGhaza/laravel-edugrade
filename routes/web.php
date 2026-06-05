<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Homepage menampilkan landing page EduGrade.
| Semua operasi aplikasi dilakukan melalui Filament panel di /admin.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/export-laporan-nilai', function (\Illuminate\Http\Request $request) {
    if (!auth()->check() || !auth()->user()->hasRole(['admin', 'super_admin'])) {
        abort(403);
    }

    $query = \App\Models\Nilai::with(['guru', 'siswa', 'mataPelajaran']);

    if ($request->filled('searchSiswa')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->searchSiswa . '%');
        });
    }
    if ($request->filled('filterKelas')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('kelas', $request->filterKelas);
        });
    }
    if ($request->filled('filterMapel')) {
        $query->where('mapel_id', $request->filterMapel);
    }
    if ($request->filled('filterSemester')) {
        $query->where('semester', $request->filterSemester);
    }

    $nilais = $query->get();
    $nilais = buatLaporanNilai($nilais);

    if ($request->filled('filterStatus')) {
        $nilais = $nilais->filter(function ($nilai) use ($request) {
            return $nilai->status === $request->filterStatus;
        });
    }

    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanNilaiExport($nilais), 'laporan_nilai.xlsx');
})->name('export.laporan.nilai');
