@extends('layouts.public')
@section('title', $menu->menu_name . ' - Qitchen')

@php
    // LOGIKA PENGECEKAN SESSION
    $cart = session()->get('cart', []);
    $isInCart = isset($cart[$menu->menu_id]);
    $currentQty = $isInCart ? $cart[$menu->menu_id]['quantity'] : 1;
    $currentNotes = $isInCart ? ($cart[$menu->menu_id]['item_notes'] ?? '') : '';
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    
    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 font-medium text-sm mb-8 transition">
        <i class="bi bi-arrow-left"></i> Back to menu
    </a>

    <!-- AREA DETAIL UTAMA -->
    <div class="flex flex-col lg:flex-row gap-12 mb-20">
        
        <!-- KIRI: Gambar -->
        <div class="w-full lg:w-1/2">
            <div class="w-full aspect-square bg-gray-100 rounded-[2rem] relative overflow-hidden flex items-center justify-center text-center">
                <div class="absolute inset-0 z-0 flex items-center justify-center p-6">
                    <span class="font-black text-gray-300 text-4xl uppercase tracking-widest">{{ $menu->menu_name }}</span>
                </div>
                <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="w-full h-full object-cover relative z-10 hover:scale-105 transition duration-700" onerror="this.style.display='none'">
            </div>
        </div>

        <!-- KANAN: Informasi -->
        <div class="w-full lg:w-1/2 flex flex-col pt-4">
            
            <div class="mb-6">
                <span class="bg-orange-50 text-orange-600 px-4 py-1.5 rounded-full text-xs font-black tracking-wide uppercase">
                    {{ $menu->category->category_name ?? 'Food' }}
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6 leading-tight">{{ $menu->menu_name }}</h1>

            <p class="text-gray-500 leading-relaxed mb-10 text-lg">
                {{ $menu->menu_description ?? 'Sajian istimewa dari dapur Qitchen yang disiapkan dengan bahan-bahan segar berkualitas tinggi.' }}
            </p>

            <!-- Form Pembelian -->
            <form action="{{ route('cart.add', $menu->menu_id) }}" method="POST" class="mt-auto">
                @csrf
                
                <!-- Item Notes (Dengan default value dari session jika ada) -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Special instructions (optional)</label>
                    <textarea name="item_notes" rows="2" placeholder="E.g., no onions, extra spicy..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 outline-none focus:ring-2 focus:ring-orange-500 transition">{{ $currentNotes }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-6">
                    <!-- Price -->
                    <div>
                        <p class="text-sm text-gray-400 font-medium mb-1">Total Price</p>
                        <!-- Harga otomatis dikalikan qty bawaan -->
                        <p class="text-4xl font-black text-gray-900" id="price-display">Rp {{ number_format($menu->menu_price * $currentQty, 0, ',', '.') }}</p>
                    </div>
                    
                    <!-- Kuantitas (Value diset dari Session) -->
                    <div class="flex items-center border border-gray-200 rounded-full bg-white w-max">
                        <button type="button" onclick="updateQty(-1)" class="w-12 h-12 flex items-center justify-center text-gray-500 hover:text-orange-500 transition font-bold text-xl rounded-l-full hover:bg-orange-50">-</button>
                        <input type="number" id="qty-input" name="quantity" value="{{ $currentQty }}" class="w-16 text-center font-black text-gray-900 bg-transparent outline-none pointer-events-none text-lg">
                        <button type="button" onclick="updateQty(1)" class="w-12 h-12 flex items-center justify-center text-gray-500 hover:text-orange-500 transition font-bold text-xl rounded-r-full hover:bg-orange-50">+</button>
                    </div>
                </div>

                <!-- Tombol Dinamis -->
                <button type="submit" id="submit-btn" class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                    <i class="bi bi-handbag" id="btn-icon"></i> 
                    <span id="btn-text">{{ $isInCart ? 'Update order' : 'Add to order' }}</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const basePrice = {{ $menu->menu_price }};
    const qtyInput = document.getElementById('qty-input');
    const priceDisplay = document.getElementById('price-display');
    
    // Tangkap elemen tombol
    const submitBtn = document.getElementById('submit-btn');
    const btnIcon = document.getElementById('btn-icon');
    const btnText = document.getElementById('btn-text');
    
    // Status awal dari server
    const wasInCart = {{ $isInCart ? 'true' : 'false' }};

    function updateQty(change) {
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;
        
        // SEKARANG BATAS BAWAHNYA ADALAH 0
        if (newQty >= 0) {
            qtyInput.value = newQty;
            
            let totalPrice = basePrice * newQty;
            let formattedPrice = new Intl.NumberFormat('id-ID').format(totalPrice);
            priceDisplay.innerText = 'Rp ' + formattedPrice;

            // LOGIKA UX: SULAP TOMBOL JADI TOMBOL HAPUS
            if (newQty === 0) {
                btnIcon.className = 'bi bi-trash text-xl';
                btnText.innerText = 'Remove from order';
                
                // Ubah gaya jadi merah
                submitBtn.classList.remove('bg-orange-500', 'hover:bg-orange-600', 'shadow-orange-500/30');
                submitBtn.classList.add('bg-red-500', 'hover:bg-red-600', 'shadow-red-500/30');
            } else {
                btnIcon.className = 'bi bi-handbag text-xl';
                btnText.innerText = wasInCart ? 'Update order' : 'Add to order';
                
                // Kembalikan ke warna oranye
                submitBtn.classList.remove('bg-red-500', 'hover:bg-red-600', 'shadow-red-500/30');
                submitBtn.classList.add('bg-orange-500', 'hover:bg-orange-600', 'shadow-orange-500/30');
            }
        }
    }
</script>
@endsection