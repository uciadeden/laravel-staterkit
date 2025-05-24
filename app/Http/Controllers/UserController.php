<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function __construct()
    {
        // Menggunakan middleware 'check.permission' dengan permission yang sesuai
        $this->middleware('check.permission:create users')->only(['create', 'store']);
        $this->middleware('check.permission:edit users')->only(['edit', 'update']);
        $this->middleware('check.permission:delete users')->only(['destroy']);
        $this->middleware('check.permission:view users')->only(['index']);
    }

    // Menampilkan daftar user
    public function index(Request $request)
    {
        $title="users";
        $users = User::all(); // Bisa diganti dengan query untuk menampilkan user tertentu

        if ($request->ajax()) {
        $users = User::all(); // Atau gunakan query builder jika Anda membutuhkan data yang lebih spesifik
        return DataTables::of($users)
        ->addColumn('action', function($row) use($title) {
            $editUrl = route('users.edit', $row->id);
            $deleteUrl = route('users.destroy', $row->id);
            return view('components.action-delete', compact('row', 'editUrl', 'deleteUrl', 'title'));
        })
        ->make(true);
    }
    return view('users.index', compact('users','title'));
}

    // Menampilkan form untuk membuat user baru
public function create()
{
    return view('users.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255|unique:users,email', // Validasi email unik
        'password' => 'required|string|min:4',
        'role' => 'required|string|in:Admin,User', // Validasi role hanya bisa "Admin" atau "User"
    ],[
        'name.required' => 'Nama wajib diisi.',
        'email.required' => 'Email/Username wajib diisi.',
        'email.unique' => 'Email/Username sudah digunakan.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 4 karakter.',
        'role.required' => 'Role harus dipilih.',
        'role.in' => 'Role tidak valid. Pilih Admin atau User.'
    ]);

    // Cek apakah email sudah ada
    $check = User::where('email', $request->email)->count();

    if ($check > 0) {
        return response()->json([
            'message' => 'Username sudah ada.',
            'icon' => 'error',
            'title' => 'Mengalami kesalahan',
        ]);
    } else {
        // Membuat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'created_by' => Auth::id(), // Menyimpan ID pengguna yang membuat user
        ]);

        // Pastikan Role Admin dan User ada
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Menetapkan role ke user
        if ($request->role == "Admin") {
                    // Menambahkan Role ke User dengan menyetel model_type secara manual
            $user->roles()->attach($adminRole->id, [
            'model_type' => get_class($user), // Menambahkan nama model secara eksplisit
        ]);
        } else if ($request->role == "User") {

        // Menambahkan Role ke User dengan menyetel model_type secara manual
            $user->roles()->attach($userRole->id, [
            'model_type' => get_class($user), // Menambahkan nama model secara eksplisit
        ]);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.',
            'icon' => 'success',
            'title' => 'Berhasil',
            'user' => $user
        ]);
    }
}


    // Menampilkan form untuk mengedit user

public function edit(User $user)
{
    // Mengembalikan data user dalam format JSON untuk digunakan dalam AJAX
    return response()->json($user);
}

    // Menyimpan perubahan user
public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'updated_by' => Auth::id(), // Menyimpan ID pengguna yang membuat user
    ]);

    return response()->json([
        'message' => 'Data berhasil diperbaharui.', 
        'user' => $user,
        'icon' => 'success',
        'title' => 'Berhasil',
    ]);
}

    // Menghapus user
public function destroy(User $user)
{
    // Perbarui kolom updated_by dengan ID pengguna yang sedang login
    // $user->updated_by = Auth::id();  // Menyimpan ID pengguna yang menghapus
    $user->save();  // Simpan perubahan

    //Delete
    $user->delete();
    return response()->json([
        'message' => 'Data berhasil dihapus.',
        'icon' => 'success',
        'title' => 'Hapus!',
    ]);
}
}
