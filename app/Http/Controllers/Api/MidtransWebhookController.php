<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;         // 🌟 Panggil model User
use App\Models\Voucher;      // 🌟 Panggil model Voucher
use App\Models\UserVoucher;  // 🌟 Panggil model UserVoucher
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        // 1. Ambil Server Key
        $serverKey = config('midtrans.server_key');
        
        // 2. Hitung Hash (Signature)
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // 3. VALIDASI KETAT (Tidak ada bypass!)
        if ($hashed != $request->signature_key) {
            Log::warning("Percobaan akses ilegal ke Webhook dengan Signature tidak valid: " . $request->order_id);
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 4. MENCARI ORDER (🌟 ZERO TO HERO BUG FIX)
        // Midtrans mengirim order_id bentuknya "QTC-123456-1720500000"
        // Kita potong timestamp di belakangnya untuk mendapatkan nomor nota asli
        $realOrderNumber = substr($request->order_id, 0, strrpos($request->order_id, '-'));

        $order = Order::where('order_number', $realOrderNumber)->first();

        if (!$order) {
            return response()->json(['message' => 'Order Not Found'], 404);
        }

        // 5. UPDATE STATUS
        if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
            
            // Cegah double-process jika Midtrans mengirim webhook berkali-kali
            if ($order->order_status == 'paid') {
                return response()->json(['message' => 'Order already processed']);
            }

            // Update status tabel order
            $order->update(['order_status' => 'paid']);
            
            // Update status tabel payment (enum: pending, success, failed, expired)
            Payment::where('order_id', $order->order_id ?? $order->id)
                   ->update(['payment_status' => 'success', 'paid_at' => now()]);

            Log::info("Webhook Success: Order " . $realOrderNumber . " verified and set to PAID.");

            // ====================================================
            // 🌟 MESIN REWARD REFERRAL OTOMATIS
            // ====================================================
            
            // Pastikan pembelinya login (bukan Guest)
            if ($order->user_id != null) {
                
                // Cari data pembeli (The "Cindy")
                $customer = User::find($order->user_id);

                // Cek 1: Apakah pembeli punya "Pengajak/Mentor" (The "Shima")?
                if ($customer && $customer->referred_by != null) {
                    
                    // Cek 2: Hitung jumlah pesanan lunas si pembeli
                    $paidOrdersCount = Order::where('user_id', $customer->user_id ?? $customer->id)
                                            ->where('order_status', 'paid')
                                            ->count();

                    // Berikan hadiah HANYA saat pesanan lunas yang PERTAMA
                    if ($paidOrdersCount == 1) {
                        
                        // Cek 3: Cari Master Voucher khusus Referral
                        $masterVoucher = Voucher::where('voucher_code', 'REF-REWARD-2026')
                                                ->where('is_active', true)
                                                ->first();

                        if ($masterVoucher) {
                            // CETAK VOUCHER & MASUKKAN KE DOMPET PENGAJAK! 🔥
                            UserVoucher::create([
                                'user_id' => $customer->referred_by, // ID si Pengajak (Shima)
                                'voucher_id' => $masterVoucher->voucher_id ?? $masterVoucher->id,
                                'is_used' => false
                            ]);
                            
                            // Simpan catatan referral reward
                            \App\Models\Referral::create([
                                'referrer_id' => $customer->referred_by,
                                'referred_user_id' => $order->user_id,
                                'voucher_id' => $masterVoucher->voucher_id ?? $masterVoucher->id,
                                'rewarded_at' => now(),
                            ]);
                            
                            Log::info("Referral Reward: Voucher REF-REWARD-2026 diberikan ke User ID " . $customer->referred_by);
                        }
                    }
                }
            }
            // ====================================================

            return response()->json(['message' => 'Webhook Processed & Rewards Distributed']);
        }

        return response()->json(['message' => 'Transaction status ignored']);
    }
}