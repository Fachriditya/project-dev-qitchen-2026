<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category; // Wajib dipanggil untuk dropdown
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Wajib untuk hapus foto lama

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status; // Menangkap filter 'available' atau 'unavailable'
        
        $menus = Menu::with('category')
            // Filter Pencarian
            ->when($search, function ($query, $search) {
                return $query->where('menu_name', 'like', '%' . $search . '%');
            })
            // Filter Status (Baru ditambahkan)
            ->when($status, function ($query, $status) {
                if ($status === 'available') {
                    return $query->where('is_available', 1);
                } elseif ($status === 'unavailable') {
                    return $query->where('is_available', 0);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // withQueryString memastikan filter tidak hilang saat pindah halaman 2, 3, dst.

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        // Ambil semua kategori untuk ditampilkan di dropdown form
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'menu_name' => 'required|string|max:255',
            'menu_price' => 'required|numeric|min:0',
            'menu_description' => 'nullable|string',
            'is_available' => 'required|boolean',
            'menu_image' => 'required|image|mimes:jpeg,png,jpg|max:2048' // Wajib upload foto
        ]);

        // Proses simpan foto ke folder storage/app/public/menus
        $imagePath = $request->file('menu_image')->store('menus', 'public');

        Menu::create([
            'category_id' => $request->category_id,
            'menu_name' => $request->menu_name,
            'menu_slug' => Str::slug($request->menu_name),
            'menu_description' => $request->menu_description,
            'menu_price' => $request->menu_price,
            'is_available' => $request->is_available,
            'menu_image' => $imagePath,
        ]);

        return redirect()->route('admin.menus.index')->with('success', 'Menu added successfully!');
    }

    public function edit(string $id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'menu_name' => 'required|string|max:255',
            'menu_price' => 'required|numeric|min:0',
            'menu_description' => 'nullable|string',
            'is_available' => 'required|boolean',
            'menu_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Boleh kosong jika tidak ganti foto
        ]);

        $data = [
            'category_id' => $request->category_id,
            'menu_name' => $request->menu_name,
            'menu_slug' => Str::slug($request->menu_name),
            'menu_description' => $request->menu_description,
            'menu_price' => $request->menu_price,
            'is_available' => $request->is_available,
        ];

        // Jika user mengunggah foto baru
        if ($request->hasFile('menu_image')) {
            // Hapus foto lama
            if ($menu->menu_image && Storage::disk('public')->exists($menu->menu_image)) {
                Storage::disk('public')->delete($menu->menu_image);
            }
            // Simpan foto baru
            $data['menu_image'] = $request->file('menu_image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu updated successfully!');
    }

    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        // Karena kamu pakai SoftDeletes di Model, kita tidak menghapus fisik fotonya dulu
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully!');
    }
}