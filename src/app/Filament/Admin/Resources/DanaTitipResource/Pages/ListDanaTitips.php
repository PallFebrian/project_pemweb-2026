<?php

namespace App\Filament\Admin\Resources\DanaTitipResource\Pages;

use App\Filament\Admin\Resources\DanaTitipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDanaTitips extends ListRecords
{
    protected static string $resource = DanaTitipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
