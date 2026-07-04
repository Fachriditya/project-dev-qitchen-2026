<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk Sidebar
        $categories = Category::all();
        
        // 2. Tangkap parameter dari URL
        $selectedCategory = $request->query('category');
        $searchQuery = $request->query('search'); // Menangkap inputan pencarian

        // 3. Buat kerangka Query dasar (Menu yang tersedia)
        $query = Menu::where('is_available', 1);

        // 4. Logika Filter Kategori
        if ($selectedCategory) {
            $query->whereHas('category', function($q) use ($selectedCategory) {
                $q->where('category_name', $selectedCategory);
            });
        }

        // 5. Logika Filter Pencarian (Mencari berdasarkan nama menu)
        if ($searchQuery) {
            $query->where('menu_name', 'like', '%' . $searchQuery . '%');
        }

        // 6. Eksekusi Query
        $allMenus = $query->get();

        // Kirim datanya ke view
        return view('public.menu.index', compact('categories', 'allMenus', 'selectedCategory', 'searchQuery'));
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        
        // Ambil 4 menu secara acak (selain menu yang sedang dilihat)
        $relatedMenus = Menu::where('is_available', 1)
                            ->where('menu_id', '!=', $id)
                            ->inRandomOrder()
                            ->limit(4)
                            ->get();

        return view('public.menu.show', compact('menu', 'relatedMenus'));
    }
}