<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Total order untuk konsistensi header
        $totalOrders = $user->orders()->count();

        // 2. Statistik Referral (Menggantikan sistem Poin)
        // Jumlah teman yang berhasil daftar pakai kode user ini
        $totalJoined = Referral::where('referrer_id', $user->user_id)->count();
        
        // Jumlah referral yang sudah membuahkan hasil (diberi voucher)
        $vouchersEarned = Referral::where('referrer_id', $user->user_id)
                                  ->whereNotNull('rewarded_at')
                                  ->count();
                                  
        // Jumlah teman yang sudah daftar tapi belum transaksi (belum dapat reward)
        $pendingRewards = Referral::where('referrer_id', $user->user_id)
                                  ->whereNull('rewarded_at')
                                  ->count();

        // Tingkat Keberhasilan (Success Rate)
        $successRate = $totalJoined > 0 ? round(($vouchersEarned / $totalJoined) * 100) : 0;

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