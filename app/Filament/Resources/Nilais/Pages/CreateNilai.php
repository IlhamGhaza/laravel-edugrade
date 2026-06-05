<?php

namespace App\Filament\Resources\Nilais\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use App\Models\Guru;
use Filament\Resources\Pages\CreateRecord;

class CreateNilai extends CreateRecord
{
    protected static string $resource = NilaiResource::class;

    /**
     * Pastikan guru_id selalu terisi untuk user guru,
     * meskipun field di-disable (dehydrated seharusnya handle ini,
     * tapi ini sebagai fallback keamanan).
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        if ($user && $user->hasRole('guru')) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $data['guru_id'] = $guru->id;
            }
        }

        return $data;
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
