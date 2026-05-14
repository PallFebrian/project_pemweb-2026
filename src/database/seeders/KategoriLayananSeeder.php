<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use Illuminate\Database\Seeder;

class KategoriLayananSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'Beli Makanan',
                'slug' => 'beli-makanan',
                'deskripsi' => 'Layanan untuk membantu membeli makanan atau minuman di sekitar kampus.',
                'biaya_normal' => 5000,
                'biaya_express' => 10000,
                'estimasi_normal' => '30 - 60 menit',
                'estimasi_express' => '15 - 30 menit',
                'aktif' => true,
            ],
            [
                'nama' => 'Ambil Paket',
                'slug' => 'ambil-paket',
                'deskripsi' => 'Layanan untuk membantu mengambil paket atau barang titipan.',
                'biaya_normal' => 7000,
                'biaya_express' => 12000,
                'estimasi_normal' => '30 - 60 menit',
                'estimasi_express' => '15 - 30 menit',
                'aktif' => true,
            ],
            [
                'nama' => 'Cetak Dokumen',
                'slug' => 'cetak-dokumen',
                'deskripsi' => 'Layanan untuk membantu mencetak dokumen, tugas, atau materi kuliah.',
                'biaya_normal' => 6000,
                'biaya_express' => 11000,
                'estimasi_normal' => '30 - 60 menit',
                'estimasi_express' => '15 - 30 menit',
                'aktif' => true,
            ],
            [
                'nama' => 'Jasa Suruh',
                'slug' => 'jasa-suruh',
                'deskripsi' => 'Layanan untuk membantu menjalankan keperluan tertentu sesuai permintaan mahasiswa.',
                'biaya_normal' => 10000,
                'biaya_express' => 15000,
                'estimasi_normal' => '60 - 120 menit',
                'estimasi_express' => '30 - 60 menit',
                'aktif' => true,
            ],
        ];

        foreach ($kategori as $item) {
            KategoriLayanan::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}