<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * Form schema untuk SiswaResource.
 * Menampilkan field user_id, NIS, nama, dan kelas dengan validasi.
 */
class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required()
                    ->options(fn () => User::query()
                        ->whereHas('roles', fn ($query) => $query->where('name', 'siswa'))
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih akun user yang terhubung dengan siswa ini.'),
                TextInput::make('nis')
                    ->label('NIS')
                    ->disabled()
                    ->placeholder('Otomatis di-generate saat simpan')
                    ->helperText('NIS akan di-generate otomatis oleh sistem.'),
                TextInput::make('nama')
                    ->label('Nama Siswa')
                    ->required()
                    ->maxLength(255),
                Select::make('kelas')
                    ->label('Kelas')
                    ->options([
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ])
                    ->required(),
            ]);
    }
}
