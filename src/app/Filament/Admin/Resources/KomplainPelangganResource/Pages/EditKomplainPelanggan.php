<?php

namespace App\Filament\Admin\Resources\KomplainPelangganResource\Pages;

use App\Filament\Admin\Resources\KomplainPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomplainPelanggan extends EditRecord
{
    protected static string $resource = KomplainPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
