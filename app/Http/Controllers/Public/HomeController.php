<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class HomeController extends Controller
{
    public function index()
    {

        $featuredMenus = Menu::where('is_available', true)
                             ->inRandomOrder()
                             ->limit(3)
                             ->get();

        return view('public.home', compact('featuredMenus'));
    }
}