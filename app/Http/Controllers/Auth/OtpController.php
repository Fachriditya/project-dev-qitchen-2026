<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;   

class OtpController extends Controller
{
    public function show(Request $request)
    {
        // Pastikan ada email di session (dikirim dari halaman Register)
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('register');
        }
        
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = $request->session()->get('otp_email');
        $user = User::where('email', $email)->first();

        // Cek apakah user ada dan OTP-nya cocok
        if ($user && $user->otp === $request->otp) {
            
            // 1. Kosongkan OTP (karena sudah terpakai) dan tandai email terverifikasi
            $user->update([
                'otp' => null,
                'email_verified_at' => now()
            ]);

            // 2. Login-kan usernya secara resmi
            Auth::login($user);

            // 3. Hapus session email
            $request->session()->forget('otp_email');

            // 4. Arahkan ke halaman profile dengan sukses
            return redirect()->route('profile.index')->with('success', 'Email berhasil diverifikasi! Selamat datang di Qitchen.');
        }

        // Jika OTP salah
        return back()->withErrors(['otp' => 'Kode OTP salah atau tidak valid.']);
    }

    public function resend(Request $request)
    {
        // 1. Pastikan masih ada sesi emailnya
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('register');
        }

        $email = $request->session()->get('otp_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            // 2. Ciptakan 6 angka OTP yang BARU
            $newOtpCode = sprintf("%06d", mt_rand(1, 999999));

            // 3. Timpa OTP yang lama di database
            $user->update([
                'otp' => $newOtpCode
            ]);

            // 4. Kirim ulang emailnya
            Mail::to($user->email)->send(new OtpMail($newOtpCode));

            // 5. Kembalikan ke halaman OTP dengan pesan sukses
            return back()->with('resend_success', 'Kode OTP baru telah dikirim ke email Anda!');
        }

        return redirect()->route('register')->withErrors(['email' => 'User tidak ditemukan.']);
    }
}