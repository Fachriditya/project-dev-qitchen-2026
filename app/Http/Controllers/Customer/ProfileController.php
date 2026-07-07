<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    // Mesin 1: Update Profil
    public function updateProfile(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id() . ',user_id', 
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar maks 2MB
        ]);

        $user = User::find(Auth::id());

        // 2. Siapkan data teks yang mau diupdate
        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // 3. Logika Upload Gambar
        if ($request->hasFile('profile_picture')) {
            // Hapus gambar lama kalau sebelumnya sudah punya foto
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Simpan gambar baru ke folder 'profiles' di dalam folder public storage
            $path = $request->file('profile_picture')->store('profiles', 'public');
            
            // Masukkan path gambar ke array update
            $dataToUpdate['profile_picture'] = $path;
        }

        // 4. Update ke database
        $user->update($dataToUpdate);

        return back()->with('success', 'Profile information updated successfully!');
    }

    // Mesin 2: Update Password
    public function updatePassword(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'current_password' => 'required|current_password', // Laravel otomatis ngecek kecocokan password lama
            'password' => 'required|min:8|confirmed', // Harus ada input 'password_confirmation' dan minimal 8 huruf
        ]);

        // 2. Update password dengan enkripsi (Hash)
        $user = User::find(Auth::id());
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // 3. Kembalikan dengan pesan sukses
        return back()->with('success', 'Password changed successfully!');
    }
}