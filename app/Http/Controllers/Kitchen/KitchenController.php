<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    // 1. TAMPILKAN KITCHEN BOARD
    public function index()
    {
        // Ambil semua pesanan yang statusnya paid, cooking, atau ready[cite: 5]
        // Eager load orderItems dan menu agar query database tidak jebol (N+1 problem)[cite: 5, 6]
        $orders = Order::with('orderItems.menu')
            ->whereIn('order_status', ['paid', 'cooking', 'ready'])
            ->orderBy('updated_at', 'asc') // Urutkan dari yang paling lama menunggu
            ->get();

        // Pecah menjadi 3 kelompok berdasarkan status
        $waiting = $orders->where('order_status', 'paid');
        $cooking = $orders->where('order_status', 'cooking');
        $ready = $orders->where('order_status', 'ready');

        return view('kitchen.board', compact('waiting', 'cooking', 'ready'));
    }

    // 2. UPDATE STATUS PESANAN
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Update status sesuai tombol yang ditekan koki[cite: 5]
        $order->update([
            'order_status' => $request->status
        ]);

        return redirect()->back();
    }
}