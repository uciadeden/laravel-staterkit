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
    		'password' => 'nullable|string|min:6',
    	]);
		    // Verifikasi password lama jika diisi
		if ($request->filled('password_confirmation')) {
			if (!Hash::check($request->password_confirmation, $user->password)) {
				return back()->withErrors(['password_confirmation' => 'Konfirmasi Password Lama tidak cocok.']);
			}

			if ($request->filled('password')) {
				$user->password = Hash::make($request->password);
			}
		}else{

			if ($request->filled('password')) {
				return back()->withErrors(['password_confirmation' => 'Konfirmasi Password Harus diisi.']);
			}
		}

    	$user->name = $request->name;
    	$user->email = $request->email;

    	$user->save();

    	return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
