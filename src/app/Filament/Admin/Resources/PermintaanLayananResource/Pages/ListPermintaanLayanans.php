<?php

namespace App\Filament\Admin\Resources\PermintaanLayananResource\Pages;

use App\Filament\Admin\Resources\PermintaanLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermintaanLayanans extends ListRecords
{
    protected static string $resource = PermintaanLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Permintaan'),
        ];
    }
}