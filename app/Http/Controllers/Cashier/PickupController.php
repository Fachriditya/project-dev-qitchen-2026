<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PickupController extends Controller
{
    // 1. Tampilkan Halaman Pickup
    public function index(Request $request)
    {
        // Ambil pesanan yang statusnya 'ready' (sudah dimasak koki)
        $query = Order::with('user')->where('order_status', 'ready');

        // Fitur Search Bar berdasarkan Nomor Pesanan
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Urutkan dari yang paling lama menungggu (updated_at ASC)
        $orders = $query->orderBy('updated_at', 'asc')->get();
        
        $readyCount = $orders->count();

        return view('cashier.pickup.index', compact('orders', 'readyCount'));
    }

    // 2. Eksekusi Tombol Confirm Pickup
    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        
        // Ubah status menjadi selesai / diserahkan
        $order->update([
            'order_status' => 'completed'
        ]);

        return redirect()->back()->with('success', 'Pesanan ' . $order->order_number . ' telah diserahkan ke pelanggan!');
    }
}