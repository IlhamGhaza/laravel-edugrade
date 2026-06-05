<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\Siswa;
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
                    ->options(function (?Siswa $record) {
                        // Ambil user_id yang sudah dipakai oleh siswa lain
                        $usedUserIds = Siswa::query()
                            ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                            ->whereNotNull('user_id')
                            ->pluck('user_id')
                            ->toArray();

                        return User::query()
                            ->whereHas('roles', fn ($query) => $query->where('name', 'siswa'))
                            ->whereNotIn('id', $usedUserIds)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
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
                Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
