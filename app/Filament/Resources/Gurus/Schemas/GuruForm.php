<?php

namespace App\Filament\Resources\Gurus\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Akun User')
                    ->required()
                    ->relationship('user', 'name')
                    ->options(fn () => User::query()
                        ->whereHas('roles', fn ($query) => $query->where('name', 'guru'))
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->searchable()
                    ->preload(),
                TextInput::make('id_guru')
                    ->label('ID Guru')
                    ->disabled()
                    ->placeholder('Otomatis di-generate saat simpan')
                    ->helperText('ID Guru akan di-generate otomatis oleh sistem.'),
                TextInput::make('nama_guru')
                    ->label('Nama Guru')
                    ->required()
                    ->maxLength(255),
                Select::make('mata_pelajaran')
                    ->label('Mata Pelajaran')
                    ->required()
                    ->options(fn () => \App\Models\Guru::query()
                        ->distinct()
                        ->pluck('mata_pelajaran', 'mata_pelajaran')
                        ->toArray()
                    )
                    ->searchable()
                    ->preload(),
            ]);
    }
}
