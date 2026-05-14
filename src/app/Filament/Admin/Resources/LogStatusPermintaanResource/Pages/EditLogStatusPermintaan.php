<?php

namespace App\Filament\Admin\Resources\LogStatusPermintaanResource\Pages;

use App\Filament\Admin\Resources\LogStatusPermintaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLogStatusPermintaan extends EditRecord
{
    protected static string $resource = LogStatusPermintaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
