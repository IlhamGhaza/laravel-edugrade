<?php

namespace App\Filament\Resources\Nilais\Schemas;

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
                    ->description('Pilih siswa dan guru yang terkait dengan nilai ini.')
                    ->schema([
                        Select::make('siswa_id')
                            ->label('Siswa')
                            ->relationship('siswa', 'nama')
                    ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('guru_id')
                            ->label('Guru / Mata Pelajaran')
                            ->relationship('guru', 'nama_guru')
                    ->required()
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                // Auto-select guru jika user yang login adalah guru
                                $user = auth()->user();
                                if ($user && $user->hasRole('guru')) {
                                    $guru = \App\Models\Guru::where('user_id', $user->id)->first();
                                    return $guru?->id;
                                }
                                return null;
                            }),
                    ])->columns(2),

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

                // Section::make('Hasil Perhitungan')
                //     ->description('Nilai akhir dan status kelulusan dihitung otomatis oleh sistem.')
                //     ->schema([
                // TextInput::make('nilai_akhir')
                //             ->label('Nilai Akhir')
                //             ->numeric()
                //             ->disabled()
                //             ->helperText('Dihitung otomatis: (30% × Tugas) + (30% × UTS) + (40% × UAS)'),
                //         TextInput::make('status')
                //             ->label('Status Kelulusan')
                //             ->disabled()
                //             ->helperText('Lulus jika Nilai Akhir ≥ 70'),
                //     ])->columns(2),
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
        if (!validasiNilai($tugas) || !validasiNilai($uts) || !validasiNilai($uas)) {
            return;
        }

        // Penerapan pemrograman terstruktur (prosedural):
        $nilaiAkhir = hitungNilaiAkhirProsedural($tugas, $uts, $uas);
        $status = tentukanStatusKelulusan($nilaiAkhir);

        $set('nilai_akhir', round($nilaiAkhir, 2));
        $set('status', $status);
    }
}
