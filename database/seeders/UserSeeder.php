<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Mendapatkan Role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Membuat User Admin jika belum ada
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password')
            ]
        );

        // Menambahkan Role ke User dengan menyetel model_type secara manual
        $adminUser->roles()->attach($adminRole->id, [
            'model_type' => get_class($adminUser), // Menambahkan nama model secara eksplisit
        ]);

        // Membuat User biasa jika belum ada
        $normalUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'password' => bcrypt('password')
            ]
        );

        // Menambahkan Role ke User dengan menyetel model_type secara manual
        $normalUser->roles()->attach($userRole->id, [
            'model_type' => get_class($normalUser), // Menambahkan nama model secara eksplisit
        ]);
    }
}
