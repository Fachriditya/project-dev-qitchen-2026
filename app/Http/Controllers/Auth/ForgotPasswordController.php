<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail; // 🌟 Kita RE-USE mesin email yang sudah ada!

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan form masukin email
    public function showRequestForm()
    {
        return view('auth.forgot-password'); 
    }

    // 2. Proses kirim OTP ke email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email' // Pastikan emailnya terdaftar!
        ], [
            'email.exists' => 'Waduh, email ini tidak terdaftar di sistem kami.'
        ]);

        // Bikin OTP baru
        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        // Simpan OTP ke database user tersebut
        $user = User::where('email', $request->email)->first();
        $user->update(['otp' => $otpCode]);

        // Kirim email
        Mail::to($user->email)->send(new OtpMail($otpCode));

        // Simpan email di session buat validasi nanti
        $request->session()->put('reset_email', $user->email);

        return redirect()->route('password.verify');
    }

    // 3. Tampilkan form input OTP
    public function showVerifyForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-password-otp');
    }

    // 4. Proses cek kebenaran OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|string|size:6']);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->where('otp', $request->otp)->first();

        if ($user) {
            // OTP Benar! Berikan tiket VIP untuk reset password
            $request->session()->put('can_reset_password', true);
            return redirect()->route('password.reset');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
    }

    // 5. Tampilkan form Buat Password Baru
    public function showResetForm(Request $request)
    {
        // Tolak kalau dia belum berhasil ngelewatin tahap OTP
        if (!$request->session()->has('can_reset_password')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // 6. Proses simpan password baru ke database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        $email = $request->session()->get('reset_email');
        $user = User::where('email', $email)->first();

        // Ganti password dan hanguskan OTP-nya
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null
        ]);

        // Bersihkan sampah session
        $request->session()->forget(['reset_email', 'can_reset_password']);

        // Lempar ke halaman login pakai pesan sukses!
        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}