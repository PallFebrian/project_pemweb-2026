<?php

namespace App\Filament\Admin\Resources\DanaTitipResource\Pages;

use App\Filament\Admin\Resources\DanaTitipResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

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
                ->visible(
                    fn (): bool =>
                        $this->record->status_dana_titip !== 'selesai'
                )
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Rekonsiliasi Akhir')
                ->modalDescription(
                    'Pastikan kurir sudah sampai tujuan, bukti serah terima sudah diunggah, dan pemakaian dana sudah dicatat.'
                )
                ->modalSubmitActionLabel('Selesaikan')
                ->action(function (): void {
                    $this->record->load([
                        'order.penugasanKurir',
                        'order.buktiSerahTerimas',
                        'order.pembayaran',
                    ]);

                    $order = $this->record->order;

                    if (! $order) {
                        Notification::make()
                            ->title('Rekonsiliasi gagal')
                            ->body('Data pesanan tidak ditemukan.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $penugasan = $order->penugasanKurir;

                    if (! $penugasan) {
                        Notification::make()
                            ->title('Rekonsiliasi belum dapat dilakukan')
                            ->body(
                                'Pesanan belum memiliki penugasan kurir.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    if (! in_array(
                        $penugasan->status_penugasan,
                        [
                            'sampai_tujuan',
                            'selesai',
                        ],
                        true
                    )) {
                        Notification::make()
                            ->title('Kurir belum sampai tujuan')
                            ->body(
                                'Rekonsiliasi hanya dapat diselesaikan setelah kurir sampai di tujuan.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    if ($order->buktiSerahTerimas->isEmpty()) {
                        Notification::make()
                            ->title('Bukti serah terima belum tersedia')
                            ->body(
                                'Kurir harus mengunggah bukti serah terima terlebih dahulu.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    $danaDiterima = (int) (
                        $this->record->dana_diterima ?? 0
                    );

                    $danaTerpakai = $this->record->dana_terpakai;

                    if ($danaDiterima <= 0) {
                        Notification::make()
                            ->title('Dana diterima belum valid')
                            ->body(
                                'Jumlah dana diterima harus lebih dari Rp0.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    if ($danaTerpakai === null) {
                        Notification::make()
                            ->title('Dana terpakai belum diisi')
                            ->body(
                                'Isi jumlah dana yang digunakan oleh kurir terlebih dahulu.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    DB::transaction(function () use (
                        $order,
                        $penugasan
                    ): void {
                        $this->record->update([
                            'status_dana_titip' => 'selesai',
                        ]);

                        if (
                            $penugasan->status_penugasan
                            !== 'selesai'
                        ) {
                            $penugasan->update([
                                'status_penugasan' => 'selesai',
                            ]);
                        }

                        if ($order->status_order !== 'selesai') {
                            $order->update([
                                'status_order' => 'selesai',
                            ]);
                        }

                        $pembayaran = $order->pembayaran;

                        if (
                            $pembayaran
                            && $pembayaran->metode_pembayaran
                                === 'cod'
                        ) {
                            $pembayaran->update([
                                'status_pembayaran' => 'lunas',
                                'jumlah_bayar' =>
                                    $order->total_biaya_jasa,
                                'tanggal_bayar' => now(),
                            ]);
                        }
                    });

                    $this->refreshFormData([
                        'status_dana_titip',
                        'selisih_dana',
                    ]);

                    $selisih = (int) (
                        $this->record
                            ->fresh()
                            ->selisih_dana
                        ?? 0
                    );

                    $pesanSelisih = match (true) {
                        $selisih > 0 =>
                            'Kembalikan Rp'
                            . number_format(
                                $selisih,
                                0,
                                ',',
                                '.'
                            )
                            . ' kepada pelanggan.',

                        $selisih < 0 =>
                            'Tagih Rp'
                            . number_format(
                                abs($selisih),
                                0,
                                ',',
                                '.'
                            )
                            . ' kepada pelanggan.',

                        default =>
                            'Dana titip pas dan tidak memiliki selisih.',
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