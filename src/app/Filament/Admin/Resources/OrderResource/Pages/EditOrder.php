<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\PengaturanLayanan;
use App\Models\User;
use App\Services\OpenStreetMapService;
use App\Services\PriceCalculator;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verifikasi_pesanan')
                ->label('Verifikasi Pesanan')
                ->icon('heroicon-o-check-badge')
                ->color('primary')
                ->visible(
                    fn (): bool =>
                        $this->record->status_order === 'menunggu_verifikasi'
                        && $this->isAdmin()
                )
                ->requiresConfirmation()
                ->modalHeading('Verifikasi Pesanan')
                ->modalDescription(
                    'Sistem akan menentukan apakah pesanan harus menunggu dana titip atau bisa langsung ditugaskan kepada kurir.'
                )
                ->modalSubmitActionLabel('Verifikasi Sekarang')
                ->action(function (): void {
                    $this->record->load('jenisLayanan');

                    $layanan = $this->record->jenisLayanan;

                    if (! $layanan) {
                        Notification::make()
                            ->title('Verifikasi gagal')
                            ->body(
                                'Jenis layanan pada pesanan belum tersedia.'
                            )
                            ->danger()
                            ->send();

                        return;
                    }

                    if (
                        blank($this->record->total_jarak_km)
                        || (int) $this->record->total_biaya_jasa <= 0
                    ) {
                        Notification::make()
                            ->title('Jarak dan biaya belum dihitung')
                            ->body(
                                'Hitung jarak otomatis atau input jarak manual terlebih dahulu.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    $statusBaru = $layanan->butuh_dana_titip
                        ? 'menunggu_dana_titip'
                        : 'menunggu_kurir';

                    $this->record->update([
                        'status_order' => $statusBaru,
                    ]);

                    $this->refreshFormData([
                        'status_order',
                    ]);

                    Notification::make()
                        ->title('Pesanan berhasil diverifikasi')
                        ->body(
                            $layanan->butuh_dana_titip
                                ? 'Pesanan membutuhkan dana titip sebelum ditugaskan kepada kurir.'
                                : 'Pesanan tidak membutuhkan dana titip dan siap ditugaskan kepada kurir.'
                        )
                        ->success()
                        ->send();
                }),

            Actions\Action::make(
                'selesaikan_pesanan_tanpa_dana_titip'
            )
                ->label('Selesaikan Pesanan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(function (): bool {
                    $layanan = $this->record->jenisLayanan;

                    return $this->isAdmin()
                        && $layanan
                        && ! $layanan->butuh_dana_titip
                        && $this->record->status_order
                            === 'dalam_perjalanan';
                })
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Pesanan')
                ->modalDescription(
                    'Pastikan kurir sudah sampai tujuan dan bukti serah terima sudah diunggah.'
                )
                ->modalSubmitActionLabel('Selesaikan')
                ->action(function (): void {
                    $this->record->load([
                        'jenisLayanan',
                        'penugasanKurir',
                        'buktiSerahTerimas',
                        'pembayaran',
                    ]);

                    $layanan = $this->record->jenisLayanan;

                    if (! $layanan) {
                        Notification::make()
                            ->title(
                                'Pesanan tidak dapat diselesaikan'
                            )
                            ->body(
                                'Jenis layanan tidak ditemukan.'
                            )
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($layanan->butuh_dana_titip) {
                        Notification::make()
                            ->title(
                                'Gunakan rekonsiliasi dana titip'
                            )
                            ->body(
                                'Pesanan ini membutuhkan dana titip dan harus diselesaikan melalui menu Dana Titip.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    $penugasan = $this->record->penugasanKurir;

                    if (! $penugasan) {
                        Notification::make()
                            ->title(
                                'Penugasan kurir belum tersedia'
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
                                'Pesanan baru dapat diselesaikan setelah kurir sampai di tujuan.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    if (
                        $this->record
                            ->buktiSerahTerimas
                            ->isEmpty()
                    ) {
                        Notification::make()
                            ->title(
                                'Bukti serah terima belum tersedia'
                            )
                            ->body(
                                'Kurir harus mengunggah bukti serah terima terlebih dahulu.'
                            )
                            ->warning()
                            ->send();

                        return;
                    }

                    DB::transaction(
                        function () use ($penugasan): void {
                            if (
                                $penugasan->status_penugasan
                                !== 'selesai'
                            ) {
                                $penugasan->update([
                                    'status_penugasan' => 'selesai',
                                ]);
                            }

                            $this->record->update([
                                'status_order' => 'selesai',
                            ]);

                            $pembayaran =
                                $this->record->pembayaran;

                            if (
                                $pembayaran
                                && $pembayaran
                                    ->metode_pembayaran === 'cod'
                            ) {
                                $pembayaran->update([
                                    'status_pembayaran' => 'lunas',

                                    'jumlah_bayar' =>
                                        $this->record
                                            ->total_biaya_jasa,

                                    'tanggal_bayar' => now(),
                                ]);
                            }
                        }
                    );

                    $this->refreshFormData([
                        'status_order',
                    ]);

                    Notification::make()
                        ->title(
                            'Pesanan berhasil diselesaikan'
                        )
                        ->body(
                            'Status pesanan dan penugasan kurir telah menjadi selesai.'
                        )
                        ->success()
                        ->send();
                }),

            Actions\Action::make('hitung_jarak_otomatis')
                ->label('Hitung Jarak Otomatis')
                ->icon('heroicon-o-map-pin')
                ->color('success')
                ->visible(
                    fn (): bool =>
                        $this->record->status_order
                            === 'menunggu_verifikasi'
                        && $this->isAdmin()
                )
                ->requiresConfirmation()
                ->modalHeading('Hitung Jarak Otomatis')
                ->modalDescription(
                    'Sistem akan menghitung rute Basecamp → Alamat Eksekusi → Alamat Tujuan menggunakan OpenStreetMap dan OSRM, lalu menghitung biaya jasa secara otomatis.'
                )
                ->modalSubmitActionLabel('Hitung Sekarang')
                ->action(function (): void {
                    try {
                        $setting = PengaturanLayanan::query()
                            ->first();

                        if (! $setting) {
                            throw new \RuntimeException(
                                'Pengaturan layanan belum tersedia.'
                            );
                        }

                        if (
                            blank(
                                $setting->titik_awal_basecamp
                            )
                        ) {
                            throw new \RuntimeException(
                                'Titik awal basecamp belum diisi pada pengaturan layanan.'
                            );
                        }

                        $dataRute = app(
                            OpenStreetMapService::class
                        )->getRouteData(
                            basecamp:
                                $setting->titik_awal_basecamp,

                            alamatEksekusi:
                                $this->record
                                    ->alamat_eksekusi,

                            alamatTujuan:
                                $this->record
                                    ->alamat_tujuan,
                        );

                        $jarakKm =
                            (float) $dataRute['distance_km'];

                        $pricing = app(
                            PriceCalculator::class
                        )->calculate(
                            jarakKm: $jarakKm,

                            isExpress:
                                $this->record
                                    ->pilihan_layanan
                                === 'express'
                        );

                        $this->record->update([
                            'total_jarak_km' => $jarakKm,

                            'sumber_jarak' => 'api',

                            'status_api_maps' => 'success',

                            'data_peta' => $dataRute,

                            'biaya_jasa' =>
                                $pricing['biaya_jasa'],

                            'biaya_express' =>
                                $pricing['biaya_express'],

                            'total_biaya_jasa' =>
                                $pricing[
                                    'total_biaya_jasa'
                                ],
                        ]);

                        $this->refreshFormData([
                            'total_jarak_km',
                            'sumber_jarak',
                            'status_api_maps',
                            'data_peta',
                            'biaya_jasa',
                            'biaya_express',
                            'total_biaya_jasa',
                        ]);

                        Notification::make()
                            ->title(
                                'Jarak dan biaya berhasil dihitung'
                            )
                            ->body(
                                'Jarak dan rute berhasil dihitung menggunakan OpenStreetMap dan OSRM.'
                            )
                            ->success()
                            ->send();
                        $this->redirect(
                            OrderResource::getUrl('edit', [
                                'record' => $this->record,
                            ])
                        );
                    } catch (\Throwable $e) {
                        $this->record->update([
                            'status_api_maps' => 'failed',
                            'data_peta' => null,
                        ]);

                        $this->refreshFormData([
                            'status_api_maps',
                            'data_peta',
                        ]);

                        Notification::make()
                            ->title(
                                'Perhitungan jarak otomatis gagal'
                            )
                            ->body(
                                $e->getMessage()
                                . ' Silakan periksa alamat atau gunakan input jarak manual.'
                            )
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('input_jarak_manual')
                ->label('Input Jarak Manual')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->visible(
                    fn (): bool =>
                        $this->record->status_order
                            === 'menunggu_verifikasi'
                        && $this->isAdmin()
                )
                ->form([
                    Forms\Components\TextInput::make(
                        'total_jarak_km'
                    )
                        ->label('Jarak Manual')
                        ->numeric()
                        ->suffix('KM')
                        ->required()
                        ->minValue(0.1),
                ])
                ->action(function (array $data): void {
                    $jarakKm =
                        (float) $data['total_jarak_km'];

                    $pricing = app(
                        PriceCalculator::class
                    )->calculate(
                        jarakKm: $jarakKm,

                        isExpress:
                            $this->record
                                ->pilihan_layanan
                            === 'express'
                    );

                    $this->record->update([
                        'total_jarak_km' => $jarakKm,

                        'sumber_jarak' => 'manual',

                        'status_api_maps' => 'manual',

                        'data_peta' => null,

                        'biaya_jasa' =>
                            $pricing['biaya_jasa'],

                        'biaya_express' =>
                            $pricing['biaya_express'],

                        'total_biaya_jasa' =>
                            $pricing[
                                'total_biaya_jasa'
                            ],
                    ]);

                    $this->refreshFormData([
                        'total_jarak_km',
                        'sumber_jarak',
                        'status_api_maps',
                        'data_peta',
                        'biaya_jasa',
                        'biaya_express',
                        'total_biaya_jasa',
                    ]);

                    Notification::make()
                        ->title(
                            'Jarak manual dan biaya berhasil dihitung'
                        )
                        ->body(
                            'Sumber jarak disimpan sebagai manual.'
                        )
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->visible(
                    fn (): bool => $this->isAdmin()
                ),
        ];
    }

    private function isAdmin(): bool
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $user->hasAnyRole([
            'super_admin',
            'admin',
        ]) || in_array(
            $user->role,
            [
                'super_admin',
                'admin',
            ],
            true
        );
    }
}