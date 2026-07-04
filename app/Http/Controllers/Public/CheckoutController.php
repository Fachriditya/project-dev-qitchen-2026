<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Cegah user masuk checkout kalau keranjang kosong
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.10; // Pajak 10%
        $serviceFee = 5000;      // Biaya layanan sesuai desainmu
        $total = $subtotal + $tax + $serviceFee;

        return view('public.checkout.index', compact('cart', 'subtotal', 'tax', 'serviceFee', 'total'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart');
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        // 1. Kalkulasi Server (Keamanan Data)
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $tax = $subtotal * 0.10;
        $serviceFee = 5000;
        $total = $subtotal + $tax + $serviceFee;

        // 2. Tangkap Input Form
        $orderType = $request->input('order_type', 'dine_in'); // Default dine_in
        
        // Tangkap nama berdasarkan form yang sedang aktif
        $customerName = 'Guest';
        if ($orderType == 'dine_in') $customerName = $request->input('dinein_name');
        elseif ($orderType == 'takeaway') $customerName = $request->input('pickup_name');
        elseif ($orderType == 'delivery') $customerName = $request->input('delivery_name');
        
        $customerName = $customerName ?: 'Guest'; // Fallback jika user tidak mengisi nama
        $orderNotes = $request->input('order_notes');

        // Buat nomor order unik (Misal: QTC-64A3B1)
        $orderNumber = 'QTC-' . strtoupper(substr(uniqid(), -6));

        \Illuminate\Support\Facades\DB::beginTransaction();
        
        try {
            // 3. Simpan ke tabel ORDERS
            $order = \App\Models\Order::create([
                'user_id' => null,
                'order_number' => $orderNumber,
                'order_type' => $orderType,
                'order_status' => 'pending', 
                'order_subtotal' => $subtotal,
                'order_discount' => 0,
                'order_total' => $total, // Pakai $total
                'order_notes' => "Name: $customerName | Notes: $orderNotes", 
            ]);

            // 4. Simpan ke tabel ORDER_ITEMS
            foreach ($cart as $id => $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->order_id,
                    'menu_id' => $id,
                    'quantity' => $item['quantity'],
                    'item_price' => $item['price'],
                    'item_subtotal' => $item['price'] * $item['quantity'],
                    'item_notes' => $item['item_notes'] ?? null,
                ]);
            }

            // 5. Simpan ke tabel PAYMENTS
            $payment = \App\Models\Payment::create([
                'order_id' => $order->order_id,
                'payment_amount' => $total, // Pakai $total
                'payment_method' => 'qris', 
                'payment_status' => 'pending',
            ]);

            // 6. Minta Token ke Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_id . '-' . time(), 
                    'gross_amount' => $total, // Pakai $total
                ],
                'customer_details' => [
                    'first_name' => $customerName,
                ],
                'enabled_payments' => [
                    'gopay', 'shopeepay', 'other_qris', 'dana', 'ovo', 'linkaja',
                    'bca_va', 'bni_va', 'bri_va', 'permata_va', 'cimb_va', 'echannel', 'danamon_online', 'other_va'
                ], 
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            \Illuminate\Support\Facades\DB::commit();

            // 7. Bersihkan keranjang
            session()->forget('cart');

            // 8. Bawa ke Halaman Qitchen Pay
            return view('public.checkout.payment', compact('order', 'payment', 'snapToken'));

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'System error: ' . $e->getMessage());
        }
    }
}