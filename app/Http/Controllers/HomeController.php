<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;  // Make sure you import the Menu model

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // Ambil menu berdasarkan role yang dimiliki oleh user
        $menus = Menu::whereHas('roles', function ($query) use ($user) {
            $query->where('roles.id', $user->role_id);
        })->get();

        return view('home', compact('menus'));
    }
}
