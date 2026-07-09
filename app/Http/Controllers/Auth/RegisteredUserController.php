<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        // 1. LOGIKA REFERRAL: Cari ID Pengajak berdasarkan kode yang diketik
        $referrerId = null;
        if ($request->filled('referral_code')) {
            // Cari user yang punya kode tersebut di database
            $referrer = User::where('referral_code', $request->referral_code)->first();
            
            if ($referrer) {
                // Ambil ID-nya! (Ganti 'user_id' jadi 'id' kalau primary key-mu adalah id)
                $referrerId = $referrer->user_id; 
            }
        }

        // 2. Generate OTP
        $otpCode = sprintf("%06d", mt_rand(1, 999999));

        // 3. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'customer',
            'otp' => $otpCode,
            'referred_by' => $referrerId, 
        ]);

        // 4. Kirim Email OTP
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otpCode));

        // 5. Simpan session dan arahkan
        $request->session()->put('otp_email', $user->email);
        return redirect()->route('otp.show');
    }
}
