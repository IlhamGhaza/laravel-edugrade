<?php

namespace App\Filament\Resources\Nilais\Schemas;

use App\Models\Nilai;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NilaiInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Relasi')
                    ->schema([
                        TextEntry::make('siswa.nama')
                            ->label('Siswa'),
                        TextEntry::make('siswa.kelas.nama_kelas')
                            ->label('Kelas')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('guru.nama_guru')
                            ->label('Guru'),
                        TextEntry::make('mataPelajaran.nama_mapel')
                            ->label('Mata Pelajaran')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('semester')
                            ->label('Semester')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Ganjil' => 'primary',
                                'Genap' => 'warning',
                                default => 'gray',
                            })
                            ->placeholder('-'),
                    ])->columns(3),

                Section::make('Nilai')
                    ->schema([
                        TextEntry::make('nilai_tugas')
                            ->label('Nilai Tugas (30%)')
                            ->numeric(),
                        TextEntry::make('nilai_uts')
                            ->label('Nilai UTS (30%)')
                            ->numeric(),
                        TextEntry::make('nilai_uas')
                            ->label('Nilai UAS (40%)')
                            ->numeric(),
                        TextEntry::make('nilai_akhir')
                            ->label('Nilai Akhir')
                            ->numeric()
                            ->weight('bold')
                            ->placeholder('-'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Lulus' => 'success',
                                'Tidak Lulus' => 'danger',
                                default => 'gray',
                            })
                            ->placeholder('-'),
                    ])->columns(5),

                Section::make('Informasi Waktu')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Dihapus')
                            ->dateTime('d M Y H:i')
                            ->visible(fn (Nilai $record): bool => $record->trashed()),
                    ])->columns(3)
                    ->collapsible(),
            ]);
    }
}
