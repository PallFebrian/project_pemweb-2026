<?php

namespace App\Filament\Admin\Resources\PermintaanLayananResource\Pages;

use App\Filament\Admin\Resources\PermintaanLayananResource;
use App\Models\LogStatusPermintaan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePermintaanLayanan extends CreateRecord
{
    protected static string $resource = PermintaanLayananResource::class;

    protected function afterCreate(): void
    {
        LogStatusPermintaan::create([
            'permintaan_layanan_id' => $this->record->id,
            'user_id' => Auth::id(),
            'status_lama' => null,
            'status_baru' => $this->record->status ?? 'baru',
            'catatan' => 'Permintaan dibuat melalui admin.',
        ]);
    }
}