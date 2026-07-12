<?php

namespace Tests\Feature;

use App\Services\GoogleMapsService;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

class GoogleMapsServiceTest extends TestCase
{
    public function test_menghitung_total_jarak_dari_dua_rute(): void
    {
        config()->set([
            'services.google_maps.enabled' => true,
            'services.google_maps.api_key' => 'fake-api-key',
        ]);

        Http::fakeSequence()
            ->push([
                'status' => 'OK',
                'rows' => [
                    [
                        'elements' => [
                            [
                                'status' => 'OK',
                                'distance' => [
                                    'value' => 2500,
                                ],
                            ],
                        ],
                    ],
                ],
            ])
            ->push([
                'status' => 'OK',
                'rows' => [
                    [
                        'elements' => [
                            [
                                'status' => 'OK',
                                'distance' => [
                                    'value' => 3750,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

        $service = app(GoogleMapsService::class);

        $totalJarak = $service->getTotalDistanceKm(
            'Basecamp ESA Runner',
            'Alamat Eksekusi',
            'Alamat Tujuan'
        );

        $this->assertSame(6.25, $totalJarak);
        Http::assertSentCount(2);
    }

    public function test_menolak_perhitungan_saat_api_dinonaktifkan(): void
    {
        config()->set([
            'services.google_maps.enabled' => false,
            'services.google_maps.api_key' => 'fake-api-key',
        ]);

        $service = app(GoogleMapsService::class);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            'Google Maps API sedang dinonaktifkan.'
        );

        $service->getTotalDistanceKm(
            'Basecamp ESA Runner',
            'Alamat Eksekusi',
            'Alamat Tujuan'
        );
    }
}
