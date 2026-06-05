<?php

namespace App\Filament\Resources\Nilais\Schemas;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class NilaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Relasi')
                    ->description('Pilih siswa, guru, dan mata pelajaran yang terkait dengan nilai ini.')
                    ->schema([
                        Select::make('guru_id')
                            ->label('Guru')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->options(function () {
                                $user = auth()->user();
                                if ($user && $user->hasRole('guru')) {
                                    // Guru hanya melihat dirinya sendiri
                                    return Guru::where('user_id', $user->id)
                                        ->pluck('nama_guru', 'id')
                                        ->toArray();
                                }

                                // Admin melihat semua guru
                                return Guru::pluck('nama_guru', 'id')->toArray();
                            })
                            ->default(function () {
                                // Auto-select guru jika user yang login adalah guru
                                $user = auth()->user();
                                if ($user && $user->hasRole('guru')) {
                                    $guru = Guru::where('user_id', $user->id)->first();

                                    return $guru?->id;
                                }

                                return null;
                            })
                            ->disabled(function () {
                                // Guru tidak bisa mengubah field ini
                                $user = auth()->user();

                                return $user && $user->hasRole('guru');
                            })
                            ->dehydrated() // Pastikan value tetap dikirim walaupun disabled
                            ->afterStateUpdated(function (Set $set) {
                                // Reset mapel dan siswa saat guru berubah
                                $set('mapel_id', null);
                                $set('siswa_id', null);
                            }),
                        Select::make('mapel_id')
                            ->label('Mata Pelajaran')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->options(function (Get $get) {
                                $guruId = $get('guru_id');
                                if (! $guruId) {
                                    return [];
                                }

                                $user = auth()->user();
                                if ($user && $user->hasRole('guru')) {
                                    // Guru: hanya mapel yang dia ampu
                                    return MataPelajaran::whereHas('gurus', fn ($q) => $q->where('gurus.id', $guruId))
                                        ->pluck('nama_mapel', 'id')
                                        ->toArray();
                                }

                                // Admin: tampilkan mapel sesuai guru yang dipilih
                                return MataPelajaran::whereHas('gurus', fn ($q) => $q->where('gurus.id', $guruId))
                                    ->pluck('nama_mapel', 'id')
                                    ->toArray();
                            })
                            ->afterStateUpdated(function (Set $set) {
                                // Reset siswa saat mapel berubah
                                $set('siswa_id', null);
                            })
                        // ->helperText('Mapel akan difilter sesuai guru yang dipilih.')
                        ,
                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                'Ganjil' => 'Ganjil',
                                'Genap' => 'Genap',
                            ])
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                // Reset siswa saat semester berubah
                                $set('siswa_id', null);
                            }),
                        Select::make('kelas_id')
                            ->label('Kelas')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->dehydrated(false)
                            ->formatStateUsing(function (?Nilai $record) {
                                return $record?->siswa?->kelas_id;
                            })
                            ->options(function (Get $get) {
                                $guruId = $get('guru_id');
                                $mapelId = $get('mapel_id');
                                if (! $guruId || ! $mapelId) {
                                    return [];
                                }

                                return Kelas::whereHas('gurus', fn ($q) => $q->where('gurus.id', $guruId))
                                    ->whereHas('mataPelajarans', fn ($q) => $q->where('mata_pelajarans.id', $mapelId))
                                    ->pluck('nama_kelas', 'id')
                                    ->toArray();
                            })
                            ->afterStateUpdated(function (Set $set) {
                                // Reset siswa saat kelas berubah
                                $set('siswa_id', null);
                            }),
                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->options(function (Get $get, ?Nilai $record) {
                                $guruId = $get('guru_id');
                                $mapelId = $get('mapel_id');
                                $semester = $get('semester');
                                $kelasId = $get('kelas_id');

                                if (!$kelasId) {
                                    return [];
                                }

                                // Ambil siswa_id yang sudah punya nilai untuk kombinasi ini
                                $usedSiswaIds = Nilai::query()
                                    ->when($guruId, fn ($q) => $q->where('guru_id', $guruId))
                                    ->when($mapelId, fn ($q) => $q->where('mapel_id', $mapelId))
                                    ->when($semester, fn ($q) => $q->where('semester', $semester))
                                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->pluck('siswa_id')
                                    ->toArray();

                                return Siswa::query()
                                    ->where('kelas_id', $kelasId)
                                    ->whereNotIn('id', $usedSiswaIds)
                                    ->pluck('nama', 'id')
                                    ->toArray();
                            })
                        // ->helperText('Hanya menampilkan siswa yang belum dinilai untuk kombinasi guru, mapel, dan semester ini.')
                        ,
                    ])->columns(4),

                Section::make('Input Nilai')
                    ->description('Masukkan nilai dengan rentang 0–100. Nilai akhir dihitung otomatis (30% Tugas + 30% UTS + 40% UAS).')
                    ->schema([
                        TextInput::make('nilai_tugas')
                            ->label('Nilai Tugas (30%)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateNilaiAkhir($get, $set);
                            }),
                        TextInput::make('nilai_uts')
                            ->label('Nilai UTS (30%)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateNilaiAkhir($get, $set);
                            }),
                        TextInput::make('nilai_uas')
                            ->label('Nilai UAS (40%)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateNilaiAkhir($get, $set);
                            }),
                    ])->columns(3),
            ]);
    }

    /**
     * Menghitung nilai akhir dan status secara prosedural.
     * Menggunakan helper functions dari app/Helpers/helpers.php.
     */
    protected static function updateNilaiAkhir(Get $get, Set $set): void
    {
        $tugas = (float) $get('nilai_tugas');
        $uts = (float) $get('nilai_uts');
        $uas = (float) $get('nilai_uas');

        // Validasi nilai menggunakan fungsi prosedural
        if (! validasiNilai($tugas) || ! validasiNilai($uts) || ! validasiNilai($uas)) {
            return;
        }

        // Penerapan pemrograman terstruktur (prosedural):
        $nilaiAkhir = hitungNilaiAkhirProsedural($tugas, $uts, $uas);
        $status = tentukanStatusKelulusan($nilaiAkhir);

        $set('nilai_akhir', round($nilaiAkhir, 2));
        $set('status', $status);
    }
}
