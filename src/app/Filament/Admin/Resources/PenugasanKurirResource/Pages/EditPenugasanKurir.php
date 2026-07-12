<?php

namespace App\Filament\Admin\Resources\PenugasanKurirResource\Pages;

use App\Filament\Admin\Resources\PenugasanKurirResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

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
                ->modalDescription(
                    'Status penugasan akan berubah menjadi Berjalan dan status pesanan menjadi Dalam Perjalanan.'
                )
                ->modalSubmitActionLabel('Mulai')
                ->visible(
                    fn (): bool =>
                        $this->record->status_penugasan === 'menunggu'
                )
                ->action(function (): void {
                    DB::transaction(function (): void {
                        $this->record->update([
                            'status_penugasan' => 'berjalan',
                            'waktu_berangkat' => now(),
                        ]);

                        $this->record->order?->update([
                            'kurir_id' => $this->record->kurir_id,
                            'status_order' => 'dalam_perjalanan',
                        ]);
                    });

                    $this->record->refresh();

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_berangkat',
                    ]);

                    Notification::make()
                        ->title('Perjalanan dimulai')
                        ->body(
                            'Status pesanan telah berubah menjadi Dalam Perjalanan.'
                        )
                        ->success()
                        ->send();
                }),

            Actions\Action::make('tiba_di_eksekusi')
                ->label('Tiba di Eksekusi')
                ->icon('heroicon-o-map-pin')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Tiba di Lokasi Eksekusi')
                ->modalDescription(
                    'Waktu sampai di lokasi eksekusi akan dicatat.'
                )
                ->modalSubmitActionLabel('Simpan')
                ->visible(
                    fn (): bool =>
                        $this->record->status_penugasan
                        === 'berjalan'
                )
                ->action(function (): void {
                    DB::transaction(function (): void {
                        $this->record->update([
                            'status_penugasan' =>
                                'sampai_eksekusi',

                            'waktu_sampai_eksekusi' =>
                                now(),
                        ]);

                        $this->record->order?->update([
                            'kurir_id' => $this->record->kurir_id,
                            'status_order' => 'dalam_perjalanan',
                        ]);
                    });

                    $this->record->refresh();

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_sampai_eksekusi',
                    ]);

                    Notification::make()
                        ->title('Kurir tiba di lokasi eksekusi')
                        ->body(
                            'Status pesanan tetap Dalam Perjalanan.'
                        )
                        ->success()
                        ->send();
                }),

            Actions\Action::make('tiba_di_tujuan')
                ->label('Tiba di Tujuan')
                ->icon('heroicon-o-flag')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Tiba di Tujuan Akhir')
                ->modalDescription(
                    'Waktu sampai di alamat tujuan akan dicatat.'
                )
                ->modalSubmitActionLabel('Simpan')
                ->visible(
                    fn (): bool =>
                        $this->record->status_penugasan
                        === 'sampai_eksekusi'
                )
                ->action(function (): void {
                    DB::transaction(function (): void {
                        $this->record->update([
                            'status_penugasan' =>
                                'sampai_tujuan',

                            'waktu_sampai_tujuan' =>
                                now(),
                        ]);

                        /*
                         * Pesanan belum langsung selesai.
                         * Masih menunggu bukti serah terima
                         * dan penyelesaian oleh admin.
                         */
                        $this->record->order?->update([
                            'kurir_id' => $this->record->kurir_id,
                            'status_order' => 'dalam_perjalanan',
                        ]);
                    });

                    $this->record->refresh();

                    $this->refreshFormData([
                        'status_penugasan',
                        'waktu_sampai_tujuan',
                    ]);

                    Notification::make()
                        ->title('Kurir telah sampai tujuan')
                        ->body(
                            'Silakan unggah bukti serah terima. Pesanan akan diselesaikan oleh admin.'
                        )
                        ->success()
                        ->send();
                }),
        ];
    }
}