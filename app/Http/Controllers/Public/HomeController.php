<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB; // Jangan lupa tambahkan ini untuk fungsi DB::raw

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil 4 menu Best Seller berdasarkan total quantity yang terjual[cite: 5]
        $featuredMenus = Menu::leftJoin('order_items', 'menus.menu_id', '=', 'order_items.menu_id')
                             ->select('menus.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'))
                             ->where('menus.is_available', true)
                             ->groupBy('menus.menu_id')
                             ->orderByDesc('total_sold')
                             ->limit(4) // Menyesuaikan dengan grid 4 kolom di desainmu
                             ->get();

        return view('public.home', compact('featuredMenus'));
    }
}