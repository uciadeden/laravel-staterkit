<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Memanggil Seeder untuk Role dan Permission
        $this->call(RolePermissionSeeder::class);

        // Memanggil Seeder untuk User
        $this->call(MenuSeeder::class);
        
        // Memanggil Seeder untuk User
        $this->call(UserSeeder::class);
    }
}
