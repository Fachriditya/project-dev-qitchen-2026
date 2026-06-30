<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $role = $request->role;
        
        $users = User::when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                return $query->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8', // Password wajib saat create
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,customer,cashier,kitchen,courier',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Siapkan data
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        // Jika ada upload foto profil
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_photos', 'public');
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User invited successfully!');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi, kecualikan email user ini dari aturan unique
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:8', // Password opsional saat edit
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:super_admin,admin,customer,cashier,kitchen,courier',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        // Jika password diisi, berarti user ingin ganti password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Jika upload foto baru
        if ($request->hasFile('profile_picture')) {
            // Hapus yang lama dulu
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_photos', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Hapus fotonya juga dari server (Opsional, tapi bagus untuk hemat storage)
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}