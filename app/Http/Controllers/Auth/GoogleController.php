<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani kembalian dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah email ini sudah pernah terdaftar sebelumnya
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // JIKA USER BARU: Buat data baru. 
                // Di saat fungsi create() ini berjalan, SENSOR VOUCHER KITA AKAN MENYALA OTOMATIS! 🔥
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'profile_picture' => $googleUser->getAvatar(), // Keren nih, sekalian tarik foto profil dari Google
                    'password' => bcrypt(Str::random(16)), // Beri password acak karena login via Google
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]);
            } else {
                // JIKA USER LAMA: Update google_id nya saja kalau sebelumnya register manual
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            // Login-kan usernya
            Auth::login($user);

            // Arahkan ke halaman profile
            return redirect()->route('profile.index')->with('success', 'Berhasil login dengan Google!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}