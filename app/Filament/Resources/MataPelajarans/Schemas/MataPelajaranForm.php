<?php

namespace App\Filament\Resources\MataPelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MataPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_mapel')
                    ->label('Kode Mapel')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->placeholder('Contoh: MTK, BIN, IPA'),
                TextInput::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
