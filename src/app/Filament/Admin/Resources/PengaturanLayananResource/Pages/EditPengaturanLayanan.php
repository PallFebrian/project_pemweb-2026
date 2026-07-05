<?php

namespace App\Filament\Admin\Resources\PengaturanLayananResource\Pages;

use App\Filament\Admin\Resources\PengaturanLayananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengaturanLayanan extends EditRecord
{
    protected static string $resource = PengaturanLayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
