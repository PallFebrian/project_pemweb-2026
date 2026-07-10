<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $kurirRole = Role::firstOrCreate([
            'name' => 'kurir',
            'guard_name' => 'web',
        ]);

        $pemilikBisnisRole = Role::firstOrCreate([
            'name' => 'pemilik_bisnis',
            'guard_name' => 'web',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@jastip.test'],
            [
                'name' => 'Admin ESA Runner',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'nim' => null,
                'alamat' => 'Basecamp ESA Runner',
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $admin->syncRoles([$superAdminRole]);

        $pemilik = User::updateOrCreate(
            ['email' => 'owner@jastip.test'],
            [
                'name' => 'Pemilik Bisnis',
                'password' => Hash::make('password'),
                'no_hp' => '081234567891',
                'nim' => null,
                'alamat' => 'Basecamp ESA Runner',
                'role' => 'pemilik_bisnis',
                'status' => 'aktif',
            ]
        );

        $pemilik->syncRoles([$pemilikBisnisRole]);

        $kurir = User::updateOrCreate(
            ['email' => 'mahasiswa@jastip.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
                'no_hp' => '081234567892',
                'nim' => '20240801001',
                'alamat' => 'Universitas Esa Unggul Citra Raya',
                'role' => 'kurir',
                'status' => 'aktif',
            ]
        );

        $kurir->syncRoles([$kurirRole]);
    }
}