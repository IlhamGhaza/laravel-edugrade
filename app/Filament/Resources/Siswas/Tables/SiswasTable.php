<?php

namespace App\Filament\Resources\Siswas\Tables;

use App\Models\Siswa;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

/**
 * Table schema untuk SiswaResource.
 * Menampilkan daftar siswa dengan user relation.
 */
class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->label('Nama User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('kelas')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options(fn () => Siswa::query()
                        ->distinct()
                        ->pluck('kelas', 'kelas')
                        ->toArray()
                    ),
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
                // DeleteAction::make(),
                // ForceDeleteAction::make(),
                // RestoreAction::make(),
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
