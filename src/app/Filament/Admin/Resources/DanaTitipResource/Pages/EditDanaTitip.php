<?php

namespace App\Filament\Admin\Resources\DanaTitipResource\Pages;

use App\Filament\Admin\Resources\DanaTitipResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDanaTitip extends EditRecord
{
    protected static string $resource = DanaTitipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('selesaikan_rekonsiliasi')
                ->label('Selesaikan Rekonsiliasi')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Rekonsiliasi Akhir')
                ->modalDescription('Sistem akan menghitung selisih dana titip dan menandai pesanan sebagai selesai.')
                ->modalSubmitActionLabel('Selesaikan')
                ->action(function (): void {
                    $this->record->update([
                        'status_dana_titip' => 'selesai',
                    ]);

                    $order = $this->record->order;

                    if ($order) {
                        $order->update([
                            'status_order' => 'selesai',
                        ]);

                        $order->penugasanKurir?->update([
                            'status_penugasan' => 'selesai',
                        ]);

                        if ($order->pembayaran && $order->pembayaran->metode_pembayaran === 'cod') {
                            $order->pembayaran->update([
                                'status_pembayaran' => 'lunas',
                                'jumlah_bayar' => $order->total_biaya_jasa,
                                'tanggal_bayar' => now(),
                            ]);
                        }
                    }

                    $this->refreshFormData([
                        'status_dana_titip',
                        'selisih_dana',
                    ]);

                    $selisih = $this->record->fresh()->selisih_dana;

                    $pesanSelisih = match (true) {
                        $selisih > 0 => 'Kembalikan Rp ' . number_format($selisih, 0, ',', '.') . ' ke pelanggan.',
                        $selisih < 0 => 'Tagih Rp ' . number_format(abs($selisih), 0, ',', '.') . ' ke pelanggan.',
                        default => 'Dana titip pas, tidak ada selisih.',
                    };

                    Notification::make()
                        ->title('Rekonsiliasi selesai')
                        ->body($pesanSelisih)
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}