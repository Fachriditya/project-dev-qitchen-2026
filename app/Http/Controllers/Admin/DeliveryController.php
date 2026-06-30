<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        
        // Eager load relasi order dan courier untuk menghemat query database
        $deliveries = Delivery::with(['order', 'courier'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('order', function($q) use ($search) {
                                 $q->where('order_number', 'like', "%{$search}%");
                             })
                             ->orWhereHas('courier', function($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             })
                             ->orWhere('recipient_name', 'like', "%{$search}%")
                             ->orWhere('delivery_address', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                // Logika pengelompokan filter sesuai permintaanmu
                if ($status === 'active') {
                    return $query->whereIn('delivery_status', ['waiting', 'assigned', 'on_delivery']);
                } elseif ($status === 'delivered') {
                    return $query->where('delivery_status', 'delivered');
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.deliveries.index', compact('deliveries'));
    }
    
    public function show(string $id)
    {
        // Tarik data delivery beserta relasi order (dan itemnya) dan kurir
        $delivery = Delivery::with(['order.orderItems.menu', 'courier'])->findOrFail($id);
        
        return view('admin.deliveries.show', compact('delivery'));
    }
}