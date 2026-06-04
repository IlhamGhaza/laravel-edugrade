<?php

namespace App\Filament\Resources\Nilais\Schemas;

use App\Models\Nilai;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NilaiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('siswa_id')
                    ->numeric(),
                TextEntry::make('guru_id')
                    ->numeric(),
                TextEntry::make('nilai_tugas')
                    ->numeric(),
                TextEntry::make('nilai_uts')
                    ->numeric(),
                TextEntry::make('nilai_uas')
                    ->numeric(),
                TextEntry::make('nilai_akhir')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Nilai $record): bool => $record->trashed()),
            ]);
    }
}
