<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Menampilkan tabel transaksi (Index)
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $dateFilter = $request->date; // Menangkap filter waktu
        
        // Ambil data order beserta relasi user
        $transactions = Order::with(['user', 'payment'])
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                             ->orWhereHas('user', function($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                $today = Carbon::today();
                if ($dateFilter === 'today') {
                    return $query->whereDate('created_at', $today);
                } elseif ($dateFilter === 'week') {
                    return $query->whereBetween('created_at', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()]);
                } elseif ($dateFilter === 'month') {
                    return $query->whereMonth('created_at', $today->month)
                                 ->whereYear('created_at', $today->year);
                }
            })
            ->latest() // Urutkan dari transaksi paling baru
            ->paginate(10)
            ->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan Nota/Detail Transaksi (Show)
     */
    public function show(string $id)
    {
        // Tarik data order beserta SEMUA relasinya untuk ditampilkan di Nota
        $transaction = Order::with(['user', 'voucher', 'orderItems', 'payment', 'delivery'])->findOrFail($id);
        
        return view('admin.transactions.show', compact('transaction'));
    }
}