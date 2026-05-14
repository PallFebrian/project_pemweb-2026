<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use App\Models\PermintaanLayanan;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermintaanLayananSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'mahasiswa@jastip.test')->first();

        $kategoriMakanan = KategoriLayanan::where('slug', 'beli-makanan')->first();
        $kategoriPaket = KategoriLayanan::where('slug', 'ambil-paket')->first();
        $kategoriDokumen = KategoriLayanan::where('slug', 'cetak-dokumen')->first();

        if (! $user || ! $kategoriMakanan || ! $kategoriPaket || ! $kategoriDokumen) {
            return;
        }

        PermintaanLayanan::updateOrCreate(
            ['kode' => 'REQ-2026-0001'],
            [
                'user_id' => $user->id,
                'kategori_layanan_id' => $kategoriMakanan->id,
                'nama_pemesan' => 'Mahasiswa Demo',
                'no_hp' => '081234567892',
                'judul' => 'Titip beli makanan di kantin',
                'deskripsi' => 'Tolong belikan nasi ayam geprek level sedang dan es teh manis.',
                'lokasi_awal' => 'Kantin Kampus',
                'lokasi_tujuan' => 'Gedung Fakultas Ilmu Komputer',
                'tipe_layanan' => 'normal',
                'biaya_layanan' => $kategoriMakanan->biaya_normal,
                'status' => 'baru',
                'catatan_admin' => null,
                'whatsapp_url' => null,
            ]
        );

        PermintaanLayanan::updateOrCreate(
            ['kode' => 'REQ-2026-0002'],
            [
                'user_id' => $user->id,
                'kategori_layanan_id' => $kategoriPaket->id,
                'nama_pemesan' => 'Mahasiswa Demo',
                'no_hp' => '081234567892',
                'judul' => 'Ambil paket di pos satpam',
                'deskripsi' => 'Paket atas nama Mahasiswa Demo, tolong ambilkan di pos satpam kampus.',
                'lokasi_awal' => 'Pos Satpam Kampus',
                'lokasi_tujuan' => 'Lobby Gedung Utama',
                'tipe_layanan' => 'express',
                'biaya_layanan' => $kategoriPaket->biaya_express,
                'status' => 'diproses',
                'catatan_admin' => 'Sudah dihubungi melalui WhatsApp.',
                'whatsapp_url' => null,
            ]
        );

        PermintaanLayanan::updateOrCreate(
            ['kode' => 'REQ-2026-0003'],
            [
                'user_id' => $user->id,
                'kategori_layanan_id' => $kategoriDokumen->id,
                'nama_pemesan' => 'Mahasiswa Demo',
                'no_hp' => '081234567892',
                'judul' => 'Cetak dokumen tugas',
                'deskripsi' => 'Cetak dokumen PDF sebanyak 10 halaman hitam putih.',
                'lokasi_awal' => 'Tempat Fotocopy Dekat Kampus',
                'lokasi_tujuan' => 'Kelas CR001',
                'tipe_layanan' => 'normal',
                'biaya_layanan' => $kategoriDokumen->biaya_normal,
                'status' => 'selesai',
                'catatan_admin' => 'Permintaan sudah selesai.',
                'whatsapp_url' => null,
            ]
        );
    }
}