<?php

namespace App\Filament\Resources\Gurus\Schemas;

use App\Models\Guru;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GuruInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('id_guru'),
                TextEntry::make('nama_guru'),
                TextEntry::make('mata_pelajaran'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Guru $record): bool => $record->trashed()),
            ]);
    }
}
