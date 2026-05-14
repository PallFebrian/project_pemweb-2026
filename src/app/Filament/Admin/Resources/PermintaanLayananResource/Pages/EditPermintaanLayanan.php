<?php

namespace App\Filament\Admin\Resources\PermintaanLayananResource\Pages;

use App\Filament\Admin\Resources\PermintaanLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermintaanLayanan extends EditRecord
{
    protected static string $resource = PermintaanLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}