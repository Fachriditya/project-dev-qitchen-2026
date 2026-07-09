<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Payment;
use Illuminate\Http\Request;

class ManualOrderController extends Controller
{
    // 1. TAMPILKAN HALAMAN POS
    public function index(Request $request)
    {
        // 🌟 1. Ambil data semua kategori untuk mengisi Dropdown
        // (Pastikan kamu punya Model Category. Kalau namamu beda, sesuaikan ya wak)
        $categories = \App\Models\Category::all(); 

        // 🌟 2. Fitur Search & Filter Menu
        $query = Menu::query();

        // Kalau kasir ngetik di kolom search
        if ($request->filled('search')) {
            $query->where('menu_name', 'like', '%' . $request->search . '%');
        }

        // Kalau kasir milih kategori dari dropdown
        if ($request->filled('category')) {
            // Sesuaikan 'category_id' dengan nama kolom di tabel menus milikmu
            $query->where('category_id', $request->category); 
        }

        $menus = $query->get();

        // 3. Ambil keranjang khusus KASIR
        $cart = session()->get('cashier_cart', []);
        
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;

        // Jangan lupa passing $categories ke tampilan View
        return view('cashier.manual_order.index', compact('menus', 'cart', 'subtotal', 'tax', 'total', 'categories'));
    }

    // 2. TAMBAH KE KERANJANG
    public function add($id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cashier_cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'menu_name' => $menu->menu_name,
                'quantity' => 1,
                'price' => $menu->menu_price,
            ];
        }

        session()->put('cashier_cart', $cart);
        return redirect()->back(); // Reload cepat tanpa flash message biar kasir sat-set
    }

    // 3. UBAH KUANTITAS (+ / -)
    public function update(Request $request, $id)
    {
        $cart = session()->get('cashier_cart');
        $action = $request->action; // 'increase' atau 'decrease'

        if (isset($cart[$id])) {
            if ($action === 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$id]['quantity']--;
                // Kalau jumlahnya 0, hapus dari keranjang
                if ($cart[$id]['quantity'] <= 0) {
                    unset($cart[$id]);
                }
            }
            session()->put('cashier_cart', $cart);
        }

        return redirect()->back();
    }

    // 4. PROSES KERANJANG KASIR
    public function process(Request $request)
    {
        $cart = session()->get('cashier_cart');
        if (empty($cart)) return redirect()->back()->with('error', 'Keranjang masih kosong!');

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.10;
        $total = $subtotal + $tax;

        $orderNumber = 'QTC-POS-' . strtoupper(substr(uniqid(), -6));

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Catat pesanan atas nama Walk-in Guest
            $order = \App\Models\Order::create([
                'user_id' => null, 
                'order_number' => $orderNumber,
                'order_type' => 'dine_in', // Default dine-in untuk POS
                'order_status' => 'pending',
                'order_subtotal' => $subtotal,
                'order_discount' => 0,
                'order_total' => $total,
                'order_notes' => 'Name: Walk-in Guest | POS Order',
            ]);

            foreach ($cart as $id => $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->order_id ?? $order->id,
                    'menu_id' => $id,
                    'quantity' => $item['quantity'],
                    'item_price' => $item['price'],
                    'item_subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            session()->forget('cashier_cart'); // Bersihkan laci keranjang kasir
            
            // Lempar ke halaman kalkulator pembayaran
            return redirect()->route('cashier.manual-order.payment', $order->order_id ?? $order->id);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    // 5. HALAMAN PEMBAYARAN POS
    public function payment($id)
    {
        $order = \App\Models\Order::findOrFail($id);

        // 🌟 ZERO TO HERO: Generate Snap Token untuk QRIS Kasir
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number . '-' . time(), // Tambah time() biar unik jika gagal bayar
                'gross_amount' => $order->order_total,
            ],
            'customer_details' => [
                'first_name' => 'Walk-in Guest',
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'other_qris', 'dana', 'ovo', 'linkaja'
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Lempar tokennya ke view
        return view('cashier.manual_order.payment', compact('order', 'snapToken'));
    }

    // 6. PROSES PEMBAYARAN CASH SELESAI
    public function completePayment(Request $request, $id)
    {
        // Cari pesanannya
        $order = \App\Models\Order::findOrFail($id);

        // Update status pesanannya jadi LUNAS ('paid')
        $order->update([
            'order_status' => 'paid', 
        ]);

        \App\Models\Payment::create([
             'order_id' => $order->order_id ?? $order->id,
            'midtrans_transaction_id' => 'CASH-' . strtoupper(uniqid()), // Bikin ID unik untuk transaksi tunai
            'payment_method' => 'cash',
            'payment_amount' => $order->order_total,
            'payment_status' => 'success', 
            'paid_at' => now(), // Otomatis mencatat waktu saat ini
        ]);

        // Lempar kembali mbak kasir ke halaman Monitoring dengan pesan sukses!
        return redirect()->route('cashier.transactions.index')->with('success', 'Pembayaran Cash berhasil! Kembalian: Rp ' . number_format($request->cash_received - $order->order_total, 0, ',', '.'));
    }
}