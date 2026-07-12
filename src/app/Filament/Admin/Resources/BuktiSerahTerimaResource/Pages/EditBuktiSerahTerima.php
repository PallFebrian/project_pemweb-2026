<?php

namespace App\Filament\Admin\Resources\BuktiSerahTerimaResource\Pages;

use App\Filament\Admin\Resources\BuktiSerahTerimaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuktiSerahTerima extends EditRecord
{
    protected static string $resource = BuktiSerahTerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
