<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
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

        // 4. Cari Order
        $order = Order::where('order_number', $request->order_id)->first();

        if (!$order) {
            return response()->json(['message' => 'Order Not Found'], 404);
        }

        // 5. Update Status
        if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
            $order->update(['order_status' => 'paid']);
            Log::info("Webhook Success: Order " . $request->order_id . " verified and set to PAID.");
            return response()->json(['message' => 'Webhook Processed']);
        }

        return response()->json(['message' => 'Transaction status ignored']);
    }
}