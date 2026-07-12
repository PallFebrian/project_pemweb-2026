<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
        $alamatLower = mb_strtolower($alamat);

        if (
            str_contains($alamatLower, 'universitas esa unggul')
            && str_contains($alamatLower, 'citra raya')
        ) {
            return [
                'latitude' => -6.2728365,
                'longitude' => 106.5265246,
                'display_name' =>
                    'Universitas Esa Unggul Citra Raya, Tangerang',
                'query_used' => $alamat,
            ];
        }

        $queries = $this->buildSearchQueries($alamat);

        foreach ($queries as $query) {
            $cacheKey = 'osm_geocode_'
                . sha1(mb_strtolower($query));

            $hasil = Cache::remember(
                $cacheKey,
                now()->addDays(30),
                function () use ($query): array {
                    $response = Http::timeout(20)
                        ->withHeaders([
                            'User-Agent' =>
                                'ESA-Runner/1.0 student-project',

                            'Accept-Language' => 'id',
                        ])
                        ->get(
                            'https://nominatim.openstreetmap.org/search',
                            [
                                'q' => $query,
                                'format' => 'jsonv2',
                                'limit' => 1,
                                'countrycodes' => 'id',
                                'addressdetails' => 1,
                            ]
                        );

                    if ($response->failed()) {
                        return [];
                    }

                    $data = $response->json();

                    return is_array($data)
                        ? $data
                        : [];
                }
            );

            if (
                isset(
                    $hasil[0]['lat'],
                    $hasil[0]['lon']
                )
                && is_numeric($hasil[0]['lat'])
                && is_numeric($hasil[0]['lon'])
            ) {
                return [
                    'latitude' =>
                        (float) $hasil[0]['lat'],

                    'longitude' =>
                        (float) $hasil[0]['lon'],

                    'display_name' =>
                        $hasil[0]['display_name']
                        ?? $query,

                    'query_used' => $query,
                ];
            }
        }

        throw new RuntimeException(
            'Alamat tidak ditemukan: ' . $alamat
        );
    }

    private function buildSearchQueries(
        string $alamat
    ): array {
        $normalized = preg_replace(
            '/\s+/',
            ' ',
            trim($alamat)
        ) ?? trim($alamat);

        $normalized = str_ireplace(
            [
                'Tangerang Regency',
                'Regency',
            ],
            [
                'Kabupaten Tangerang',
                'Kabupaten',
            ],
            $normalized
        );

        $lower = mb_strtolower($normalized);

        if (
            str_contains($lower, 'tangerang')
            && ! str_contains($lower, 'banten')
        ) {
            $normalized .= ', Banten';
        }

        if (
            ! str_contains(
                mb_strtolower($normalized),
                'indonesia'
            )
        ) {
            $normalized .= ', Indonesia';
        }

        $segments = array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', $normalized)
                )
            )
        );

        $queries = [
            $normalized,
        ];

        /*
        * Kalau nama cluster/perumahan tidak ditemukan,
        * coba pencarian berdasarkan kelurahan dan kecamatan.
        */
        if (count($segments) >= 4) {
            $queries[] = implode(
                ', ',
                array_slice($segments, 1)
            );
        }

        /*
        * Percobaan terakhir menggunakan bagian
        * administrasi alamat yang paling umum.
        */
        if (count($segments) >= 5) {
            $queries[] = implode(
                ', ',
                array_slice($segments, -5)
            );
        }

        return array_values(
            array_unique($queries)
        );
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

        $serverRute = [
            'https://router.project-osrm.org/route/v1/driving/',
            'https://routing.openstreetmap.de/routed-car/route/v1/driving/',
        ];

        $pesanErrorTerakhir = null;

        foreach ($serverRute as $server) {
            try {
                $response = Http::connectTimeout(20)
                    ->timeout(60)
                    ->withOptions([
                        'force_ip_resolve' => 'v4',
                    ])
                    ->acceptJson()
                    ->get(
                        $server . $koordinat,
                        [
                            'overview' => 'full',
                            'geometries' => 'geojson',
                            'steps' => 'false',
                            'alternatives' => 'false',
                        ]
                    );

                if ($response->failed()) {
                    $pesanErrorTerakhir =
                        'Server rute merespons dengan status '
                        . $response->status();

                    continue;
                }

                $data = $response->json();

                if (($data['code'] ?? null) !== 'Ok') {
                    $pesanErrorTerakhir =
                        $data['message']
                        ?? 'Rute perjalanan tidak ditemukan.';

                    continue;
                }

                $jarakMeter =
                    $data['routes'][0]['distance'] ?? null;

                $koordinatRute =
                    $data['routes'][0]['geometry']['coordinates']
                    ?? null;

                if (
                    ! is_numeric($jarakMeter)
                    || ! is_array($koordinatRute)
                    || empty($koordinatRute)
                ) {
                    $pesanErrorTerakhir =
                        'Data rute perjalanan tidak valid.';

                    continue;
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
            } catch (\Throwable $e) {
                $pesanErrorTerakhir = $e->getMessage();
            }
        }

        throw new RuntimeException(
            'Server perhitungan rute sedang sibuk atau tidak dapat dihubungi. '
            . 'Silakan coba kembali beberapa saat lagi.'
            . (
                $pesanErrorTerakhir
                    ? ' Detail: ' . $pesanErrorTerakhir
                    : ''
            )
        );
    }
}