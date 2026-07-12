<?php

namespace App\Filament\Admin\Resources\LayananJasaSuruhResource\Pages;

use App\Filament\Admin\Resources\LayananJasaSuruhResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLayananJasaSuruh extends EditRecord
{
    protected static string $resource = LayananJasaSuruhResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
