<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\PengaturanLayanan;
use App\Services\GoogleMapsService;
use App\Services\PriceCalculator;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('hitung_jarak_google_maps')
                ->label('Hitung Jarak Google Maps')
                ->icon('heroicon-o-map-pin')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Hitung Jarak dengan Google Maps API')
                ->modalDescription('Sistem akan menghitung jarak rute Basecamp → Alamat Eksekusi → Alamat Tujuan, lalu menghitung biaya jasa secara otomatis.')
                ->modalSubmitActionLabel('Hitung Sekarang')
                ->action(function (): void {
                    try {
                        $setting = PengaturanLayanan::query()->first();

                        if (! $setting) {
                            throw new \Exception('Pengaturan layanan belum tersedia.');
                        }

                        if (! $setting->google_maps_api_enabled) {
                            throw new \Exception('Google Maps API sedang dinonaktifkan pada pengaturan layanan.');
                        }

                        if (blank($setting->titik_awal_basecamp)) {
                            throw new \Exception('Titik awal basecamp belum diisi pada pengaturan layanan.');
                        }

                        $jarakKm = app(GoogleMapsService::class)->getTotalDistanceKm(
                            basecamp: $setting->titik_awal_basecamp,
                            alamatEksekusi: $this->record->alamat_eksekusi,
                            alamatTujuan: $this->record->alamat_tujuan,
                        );

                        $pricing = app(PriceCalculator::class)->calculate(
                            jarakKm: $jarakKm,
                            isExpress: $this->record->pilihan_layanan === 'express'
                        );

                        $this->record->update([
                            'total_jarak_km' => $jarakKm,
                            'sumber_jarak' => 'api',
                            'status_api_maps' => 'success',
                            'biaya_jasa' => $pricing['biaya_jasa'],
                            'biaya_express' => $pricing['biaya_express'],
                            'total_biaya_jasa' => $pricing['total_biaya_jasa'],
                        ]);

                        $this->refreshFormData([
                            'total_jarak_km',
                            'sumber_jarak',
                            'status_api_maps',
                            'biaya_jasa',
                            'biaya_express',
                            'total_biaya_jasa',
                        ]);

                        Notification::make()
                            ->title('Jarak dan biaya berhasil dihitung')
                            ->body('Sumber jarak disimpan sebagai Google Maps API.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        $this->record->update([
                            'status_api_maps' => 'failed',
                        ]);

                        $this->refreshFormData([
                            'status_api_maps',
                        ]);

                        Notification::make()
                            ->title('Google Maps API gagal')
                            ->body($e->getMessage() . ' Silakan gunakan input jarak manual.')
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('input_jarak_manual')
                ->label('Input Jarak Manual')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->form([
                    Forms\Components\TextInput::make('total_jarak_km')
                        ->label('Jarak Manual')
                        ->numeric()
                        ->suffix('KM')
                        ->required()
                        ->minValue(0.1),
                ])
                ->action(function (array $data): void {
                    $jarakKm = (float) $data['total_jarak_km'];

                    $pricing = app(PriceCalculator::class)->calculate(
                        jarakKm: $jarakKm,
                        isExpress: $this->record->pilihan_layanan === 'express'
                    );

                    $this->record->update([
                        'total_jarak_km' => $jarakKm,
                        'sumber_jarak' => 'manual',
                        'status_api_maps' => 'manual',
                        'biaya_jasa' => $pricing['biaya_jasa'],
                        'biaya_express' => $pricing['biaya_express'],
                        'total_biaya_jasa' => $pricing['total_biaya_jasa'],
                    ]);

                    $this->refreshFormData([
                        'total_jarak_km',
                        'sumber_jarak',
                        'status_api_maps',
                        'biaya_jasa',
                        'biaya_express',
                        'total_biaya_jasa',
                    ]);

                    Notification::make()
                        ->title('Jarak manual dan biaya berhasil dihitung')
                        ->body('Sumber jarak disimpan sebagai manual.')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}