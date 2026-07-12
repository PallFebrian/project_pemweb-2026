<?php

namespace App\Filament\Admin\Resources\LayananJasaSuruhResource\Pages;

use App\Filament\Admin\Resources\LayananJasaSuruhResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLayananJasaSuruhs extends ListRecords
{
    protected static string $resource = LayananJasaSuruhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
