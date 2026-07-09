<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  // 🌟 Ganti Referral menjadi User
use App\Models\Order; // 🌟 Tambahkan Order

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // (Sesuaikan kalau Primary Key di tabel users-mu adalah 'id' atau 'user_id')
        $myId = $user->user_id ?? $user->id; 

        // 1. Total order untuk konsistensi header
        // (Pastikan kamu sudah membuat relasi orders() di dalam Model User)
        $totalOrders = $user->orders()->count();

        // 2. Statistik Referral (Menggunakan kolom referred_by di tabel users)
        
        // A. Jumlah teman yang berhasil daftar pakai kode user ini
        $totalJoined = User::where('referred_by', $myId)->count();
        
        // B. Ambil daftar seluruh ID teman yang diajak
        $referredUserIds = User::where('referred_by', $myId)->pluck('user_id'); // Ganti ke 'id' jika pakai id
        
        // C. Jumlah referral yang sudah membuahkan hasil (temannya sudah belanja & pesanan completed)
        $vouchersEarned = Order::whereIn('user_id', $referredUserIds)
                               ->where('order_status', 'completed')
                               ->distinct('user_id')
                               ->count('user_id');
                                  
        // D. Jumlah teman yang sudah daftar tapi belum transaksi
        $pendingRewards = $totalJoined - $vouchersEarned;
        if ($pendingRewards < 0) {
            $pendingRewards = 0; // Failsafe agar tidak minus
        }

        // 3. Tingkat Keberhasilan (Success Rate)
        $successRate = $totalJoined > 0 ? round(($vouchersEarned / $totalJoined) * 100) : 0;

        // 🌟 Pastikan nama folder view-mu benar: 'profile.referral.index' atau 'profile.referral'
        return view('profile.referral.index', compact(
            'user', 
            'totalOrders', 
            'totalJoined', 
            'vouchersEarned', 
            'pendingRewards', 
            'successRate'
        ));
    }
}