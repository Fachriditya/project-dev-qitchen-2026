<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. RENTANG TANGGAL UTAMA (Bulan Ini)
        $startDate = Carbon::now()->startOfMonth()->format('M j'); 
        $endDate = Carbon::now()->endOfMonth()->format('M j');   

        // 2. HITUNG AKUMULASI TOTAL UTAMA (ANGKA BESAR)
        $totalRevenue = Order::whereIn('order_status', ['paid', 'completed'])->sum('order_total');
        $totalOrders = Order::count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $avgPrepTime = 0; 

        // ==========================================
        // MENTORING ZONE: LOGIKA PERTUMBUHAN (WoW)
        // ==========================================
        
        // Definisikan Waktu Minggu Ini (7 hari terakhir)
        $thisWeekStart = Carbon::now()->subDays(6)->startOfDay();
        $thisWeekEnd = Carbon::now()->endOfDay();

        // Definisikan Waktu Minggu Lalu (7 hari sebelum minggu ini)
        $lastWeekStart = Carbon::now()->subDays(13)->startOfDay();
        $lastWeekEnd = Carbon::now()->subDays(7)->endOfDay();

        // A. Ambil Data Transaksi Minggu Ini
        $revThisWeek = Order::whereIn('order_status', ['paid', 'completed'])->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->sum('order_total');
        $ordThisWeek = Order::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->count();
        $avgThisWeek = $ordThisWeek > 0 ? $revThisWeek / $ordThisWeek : 0;

        // B. Ambil Data Transaksi Minggu Lalu
        $revLastWeek = Order::whereIn('order_status', ['paid', 'completed'])->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->sum('order_total');
        $ordLastWeek = Order::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();
        $avgLastWeek = $ordLastWeek > 0 ? $revLastWeek / $ordLastWeek : 0;

        // C. Hitung Persentase Pertumbuhan (Dengan Proteksi Division by Zero)
        $revenueGrowth = $revLastWeek > 0 ? (($revThisWeek - $revLastWeek) / $revLastWeek) * 100 : ($revThisWeek > 0 ? 100 : 0);
        $ordersGrowth = $ordLastWeek > 0 ? (($ordThisWeek - $ordLastWeek) / $ordLastWeek) * 100 : ($ordThisWeek > 0 ? 100 : 0);
        $avgOrderGrowth = $avgLastWeek > 0 ? (($avgThisWeek - $avgLastWeek) / $avgLastWeek) * 100 : ($avgThisWeek > 0 ? 100 : 0);

        // Bungkus metrik ke array agar gampang dibaca di Blade View
        $metrics = [
            'revenue' => ['growth' => round($revenueGrowth, 1), 'is_up' => $revenueGrowth >= 0],
            'orders'  => ['growth' => round($ordersGrowth, 1), 'is_up' => $ordersGrowth >= 0],
            'avg'     => ['growth' => round($avgOrderGrowth, 1), 'is_up' => $avgOrderGrowth >= 0],
        ];

        // 3. SALES OVER TIME (Grafik Batang)
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $label = Carbon::now()->subDays($i)->format('D'); 
            $salesCount = Order::whereDate('created_at', $date)->count();
            $last7Days->push(['label' => $label, 'sales' => $salesCount]);
        }
        $salesData = [
            'labels' => $last7Days->pluck('label')->toArray(),
            'data' => $last7Days->pluck('sales')->toArray(),
        ];

        // 4. REVENUE BY CATEGORY (Progress Bar)
        $categoryData = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.menu_id')
            ->join('categories', 'menus.category_id', '=', 'categories.category_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->whereIn('orders.order_status', ['paid', 'completed'])
            ->select('categories.category_name', DB::raw('SUM(order_items.item_subtotal) as total_revenue'))
            ->groupBy('categories.category_name')
            ->get();

        $totalCatRevenue = $categoryData->sum('total_revenue');
        $categoryReport = $categoryData->map(function($item) use ($totalCatRevenue) {
            return [
                'name' => $item->category_name,
                'percentage' => $totalCatRevenue > 0 ? round(($item->total_revenue / $totalCatRevenue) * 100) : 0,
                'color' => 'bg-orange-500'
            ];
        })->sortByDesc('percentage')->values()->toArray();

        // 5. TOP SELLING ITEMS (Tabel Mini)
        $topSellingItems = OrderItem::join('menus', 'order_items.menu_id', '=', 'menus.menu_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.order_id')
            ->whereIn('orders.order_status', ['paid', 'completed'])
            ->select('menus.menu_name as name', DB::raw('SUM(order_items.quantity) as sold'), DB::raw('SUM(order_items.item_subtotal) as revenue'))
            ->groupBy('menus.menu_id', 'menus.menu_name')
            ->orderByDesc('sold')
            ->limit(5)
            ->get()
            ->toArray();

        // 6. PAYMENT METHODS (Grafik Donat)
        $payments = Payment::select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        $paymentMethods = [
            'labels' => $payments->pluck('payment_method')->map(fn($m) => ucfirst($m))->toArray(),
            'data' => $payments->pluck('count')->toArray(),
        ];

        return view('admin.reports.index', compact(
            'startDate', 'endDate', 'totalRevenue', 'totalOrders', 'avgOrderValue', 'avgPrepTime',
            'salesData', 'categoryReport', 'topSellingItems', 'paymentMethods', 'metrics'
        ));
    }
}