<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleMapsService
{
    private string $apiKey;

    private bool $enabled;

    public function __construct()
    {
        $this->apiKey = (string) config(
            'services.google_maps.api_key',
            ''
        );

        $this->enabled = (bool) config(
            'services.google_maps.enabled',
            false
        );
    }

    public function getTotalDistanceKm(
        string $basecamp,
        string $alamatEksekusi,
        string $alamatTujuan
    ): float {
        if (! $this->enabled) {
            throw new RuntimeException(
                'Google Maps API sedang dinonaktifkan.'
            );
        }

        if (blank($this->apiKey)) {
            throw new RuntimeException(
                'Google Maps API key belum diisi.'
            );
        }

        if (
            blank($basecamp)
            || blank($alamatEksekusi)
            || blank($alamatTujuan)
        ) {
            throw new RuntimeException(
                'Alamat basecamp, eksekusi, dan tujuan wajib diisi.'
            );
        }

        $jarakBasecampKeEksekusi = $this->getDistanceMeters(
            origin: $basecamp,
            destination: $alamatEksekusi
        );

        $jarakEksekusiKeTujuan = $this->getDistanceMeters(
            origin: $alamatEksekusi,
            destination: $alamatTujuan
        );

        $totalMeter = $jarakBasecampKeEksekusi
            + $jarakEksekusiKeTujuan;

        return round($totalMeter / 1000, 2);
    }

    private function getDistanceMeters(
        string $origin,
        string $destination
    ): int {
        $response = Http::timeout(15)
            ->retry(2, 500)
            ->get(
                'https://maps.googleapis.com/maps/api/distancematrix/json',
                [
                    'origins' => $origin,
                    'destinations' => $destination,
                    'mode' => 'driving',
                    'language' => 'id',
                    'region' => 'id',
                    'key' => $this->apiKey,
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Gagal menghubungi layanan Google Maps.'
            );
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'OK') {
            $pesan = $data['error_message']
                ?? $data['status']
                ?? 'Unknown error';

            throw new RuntimeException(
                'Google Maps API gagal: ' . $pesan
            );
        }

        $element = $data['rows'][0]['elements'][0] ?? null;

        if (
            ! is_array($element)
            || ($element['status'] ?? null) !== 'OK'
        ) {
            throw new RuntimeException(
                'Rute antara alamat tidak dapat ditemukan.'
            );
        }

        $jarakMeter = $element['distance']['value'] ?? null;

        if (! is_numeric($jarakMeter)) {
            throw new RuntimeException(
                'Data jarak dari Google Maps tidak valid.'
            );
        }

        return (int) $jarakMeter;
    }
}