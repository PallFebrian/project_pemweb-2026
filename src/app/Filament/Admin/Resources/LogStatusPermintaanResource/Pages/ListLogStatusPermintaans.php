<?php

namespace App\Filament\Admin\Resources\LogStatusPermintaanResource\Pages;

use App\Filament\Admin\Resources\LogStatusPermintaanResource;
use Filament\Resources\Pages\ListRecords;

class ListLogStatusPermintaans extends ListRecords
{
    protected static string $resource = LogStatusPermintaanResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}