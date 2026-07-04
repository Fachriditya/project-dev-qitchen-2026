<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $cart = session()->get('cart', []);
        
        // 1. Tangkap input 'quantity' (dari halaman detail)
        // Gunakan null sebagai penanda agar kita tahu apakah request ini dari halaman katalog (tanpa form qty) atau halaman detail
        $inputQty = $request->input('quantity');

        // 2. LOGIKA ZERO TO HERO: Jika dari halaman detail dan diset 0, HAPUS BARANG!
        if ($inputQty !== null && (int)$inputQty <= 0) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
                session()->put('cart', $cart);
            }
            return redirect()->route('menu.index')->with('success', 'Item removed from cart!');
        }

        // 3. Jika barang sudah ada di keranjang
        if (isset($cart[$id])) {
            if ($inputQty !== null) {
                // Dari halaman Show (replace/timpa angkanya sesuai input terbaru)
                $cart[$id]['quantity'] = (int) $inputQty;
            } else {
                // Dari halaman Katalog (+ tanpa masuk ke detail, tambah 1)
                $cart[$id]['quantity']++;
            }
            
            // Update notes jika ada perubahan
            if ($request->has('item_notes')) {
                $cart[$id]['item_notes'] = $request->input('item_notes');
            }
        } 
        // 4. Jika barang belum ada sama sekali
        else {
            $cart[$id] = [
                'menu_name' => $menu->menu_name,
                'quantity' => $inputQty !== null ? (int) $inputQty : 1,
                'price' => $menu->menu_price,
                'image' => $menu->menu_image,
                'item_notes' => $request->input('item_notes', '')
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    // Menampilkan halaman keranjang
    public function index()
    {
        // 1. Ambil data keranjang dari memori browser
        $cart = session()->get('cart', []);
        
        $subtotal = 0;

        // 2. Hitung subtotal nyata dari seluruh item
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 3. Hitung pajak 10%
        $tax = $subtotal * 0.10;
        
        // 4. Hitung Grand Total
        $total = $subtotal + $tax;

        // 5. Kirim data nyata ini ke halaman View
        return view('public.cart.index', compact('cart', 'subtotal', 'tax', 'total'));
    }

    // Menambah atau mengurangi jumlah item di keranjang
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');
        $action = $request->input('action'); // Menangkap aksi 'increase' atau 'decrease'

        if (isset($cart[$id])) {
            // Logika tambah/kurang
            if ($action === 'increase') {
                $cart[$id]['quantity']++;
            } elseif ($action === 'decrease') {
                $cart[$id]['quantity']--;
            }

            // LOGIKA ZERO TO HERO: Jika kuantitas menyentuh 0, otomatis hapus item dari Session!
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return redirect()->back()->with('success', 'Item automatically removed from cart');
            }

            // Jika masih lebih dari 0, simpan update-nya
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    // Menghapus item dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]); // Hapus datanya dari array
            session()->put('cart', $cart); // Simpan kembali array yang baru ke Session
        }

        return redirect()->back()->with('success', 'Menu removed from cart');
    }
}