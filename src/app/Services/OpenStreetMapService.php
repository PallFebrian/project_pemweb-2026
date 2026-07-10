<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenStreetMapService
{
    public function getTotalDistanceKm(
        string $basecamp,
        string $alamatEksekusi,
        string $alamatTujuan
    ): float {
        return $this->getRouteData(
            $basecamp,
            $alamatEksekusi,
            $alamatTujuan
        )['distance_km'];
    }

    public function getRouteData(
        string $basecamp,
        string $alamatEksekusi,
        string $alamatTujuan
    ): array {
        if (
            blank($basecamp)
            || blank($alamatEksekusi)
            || blank($alamatTujuan)
        ) {
            throw new RuntimeException(
                'Alamat basecamp, eksekusi, dan tujuan wajib diisi.'
            );
        }

        $koordinatBasecamp = $this->getCoordinates($basecamp);
        $koordinatEksekusi = $this->getCoordinates($alamatEksekusi);
        $koordinatTujuan = $this->getCoordinates($alamatTujuan);

        $rutePertama = $this->getRoute(
            $koordinatBasecamp,
            $koordinatEksekusi
        );

        $ruteKedua = $this->getRoute(
            $koordinatEksekusi,
            $koordinatTujuan
        );

        $garisRute = array_merge(
            $rutePertama['coordinates'],
            array_slice($ruteKedua['coordinates'], 1)
        );

        $totalMeter = $rutePertama['distance']
            + $ruteKedua['distance'];

        return [
            'distance_km' => round($totalMeter / 1000, 2),

            'points' => [
                'basecamp' => [
                    'lat' => $koordinatBasecamp['latitude'],
                    'lng' => $koordinatBasecamp['longitude'],
                    'label' => 'Basecamp',
                ],
                'eksekusi' => [
                    'lat' => $koordinatEksekusi['latitude'],
                    'lng' => $koordinatEksekusi['longitude'],
                    'label' => 'Lokasi Eksekusi',
                ],
                'tujuan' => [
                    'lat' => $koordinatTujuan['latitude'],
                    'lng' => $koordinatTujuan['longitude'],
                    'label' => 'Lokasi Tujuan',
                ],
            ],

            'route' => $garisRute,
        ];
    }

    private function getCoordinates(string $alamat): array
    {
        $response = Http::timeout(20)
            ->retry(2, 500)
            ->withHeaders([
                'User-Agent' => 'ESA-Runner/1.0',
                'Accept-Language' => 'id',
            ])
            ->get(
                'https://nominatim.openstreetmap.org/search',
                [
                    'q' => $alamat,
                    'format' => 'jsonv2',
                    'limit' => 1,
                    'countrycodes' => 'id',
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Gagal mencari koordinat alamat.'
            );
        }

        $hasil = $response->json();

        if (
            ! is_array($hasil)
            || empty($hasil)
            || ! isset($hasil[0]['lat'], $hasil[0]['lon'])
        ) {
            throw new RuntimeException(
                'Alamat tidak ditemukan: ' . $alamat
            );
        }

        if (
            ! is_numeric($hasil[0]['lat'])
            || ! is_numeric($hasil[0]['lon'])
        ) {
            throw new RuntimeException(
                'Koordinat alamat tidak valid.'
            );
        }

        return [
            'latitude' => (float) $hasil[0]['lat'],
            'longitude' => (float) $hasil[0]['lon'],
        ];
    }

    private function getRoute(
        array $asal,
        array $tujuan
    ): array {
        $koordinat = sprintf(
            '%s,%s;%s,%s',
            $asal['longitude'],
            $asal['latitude'],
            $tujuan['longitude'],
            $tujuan['latitude']
        );

        $response = Http::timeout(20)
            ->retry(2, 500)
            ->get(
                'https://router.project-osrm.org/route/v1/driving/'
                    . $koordinat,
                [
                    'overview' => 'full',
                    'geometries' => 'geojson',
                    'steps' => 'false',
                    'alternatives' => 'false',
                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Gagal menghitung rute perjalanan.'
            );
        }

        $data = $response->json();

        if (($data['code'] ?? null) !== 'Ok') {
            throw new RuntimeException(
                'Rute perjalanan tidak ditemukan.'
            );
        }

        $jarakMeter = $data['routes'][0]['distance'] ?? null;
        $koordinatRute =
            $data['routes'][0]['geometry']['coordinates'] ?? null;

        if (
            ! is_numeric($jarakMeter)
            || ! is_array($koordinatRute)
        ) {
            throw new RuntimeException(
                'Data rute perjalanan tidak valid.'
            );
        }

        $garisRute = array_map(
            fn (array $titik): array => [
                (float) $titik[1],
                (float) $titik[0],
            ],
            $koordinatRute
        );

        return [
            'distance' => (float) $jarakMeter,
            'coordinates' => $garisRute,
        ];
    }
}