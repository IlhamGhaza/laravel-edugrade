<?php

namespace App\Filament\Resources\Gurus\Schemas;

use App\Models\Guru;
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
                    ->options(function (?Guru $record) {
                        // Ambil user_id yang sudah dipakai oleh guru lain
                        $usedUserIds = Guru::query()
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->whereNotNull('user_id')
                            ->pluck('user_id')
                            ->toArray();

                        return User::query()
                            ->whereHas('roles', fn ($query) => $query->where('name', 'guru'))
                            ->whereNotIn('id', $usedUserIds)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
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
                Select::make('mataPelajarans')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajarans', 'nama_mapel')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Pilih satu atau lebih mata pelajaran yang diampu guru ini.'),
            ]);
    }
}
