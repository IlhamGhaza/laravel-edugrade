<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiswa extends CreateRecord
{
    protected static string $resource = SiswaResource::class;
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
