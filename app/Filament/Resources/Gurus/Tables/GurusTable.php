<?php

namespace App\Filament\Resources\Gurus\Tables;

use App\Models\Guru;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * Table schema untuk GuruResource.
 * Menampilkan data guru dengan relasi user dan filter mata pelajaran.
 */
class GurusTable
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
                TextColumn::make('id_guru')
                    ->label('ID Guru')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('nama_guru')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('user.name')
                    ->label('Akun User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('mata_pelajaran')
                    ->label('Filter Mapel')
                    ->options(fn () => Guru::query()
                        ->distinct()
                        ->pluck('mata_pelajaran', 'mata_pelajaran')
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
