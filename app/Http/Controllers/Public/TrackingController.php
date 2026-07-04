<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show($order_number)
    {
        // Ambil data order beserta item menu dan data delivery-nya
        $order = Order::with(['orderItems.menu', 'delivery'])
                      ->where('order_number', $order_number)
                      ->firstOrFail();

        return view('public.tracking.show', compact('order'));
    }
}