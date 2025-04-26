<?php
namespace App\Http\Controllers;

use App\Models\Menu;  // Make sure you import the Menu model
use App\Models\Role;  // Make sure you import the Menu model
use App\Models\User;  // Make sure you import the Menu model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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
    if (Auth::user()->hasRole('Admin')) {
        return view('dashboard-admin');
    } elseif (Auth::user()->hasRole('User')) {
        return view('dashboard-user');
    }

        return view('home');
    }

    /**
     * Get the sidebar menus based on the user's role.
     *
     * @param $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
}
