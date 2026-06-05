<?php

namespace App\Filament\Resources\Nilais\Tables;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

/**
 * Table schema untuk NilaiResource.
 * Menampilkan data nilai dengan relasi siswa, guru, mapel, dan status berwarna.
 */
class NilaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ganjil' => 'primary',
                        'Genap' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('guru.nama_guru')
                    ->label('Guru')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('mataPelajaran.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('nilai_tugas')
                    ->label('Tugas')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('nilai_uts')
                    ->label('UTS')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('nilai_uas')
                    ->label('UAS')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('nilai_akhir')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lulus' => 'success',
                        'Tidak Lulus' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('siswa_kelas')
                    ->label('Kelas')
                    ->options(fn () => \App\Models\Kelas::pluck('nama_kelas', 'id')->toArray())
                    ->query(function ($query, array $data) {
                        if (filled($data['value'])) {
                            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']));
                        }
                    }),
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ]),
                SelectFilter::make('status')
                    ->label('Status Kelulusan')
                    ->options([
                        'Lulus' => 'Lulus',
                        'Tidak Lulus' => 'Tidak Lulus',
                    ]),
                SelectFilter::make('guru_id')
                    ->label('Guru')
                    ->options(function () {
                        $user = auth()->user();
                        if ($user && $user->hasRole('guru')) {
                            // Guru hanya melihat dirinya
                            return Guru::where('user_id', $user->id)
                                ->pluck('nama_guru', 'id')
                                ->toArray();
                        }
                        return Guru::pluck('nama_guru', 'id')->toArray();
                    })
                    ->visible(function () {
                        // Sembunyikan filter guru jika yang login adalah guru (tidak perlu filter)
                        $user = auth()->user();
                        return !($user && $user->hasRole('guru'));
                    })
                    ->searchable(),
                SelectFilter::make('mapel_id')
                    ->label('Mata Pelajaran')
                    ->options(function () {
                        $user = auth()->user();
                        if ($user && $user->hasRole('guru')) {
                            $guru = Guru::where('user_id', $user->id)->first();
                            if ($guru) {
                                return MataPelajaran::whereHas('gurus', fn ($q) => $q->where('gurus.id', $guru->id))
                                    ->pluck('nama_mapel', 'id')
                                    ->toArray();
                            }
                            return [];
                        }
                        return MataPelajaran::pluck('nama_mapel', 'id')->toArray();
                    })
                    ->searchable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                 Action::make('delete')
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make('table')
                                ->fromTable()
                                ->withFilename(fn () => 'nilai-siswa-' . date('Y-m-d')),
                        ]),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
