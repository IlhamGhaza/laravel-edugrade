<?php

namespace App\Filament\Resources\Nilais\Tables;

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

/**
 * Table schema untuk NilaiResource.
 * Menampilkan data nilai dengan relasi siswa & guru, status berwarna.
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
                TextColumn::make('siswa.kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('guru.nama_guru')
                    ->label('Guru')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('guru.mata_pelajaran')
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
                SelectFilter::make('status')
                    ->label('Status Kelulusan')
                    ->options([
                        'Lulus' => 'Lulus',
                        'Tidak Lulus' => 'Tidak Lulus',
                    ]),
                SelectFilter::make('guru_id')
                    ->label('Guru')
                    ->relationship('guru', 'nama_guru')
                    ->preload()
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
                    ->action(fn (Post $record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
