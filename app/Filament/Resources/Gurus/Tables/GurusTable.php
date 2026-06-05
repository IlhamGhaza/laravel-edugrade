<?php

namespace App\Filament\Resources\Gurus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

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
                TextColumn::make('id_guru')
                    ->label('ID Guru')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('nama_guru')
                    ->label('Nama Guru')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->numeric()
                    ->label('Nama User')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('mataPelajarans.nama_mapel')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
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
                SelectFilter::make('mataPelajarans')
                    ->label('Filter Mapel')
                    ->relationship('mataPelajarans', 'nama_mapel')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->exports([
                            ExcelExport::make('table')
                                ->fromTable()
                                ->withFilename(fn () => 'data-guru-'.date('Y-m-d')),
                        ]),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
