<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserVoucher;

class VoucherController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data UserVoucher yang BELUM DIPAKAI
        $userVouchers = UserVoucher::with('voucher')
            ->where('user_id', $user->user_id)
            ->where('is_used', false)
            ->whereHas('voucher', function($query) {
                // FILTER RELASI: Vouchernya harus aktif dan belum expired
                $query->where('is_active', true)
                      ->where('expiration_date', '>=', now());
            })
            ->get();

        // Hitung total order untuk ditampilkan di header (biar konsisten dengan halaman sebelumnya)
        $totalOrders = $user->orders()->count();

        return view('profile.voucher.index', compact('userVouchers', 'totalOrders'));
    }
}