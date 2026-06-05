<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Imports\SiswaImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ExportAction::make()
            //     ->label('Export Excel')
            //     ->exports([
            //         ExcelExport::make('table')
            //             ->fromTable()
            //             ->askForFilename()
            //             ->withFilename(fn ($filename) => $filename ?: 'data-siswa-' . date('Y-m-d'))
            //             ->except([
            //                 'created_at', 'updated_at', 'deleted_at',
            //             ]),
            //     ]),
            // Action::make('importSiswa')
            //     ->label('Import Excel')
            //     ->icon('heroicon-o-arrow-up-tray')
            //     ->color('success')
            //     ->form([
            //         FileUpload::make('file')
            //             ->label('File Excel')
            //             ->acceptedFileTypes([
            //                 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            //                 'application/vnd.ms-excel',
            //                 'text/csv',
            //             ])
            //             ->required()
            //             ->helperText('Format: .xlsx atau .csv. Kolom: nama, kelas (10/11/12), user_id (opsional).'),
            //     ])
            //     ->action(function (array $data) {
            //         try {
            //             $import = new SiswaImport();
            //             Excel::import($import, storage_path('app/private/' . $data['file']));

            //             Notification::make()
            //                 ->title('Import Berhasil')
            //                 ->body("Berhasil mengimport {$import->importedCount} data siswa.")
            //                 ->success()
            //                 ->send();
            //         } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //             $failures = $e->failures();
            //             $messages = collect($failures)
            //                 ->take(5)
            //                 ->map(fn ($f) => "Baris {$f->row()}: {$f->errors()[0]}")
            //                 ->implode("\n");

            //             Notification::make()
            //                 ->title('Import Gagal — Validasi Error')
            //                 ->body($messages)
            //                 ->danger()
            //                 ->persistent()
            //                 ->send();
            //         } catch (\Exception $e) {
            //             Notification::make()
            //                 ->title('Import Gagal')
            //                 ->body('Terjadi kesalahan: ' . $e->getMessage())
            //                 ->danger()
            //                 ->persistent()
            //                 ->send();
            //         }
            //     }),
            CreateAction::make(),
        ];
    }
}
