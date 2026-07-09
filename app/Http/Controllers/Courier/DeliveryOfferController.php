<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryOfferController extends Controller
{
    // 1. Tampilkan Daftar Order yang Butuh Kurir
    public function index()
    {
        // Cari pesanan Delivery yang sudah 'ready' tapi belum ada kurirnya
        $availableOrders = \App\Models\Order::with('user', 'orderItems')
            ->where('order_type', 'delivery')
            ->where('order_status', 'ready')
            ->whereDoesntHave('delivery', function($q) {
                $q->whereNotNull('courier_id'); 
            })
            ->get();

        // Cari pesanan yang SEDANG diantar oleh kurir yang sedang login ini
        $activeDelivery = \App\Models\Order::with('user')
            ->whereHas('delivery', function($q) {
                $q->where('courier_id', \Illuminate\Support\Facades\Auth::id())
                  ->where('delivery_status', 'on_delivery');
            })
            ->first();

        // Jika kurir masih punya orderan aktif, paksa dia ke halaman Peta
        if ($activeDelivery) {
            return redirect()->route('courier.map', $activeDelivery->order_id ?? $activeDelivery->id);
        }

        // 🌟 ZERO TO HERO: Hitung Statistik Hari Ini secara Real-Time
        $courierId = \Illuminate\Support\Facades\Auth::id();
        $today = \Carbon\Carbon::today();

        // Hitung total order yang diselesaikan HARI INI oleh kurir ini
        $completedToday = \App\Models\Delivery::where('courier_id', $courierId)
            ->where('delivery_status', 'delivered')
            ->whereDate('updated_at', $today)
            ->count();

        // Asumsi ongkir dasar per pengiriman (misal: kita set rata-rata Rp 15.000)
        // Nanti bisa diubah kalau kamu menambah kolom 'delivery_fee' di database
        $baseFee = 15000;
        $todayEarnings = $completedToday * $baseFee;

        return view('courier.offers', compact('availableOrders', 'completedToday', 'todayEarnings'));
    }

    // 2. Kurir Menerima Orderan
    public function accept($id)
    {
        $order = Order::findOrFail($id);
        
        // Buat atau update tabel Delivery
        $delivery = Delivery::updateOrCreate(
            ['order_id' => $order->order_id ?? $order->id],
            [
                'courier_id' => Auth::id(),
                'delivery_status' => 'on_delivery'
            ]
        );

        return redirect()->route('courier.map', $order->order_id ?? $order->id);
    }

    public function myDeliveries(Request $request)
    {
        $courierId = Auth::id();
        
        // Tangkap parameter tab dari URL (default: active)
        $tab = $request->get('tab', 'active');

        // Ambil data berdasarkan status
        $activeDeliveries = Delivery::with('order.user')
            ->where('courier_id', $courierId)
            ->where('delivery_status', 'on_delivery') // Sedang di jalan
            ->get();

        $acceptedDeliveries = Delivery::with('order.user')
            ->where('courier_id', $courierId)
            ->where('delivery_status', 'assigned') // Menuju restoran
            ->get();

        $historyDeliveries = Delivery::with('order.user')
            ->where('courier_id', $courierId)
            ->where('delivery_status', 'delivered') // Selesai
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('courier.my-deliveries', compact('activeDeliveries', 'acceptedDeliveries', 'historyDeliveries', 'tab'));
    }

    // 3. Tampilkan Peta (Leaflet OSM)
    public function map($id)
    {
        $order = Order::with(['user', 'delivery'])->findOrFail($id);
        
        // Kalau kurir nge-klik Detail, ubah status 'assigned' jadi 'on_delivery'
        if ($order->delivery && $order->delivery->delivery_status == 'assigned') {
            $order->delivery->update(['delivery_status' => 'on_delivery']);
        }
        
        return view('courier.map', compact('order'));
    }

    // 4. Selesaikan Pengiriman
    public function complete($id)
    {
        $order = Order::findOrFail($id);
        
        // Update Order jadi selesai
        $order->update(['order_status' => 'completed']);
        
        // Update Delivery jadi terkirim
        if ($order->delivery) {
            $order->delivery->update(['delivery_status' => 'delivered']);
        }

        return redirect()->route('courier.offers')->with('success', 'Pengiriman berhasil diselesaikan!');
    }
}