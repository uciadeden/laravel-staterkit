<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
        $user = Auth::user(); // ambil data user yang sedang login
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
    	$user = auth()->user();

    	$request->validate([
    		'name' => 'required|string|max:255',
    		'email' => 'required|email|max:255|unique:users,email,' . $user->id,
    		'password' => 'nullable|string|min:6|confirmed',
    	]);

    	$user->name = $request->name;
    	$user->email = $request->email;

    	if ($request->filled('password')) {
    		$user->password = Hash::make($request->password);
    	}

    	$user->save();

    	return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
