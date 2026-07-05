<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    public function getTotalDistanceKm(
        string $basecamp,
        string $alamatEksekusi,
        string $alamatTujuan
    ): float {
        $distanceBasecampToEksekusi = $this->getDistanceKm(
            origin: $basecamp,
            destination: $alamatEksekusi
        );

        $distanceEksekusiToTujuan = $this->getDistanceKm(
            origin: $alamatEksekusi,
            destination: $alamatTujuan
        );

        return round($distanceBasecampToEksekusi + $distanceEksekusiToTujuan, 2);
    }

    private function getDistanceKm(string $origin, string $destination): float
    {
        $apiKey = config('services.google_maps.key');
        $enabled = config('services.google_maps.enabled');

        if (! $enabled) {
            throw new Exception('Google Maps API sedang dinonaktifkan di pengaturan sistem.');
        }

        if (blank($apiKey)) {
            throw new Exception('Google Maps API key belum dikonfigurasi di file .env.');
        }

        $response = Http::timeout(15)->get('https://maps.googleapis.com/maps/api/distancematrix/json', [
            'origins' => $origin,
            'destinations' => $destination,
            'key' => $apiKey,
            'units' => 'metric',
            'region' => 'id',
            'language' => 'id',
        ]);

        if (! $response->successful()) {
            throw new Exception('Gagal menghubungi Google Maps API.');
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'OK') {
            throw new Exception('Google Maps API gagal memproses request.');
        }

        $element = $data['rows'][0]['elements'][0] ?? null;

        if (! $element || ($element['status'] ?? null) !== 'OK') {
            throw new Exception('Google Maps API tidak dapat menemukan jarak untuk alamat tersebut.');
        }

        $distanceInMeters = $element['distance']['value'] ?? null;

        if (! $distanceInMeters) {
            throw new Exception('Data jarak dari Google Maps API tidak tersedia.');
        }

        return $distanceInMeters / 1000;
    }
}