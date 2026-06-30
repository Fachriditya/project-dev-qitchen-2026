<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // TAMBAHKAN INI UNTUK MEMBUAT SLUG

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap apa pun yang diketik user di kolom pencarian
        $search = $request->search;

        // Ambil data dengan filter pencarian
        $categories = Category::with('menus')
            ->when($search, function ($query, $search) {
                // Cari data yang 'category_name'-nya mirip dengan kata kunci
                return $query->where('category_name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Ini PENTING: agar saat user pindah halaman 2, hasil search tidak hilang

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validasi menggunakan category_name
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name'
        ]);

        // Simpan data beserta slug-nya
        Category::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name)
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        // Validasi update dengan mengecualikan category_id saat ini
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $category->category_id . ',category_id'
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name)
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }
}