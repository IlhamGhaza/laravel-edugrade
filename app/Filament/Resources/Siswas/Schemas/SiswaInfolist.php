<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\Siswa;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('nis'),
                TextEntry::make('nama'),
                TextEntry::make('kelas'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Siswa $record): bool => $record->trashed()),
            ]);
    }
}
