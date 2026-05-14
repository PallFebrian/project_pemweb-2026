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

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $ownerRole = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@jastip.test'],
            [
                'name' => 'Admin Jastip',
                'password' => Hash::make('password'),
                'no_hp' => '081234567890',
                'nim' => null,
                'alamat' => 'Kampus',
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $admin->syncRoles([$superAdminRole]);

        $owner = User::updateOrCreate(
            ['email' => 'owner@jastip.test'],
            [
                'name' => 'Owner Jastip',
                'password' => Hash::make('password'),
                'no_hp' => '081234567891',
                'nim' => null,
                'alamat' => 'Kampus',
                'role' => 'owner',
                'status' => 'aktif',
            ]
        );

        $owner->syncRoles([$ownerRole]);

        $mahasiswa = User::updateOrCreate(
            ['email' => 'mahasiswa@jastip.test'],
            [
                'name' => 'Mahasiswa Demo',
                'password' => Hash::make('password'),
                'no_hp' => '081234567892',
                'nim' => '20240801001',
                'alamat' => 'Universitas Esa Unggul Citra Raya',
                'role' => 'user',
                'status' => 'aktif',
            ]
        );

        $mahasiswa->syncRoles([$userRole]);
    }
}