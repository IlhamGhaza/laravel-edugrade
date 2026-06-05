<?php

namespace App\Filament\Resources\Nilais\Pages;

use App\Filament\Resources\Nilais\NilaiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListNilais extends ListRecords
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ExportAction::make()
            //     ->label('Export Excel')
            //     ->exports([
            //         ExcelExport::make('table')
            //             ->fromTable()
            //             ->askForFilename()
            //             ->withFilename(fn ($filename) => $filename ?: 'nilai-siswa-' . date('Y-m-d'))
            //             ->except([
            //                 'created_at', 'updated_at', 'deleted_at',
            //             ]),
            //     ]),
            CreateAction::make(),
        ];
    }
}
