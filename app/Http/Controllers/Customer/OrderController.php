<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Ambil semua order milik user, tarik sekalian data item dan menu-nya (Eager Loading)
        $orders = Order::with('orderItems.menu')
                    ->where('user_id', $user->user_id) 
                    ->orderBy('created_at', 'desc')
                    ->get();

        $totalOrders = $orders->count();

        // 2. Logika "Klik": Cari order mana yang sedang dipilih di URL
        $selectedOrderId = $request->query('order_id');
        $selectedOrder = null;

        if ($orders->isNotEmpty()) {
            // Kalau ada order_id di URL, cari order tersebut. Kalau tidak ada, default ke order paling atas.
            $selectedOrder = $selectedOrderId 
                ? $orders->where('order_id', $selectedOrderId)->first() 
                : $orders->first();
        }

        // 3. Arahkan ke folder barumu: profile/history/index.blade.php
        return view('profile.history.index', compact('orders', 'selectedOrder', 'totalOrders'));
    }
}