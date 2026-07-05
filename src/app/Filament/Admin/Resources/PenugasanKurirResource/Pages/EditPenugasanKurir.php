<?php

namespace App\Filament\Admin\Resources\PenugasanKurirResource\Pages;

use App\Filament\Admin\Resources\PenugasanKurirResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPenugasanKurir extends EditRecord
{
    protected static string $resource = PenugasanKurirResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mulai_perjalanan')
                ->label('Mulai Perjalanan')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Mulai Perjalanan')
                ->modalDescription('Status penugasan akan berubah menjadi Berjalan dan waktu berangkat akan dicatat.')
                ->modalSubmitActionLabel('Mulai')
                ->visible(fn (): bool => $this->record->status_penugasan === 'menunggu')
                ->action(function (): void {
                    $this->record->update([
                        'status_penugasan' => 'berjalan',
                        'waktu_berangkat' => now(),
                    ]);

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_berangkat',
                    ]);

                    Notification::make()
                        ->title('Perjalanan dimulai')
                        ->body('Status order otomatis berubah menjadi Dalam Perjalanan.')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('tiba_di_eksekusi')
                ->label('Tiba di Eksekusi')
                ->icon('heroicon-o-map-pin')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Tiba di Lokasi Eksekusi')
                ->modalDescription('Waktu sampai di lokasi eksekusi akan dicatat.')
                ->modalSubmitActionLabel('Simpan')
                ->visible(fn (): bool => $this->record->status_penugasan === 'berjalan')
                ->action(function (): void {
                    $this->record->update([
                        'status_penugasan' => 'sampai_eksekusi',
                        'waktu_sampai_eksekusi' => now(),
                    ]);

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_sampai_eksekusi',
                    ]);

                    Notification::make()
                        ->title('Waktu sampai eksekusi tersimpan')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('tiba_di_tujuan')
                ->label('Tiba di Tujuan')
                ->icon('heroicon-o-flag')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Tiba di Tujuan Akhir')
                ->modalDescription('Waktu sampai di alamat tujuan akan dicatat.')
                ->modalSubmitActionLabel('Simpan')
                ->visible(fn (): bool => $this->record->status_penugasan === 'sampai_eksekusi')
                ->action(function (): void {
                    $this->record->update([
                        'status_penugasan' => 'sampai_tujuan',
                        'waktu_sampai_tujuan' => now(),
                    ]);

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_sampai_tujuan',
                    ]);

                    Notification::make()
                        ->title('Waktu sampai tujuan tersimpan')
                        ->body('Setelah ini lanjut ke upload bukti dan rekonsiliasi akhir.')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}