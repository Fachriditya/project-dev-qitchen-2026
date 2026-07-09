<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. STATISTIK REAL-TIME HARI INI
        $today = Carbon::today();

        // Open Orders (Semua yang belum selesai/batal hari ini)
        $openOrders = Order::whereDate('created_at', $today)
            ->whereNotIn('order_status', ['completed', 'cancelled'])
            ->count();

        // Awaiting Payment (Status pending)
        $awaitingPayment = Order::whereDate('created_at', $today)
            ->where('order_status', 'pending')
            ->count();

        // Ready to Serve (Makanan udah matang dari dapur)
        $readyToServe = Order::whereDate('created_at', $today)
            ->where('order_status', 'ready')
            ->count();

        // Today's Revenue (Total uang masuk dari pesanan yg udah dibayar/selesai hari ini)
        $todaysRevenue = Order::whereDate('created_at', $today)
            ->whereIn('order_status', ['paid', 'completed'])
            ->sum('order_total');

        // 2. DATA TABEL & PENCARIAN
        $query = Order::with(['user', 'payment'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari berdasarkan nomor nota
                $q->where('order_number', 'like', "%{$search}%")
                  // Atau cari berdasarkan nama customer (Guest note atau User)
                  ->orWhere('order_notes', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Waktu (All, Today, Week, Month) dari kodemu
        if ($request->filled('date')) {
            if ($request->date == 'today') {
                $query->whereDate('created_at', $today);
            } elseif ($request->date == 'week') {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($request->date == 'month') {
                $query->whereMonth('created_at', Carbon::now()->month);
            }
        }

        // Pagination 10 data per halaman
        $transactions = $query->paginate(10);

        return view('cashier.transactions.index', compact(
            'transactions', 'openOrders', 'awaitingPayment', 'readyToServe', 'todaysRevenue'
        ));
    }

    public function show($id)
    {
        // Ambil data transaksi beserta relasinya
        $transaction = \App\Models\Order::with(['user', 'orderItems.menu', 'payment'])->findOrFail($id);
        
        return view('cashier.transactions.show', compact('transaction'));
    }
}