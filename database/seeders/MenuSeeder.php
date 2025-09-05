<?php
namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Buat menu
        $dashboardMenu = Menu::firstOrCreate([
            'name' => 'Dashboard', 
            'url' => '/dashboard', 
            'icon' => 'fas fa-tachometer-alt',
            'category' =>  null
        ]);
        // Buat menu
        $penggunaMenu = Menu::firstOrCreate([
            'name' => 'Pengguna', 
            'url' => '/users', 
            'icon' => 'fas fa-user',
            'category' =>  null
        ]);

        $userManagementMenu = Menu::firstOrCreate([
        	'name' => 'Post', 
        	'url' => '/posts', 
        	'icon' => 'fas fa-database',
        	'category' => 'Master Data'
        ]);

        // Buat role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Berikan menu ke role
        $adminRole->menus()->syncWithoutDetaching([
        	$dashboardMenu->id, 
        	$penggunaMenu->id,
        ]);
        $userRole->menus()->syncWithoutDetaching([$dashboardMenu->id]); // Role 'User' hanya memiliki akses ke dashboard
    }
}
