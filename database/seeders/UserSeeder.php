<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

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

    // Check if the user already has the admin role
    if (!$adminUser->roles->contains($adminRole)) {
        // Menambahkan role secara eksplisit dengan model_type
        $adminUser->roles()->attach($adminRole->id, [
            'model_type' => get_class($adminUser) // Menambahkan nama model secara eksplisit
        ]);
    }

    // Membuat User biasa jika belum ada
    $normalUser = User::firstOrCreate(
        ['email' => 'user@example.com'],
        [
            'name' => 'Normal User',
            'password' => bcrypt('password')
        ]
    );

    // Check if the user already has the user role
    if (!$normalUser->roles->contains($userRole)) {
        // Menambahkan role secara eksplisit dengan model_type
        $normalUser->roles()->attach($userRole->id, [
            'model_type' => get_class($normalUser) // Menambahkan nama model secara eksplisit
        ]);
    }
}


}

