<?php

namespace App\Filament\Admin\Resources\PenugasanKurirResource\Pages;

use App\Filament\Admin\Resources\PenugasanKurirResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenugasanKurirs extends ListRecords
{
    protected static string $resource = PenugasanKurirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
