<?php

namespace Database\Seeders;

use App\Models\PengaturanLayanan;
use Illuminate\Database\Seeder;

class PengaturanLayananSeeder extends Seeder
{
    public function run(): void
    {
        PengaturanLayanan::firstOrCreate(
            ['nama_pengaturan' => 'default'],
            [
                'titik_awal_basecamp' => 'Kampus Esa Unggul Citra Raya',
                'latitude_basecamp' => null,
                'longitude_basecamp' => null,
                'biaya_flat_satu_km' => 7000,
                'biaya_per_km' => 5000,
                'surcharge_express_per_dua_km' => 10000,
                'google_maps_api_enabled' => true,
                'batas_simpan_dokumen_hari' => 30,
            ]
        );
    }
}