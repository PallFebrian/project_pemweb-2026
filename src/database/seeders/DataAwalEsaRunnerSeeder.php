<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use App\Models\LayananJasaSuruh;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\PengaturanLayanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DataAwalEsaRunnerSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function (): void {
            /*
             * Role internal sesuai sistem ESA RUNNER.
             */
            $superAdminRole = Role::firstOrCreate([
                'name' => 'super_admin',
                'guard_name' => 'web',
            ]);

            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            $kurirRole = Role::firstOrCreate([
                'name' => 'kurir',
                'guard_name' => 'web',
            ]);

            $pemilikRole = Role::firstOrCreate([
                'name' => 'pemilik_bisnis',
                'guard_name' => 'web',
            ]);

            /*
             * User demo.
             * Password hanya diisi jika user belum pernah dibuat.
             */
            $admin = User::firstOrCreate(
                ['email' => 'admin@jastip.test'],
                [
                    'name' => 'Admin Jastip',
                    'password' => Hash::make('password'),
                ]
            );

            $pemilik = User::firstOrCreate(
                ['email' => 'owner@jastip.test'],
                [
                    'name' => 'Owner Jastip',
                    'password' => Hash::make('password'),
                ]
            );

            $kurir = User::firstOrCreate(
                ['email' => 'mahasiswa@jastip.test'],
                [
                    'name' => 'Mahasiswa Demo',
                    'password' => Hash::make('password'),
                ]
            );

            $admin->syncRoles([
                $superAdminRole,
                $adminRole,
            ]);

            $pemilik->syncRoles([
                $pemilikRole,
            ]);

            $kurir->syncRoles([
                $kurirRole,
            ]);

            /*
             * Kategori layanan.
             */
            $kategoriMakanan = KategoriLayanan::updateOrCreate(
                ['slug' => 'makanan'],
                [
                    'nama' => 'Makanan',
                    'deskripsi' => 'Kategori layanan pembelian makanan.',
                    'biaya_normal' => 0,
                    'biaya_express' => 0,
                    'estimasi_normal' => '30-45 menit',
                    'estimasi_express' => '15-30 menit',
                    'aktif' => true,
                ]
            );

            /*
             * Layanan jasa suruh aktif.
             */
            $layanan = LayananJasaSuruh::updateOrCreate(
                ['slug' => 'nitip-beli-makanan'],
                [
                    'kategori_layanan_id' => $kategoriMakanan->id,
                    'nama_layanan' => 'Nitip Beli Makanan',
                    'deskripsi' => 'Layanan untuk membantu membeli makanan.',
                    'harga_dasar' => 0,
                    'satuan' => 'per pesanan',
                    'bisa_express' => true,
                    'status' => true,
                ]
            );

            /*
             * Pengaturan biaya dan basecamp.
             */
            PengaturanLayanan::updateOrCreate(
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

            /*
             * Pelanggan demo.
             */
            $pelanggan = Pelanggan::updateOrCreate(
                ['nomor_whatsapp' => '081234567890'],
                [
                    'nama' => 'Pelanggan Tes',
                ]
            );

            /*
             * Order demo.
             * kode_order otomatis dibuat oleh model Order.
             */
            Order::firstOrCreate(
                [
                    'nomor_whatsapp' => $pelanggan->nomor_whatsapp,
                    'detail_barang' => 'Nasi ayam satu porsi - data awal testing',
                ],
                [
                    'pelanggan_id' => $pelanggan->id,
                    'nama_pelanggan' => $pelanggan->nama,
                    'jenis_layanan_id' => $layanan->id,
                    'alamat_eksekusi' => 'Warung Makan Citra Raya',
                    'alamat_tujuan' => 'Kampus Esa Unggul Citra Raya',
                    'pilihan_layanan' => 'normal',
                    'biaya_jasa' => 0,
                    'biaya_express' => 0,
                    'total_biaya_jasa' => 0,
                    'status_order' => 'menunggu_verifikasi',
                    'admin_id' => $admin->id,
                    'kurir_id' => null,
                    'tanggal_order' => now(),
                ]
            );
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info('Data awal ESA RUNNER berhasil dibuat.');
        $this->command?->info('Admin: admin@jastip.test');
        $this->command?->info('Kurir: mahasiswa@jastip.test');
        $this->command?->info('Password user baru: password');
    }
}