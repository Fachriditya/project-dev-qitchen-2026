<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\{Order, OrderItem, User, Menu, Category, Voucher, Article};

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_transactions' => Order::count(), 
            'total_users'        => User::where('role', 'customer')->count(),
            'total_menus'        => Menu::count(),
            'total_categories'   => Category::count(),
            'total_vouchers'     => Voucher::count(),
            'total_deliveries'   => Order::where('order_type', 'delivery')->count(), 
            'total_revenue'      => Order::sum('order_total'), 
            'total_articles'     => Article::count(),
        ];

        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(4)->get();

        $topSellers = OrderItem::select('menu_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->with('menu')
            ->take(4)
            ->get();

        return view('admin.dashboard.index', compact('data', 'recentOrders', 'topSellers'));
    }
}