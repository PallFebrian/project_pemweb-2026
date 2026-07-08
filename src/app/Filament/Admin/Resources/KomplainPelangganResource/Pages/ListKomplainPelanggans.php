<?php

namespace App\Filament\Admin\Resources\KomplainPelangganResource\Pages;

use App\Filament\Admin\Resources\KomplainPelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomplainPelanggans extends ListRecords
{
    protected static string $resource = KomplainPelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
