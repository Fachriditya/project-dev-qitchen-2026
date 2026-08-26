<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Voucher;

class CheckoutController extends Controller
{
    // ==========================================
    // 1. TAMPILAN HALAMAN CHECKOUT
    // ==========================================
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjangmu masih kosong, King!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.10; 
        $serviceFee = 5000;      
        
        // 🌟 MESIN PENGHITUNG DISKON
        $discount = 0;
        $activeVoucher = session()->get('applied_voucher');

        if ($activeVoucher && $subtotal >= $activeVoucher['min_order']) {
            switch ($activeVoucher['type']) {
                case 'fixed':
                    $discount = $activeVoucher['value'];
                    break;
                    
                case 'percentage':
                    $discount = ($activeVoucher['value'] / 100) * $subtotal;
                    if ($activeVoucher['max_discount'] && $discount > $activeVoucher['max_discount']) {
                        $discount = $activeVoucher['max_discount'];
                    }
                    break;
                    
                case 'bogo':
                    $totalQty = 0;
                    $itemPrices = [];
                    foreach ($cart as $item) {
                        $totalQty += $item['quantity'];
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            $itemPrices[] = $item['price'];
                        }
                    }
                    if ($totalQty >= 2) {
                        $discount = min($itemPrices); // Gratis 1 item termurah
                    }
                    break;
                    
                case 'delivery':
                    $discount = $serviceFee; // Gratis ongkir/layanan
                    break;
            }
        } elseif ($activeVoucher) {
            session()->forget('applied_voucher');
            $activeVoucher = null;
        }

        $total = ($subtotal + $tax + $serviceFee) - $discount;
        if ($total < 0) {
            $total = 0; 
        }

        return view('public.checkout.index', compact('cart', 'subtotal', 'tax', 'serviceFee', 'discount', 'total', 'activeVoucher'));
    }

    // ==========================================
    // 2. LOGIKA PASANG VOUCHER (APPLY)
    // ==========================================
    public function applyVoucher(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Kamu harus login dulu untuk pakai voucher!');
        }

        $request->validate([
            'voucher_code' => 'required|string'
        ]);

        $user = Auth::user();
        $myId = $user->user_id ?? $user->id; // Sesuaikan dengan primary key user-mu

        // 1. Cari master vouchernya dulu
        $voucher = Voucher::where('voucher_code', strtoupper($request->voucher_code))
                          ->where('is_active', true)
                          ->first();

        if (!$voucher) {
            return redirect()->back()->with('error', 'Kode voucher tidak valid atau tidak ditemukan.');
        }

        // 🌟 2. ZERO TO HERO: Cek ke tabel UserVoucher (Dompet User)
        // (Pastikan kamu sudah import: use App\Models\UserVoucher; di bagian atas file)
        $userVoucher = \App\Models\UserVoucher::where('user_id', $myId)
                            ->where('voucher_id', $voucher->voucher_id ?? $voucher->id)
                            ->first();

        // Gembok Keamanan!
        if (!$userVoucher) {
            return redirect()->back()->with('error', 'Kamu belum mengklaim atau tidak memiliki voucher ini.');
        }
        if ($userVoucher->is_used) {
            return redirect()->back()->with('error', 'Waduh, voucher ini sudah pernah kamu pakai!');
        }
        if ($voucher->expiration_date && $voucher->expiration_date < now()) {
            return redirect()->back()->with('error', 'Waduh, voucher ini sudah kadaluarsa.');
        }

        // 3. Simpan data ke Session, TERMASUK ID USER VOUCHER-NYA
        session()->put('applied_voucher', [
            'id' => $voucher->voucher_id ?? $voucher->id,
            'user_voucher_id' => $userVoucher->id ?? $userVoucher->user_voucher_id, // 🌟 Ini kunci untuk menghanguskannya nanti
            'code' => $voucher->voucher_code,
            'type' => $voucher->voucher_type,
            'value' => $voucher->voucher_value,
            'max_discount' => $voucher->max_discount,
            'min_order' => $voucher->min_order
        ]);

        return redirect()->back()->with('success', 'Voucher berhasil dipasang! Cek diskonmu.');
    }

    // ==========================================
    // 3. LOGIKA LEPAS VOUCHER (REMOVE)
    // ==========================================
    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        return redirect()->back()->with('success', 'Voucher dilepas.');
    }

    // ==========================================
    // 4. PROSES CHECKOUT KE DATABASE & MIDTRANS
    // ==========================================
    public function process(Request $request)
    {
        $cart = session()->get('cart');
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $tax = $subtotal * 0.10;
        $serviceFee = 5000;
        
        // 🌟 MESIN PENGHITUNG DISKON (Sama persis dengan index)
        $discount = 0;
        $activeVoucher = session()->get('applied_voucher');

        if ($activeVoucher && $subtotal >= $activeVoucher['min_order']) {
            switch ($activeVoucher['type']) {
                case 'fixed':
                    $discount = $activeVoucher['value'];
                    break;
                    
                case 'percentage':
                    $discount = ($activeVoucher['value'] / 100) * $subtotal;
                    if ($activeVoucher['max_discount'] && $discount > $activeVoucher['max_discount']) {
                        $discount = $activeVoucher['max_discount'];
                    }
                    break;
                    
                case 'bogo':
                    $totalQty = 0;
                    $itemPrices = [];
                    foreach ($cart as $item) {
                        $totalQty += $item['quantity'];
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            $itemPrices[] = $item['price'];
                        }
                    }
                    if ($totalQty >= 2) {
                        $discount = min($itemPrices); 
                    }
                    break;
                    
                case 'delivery':
                    $discount = $serviceFee; 
                    break;
            }
        }

        $total = ($subtotal + $tax + $serviceFee) - $discount;
        if ($total < 0) $total = 0;

        $orderType = $request->input('order_type', 'dine_in'); 
        
        $customerName = 'Guest';
        if ($orderType == 'dine_in') $customerName = $request->input('dinein_name');
        elseif ($orderType == 'takeaway') $customerName = $request->input('pickup_name');
        elseif ($orderType == 'delivery') $customerName = $request->input('delivery_name');
        
        $customerName = $customerName ?: (Auth::check() ? Auth::user()->name : 'Guest');
        $orderNotes = $request->input('order_notes');

        $orderNumber = 'QTC-' . strtoupper(substr(uniqid(), -6));

        DB::beginTransaction();
        
        try {
            $order = \App\Models\Order::create([
                'user_id' => Auth::id(), 
                'order_number' => $orderNumber,
                'order_type' => $orderType,
                'order_status' => 'pending', 
                'order_subtotal' => $subtotal,
                'order_discount' => $discount, 
                'order_total' => $total, 
                'order_notes' => "Name: $customerName | Notes: $orderNotes", 
            ]);

            foreach ($cart as $id => $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->order_id ?? $order->id, 
                    'menu_id' => $id,
                    'quantity' => $item['quantity'],
                    'item_price' => $item['price'],
                    'item_subtotal' => $item['price'] * $item['quantity'],
                    'item_notes' => $item['item_notes'] ?? null,
                ]);
            }

            $payment = \App\Models\Payment::create([
                'order_id' => $order->order_id ?? $order->id,
                'payment_amount' => $total,
                'payment_method' => 'qris', 
                'payment_status' => 'pending',
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number . '-' . time(), 
                    'gross_amount' => $total, 
                ],
                'customer_details' => [
                    'first_name' => $customerName,
                    'email' => Auth::check() ? Auth::user()->email : null, 
                ],
                'enabled_payments' => [
                    'gopay', 'shopeepay', 'other_qris', 'dana', 'ovo', 'linkaja',
                    'bca_va', 'bni_va', 'bri_va', 'permata_va', 'cimb_va'
                ], 
            ];

            if (app()->environment('testing')) {
                $snapToken = 'TEST-SNAPSHOT-TOKEN-' . uniqid();
            } else {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
            }

            if ($activeVoucher) {
                // 1. Tambah statistik penggunaan di master voucher
                $usedVoucher = Voucher::find($activeVoucher['id']);
                if ($usedVoucher) {
                    $usedVoucher->increment('issued_count');
                }

                // 2. HANGUSKAN VOUCHER DI DOMPET USER (Ubah is_used jadi true)
                if (isset($activeVoucher['user_voucher_id'])) {
                    // Gunakan nama kolom primary key dari UserVoucher milikmu (misal 'id' atau 'user_voucher_id')
                    $userVoucherKey = 'user_voucher_id'; // Ganti jadi 'id' kalau primary key-mu id
                    
                    \App\Models\UserVoucher::where($userVoucherKey, $activeVoucher['user_voucher_id'])
                        ->update(['is_used' => true]);
                }
            }

            DB::commit();

            session()->forget(['cart', 'applied_voucher']);

            return view('public.checkout.payment', compact('order', 'payment', 'snapToken'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'System error: ' . $e->getMessage());
        }
    }
}