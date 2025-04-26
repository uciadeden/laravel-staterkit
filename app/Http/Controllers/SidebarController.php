<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function getSidebarMenus()
    {
        $user = auth()->user();

        // Ambil menu berdasarkan role yang dimiliki oleh user
        $menus = Menu::whereHas('roles', function ($query) use ($user) {
            $query->where('roles.id', $user->role_id);
        })->get();

        return view('layouts.sidebar', compact('menus'));
    }
}
