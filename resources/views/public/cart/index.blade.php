@extends('layouts.public')
@section('title', 'Your Cart - Qitchen')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    
    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-8">Your cart</h1>

    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- KIRI: Daftar Menu Keranjang -->
        <div class="lg:w-2/3">
            <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 shadow-sm">
                
                @if(session('cart') && count(session('cart')) > 0)
                    @foreach(session('cart') as $id => $details)
                    <!-- Item Keranjang -->
                    <div class="flex gap-4 md:gap-6 py-6 border-b border-gray-100 last:border-0 relative">
                        
                        <!-- Gambar dengan Placeholder Trik -->
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-100 rounded-2xl shrink-0 relative overflow-hidden flex items-center justify-center text-center">
                            <div class="absolute inset-0 z-0 flex items-center justify-center p-2">
                                <span class="font-bold text-gray-300 text-[10px] uppercase tracking-wider">{{ substr($details['menu_name'], 0, 5) }}</span>
                            </div>
                            <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['menu_name'] }}" class="w-full h-full object-cover relative z-10" onerror="this.style.display='none'">
                        </div>

                        <!-- Detail (Nama, Harga, Qty) -->
                        <div class="flex flex-col justify-between flex-grow">
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base md:text-lg">{{ $details['menu_name'] }}</h3>
                                    <p class="text-gray-500 text-sm mt-1">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                </div>
                                <!-- Tombol Hapus (Trash) -->
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1">
                                        <i class="bi bi-trash text-lg md:text-xl"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="flex justify-between items-end mt-4">
                                <!-- Kuantitas Dinamis -->
                                <div class="flex items-center border border-gray-200 rounded-full bg-white px-1">
                                    
                                    <!-- Tombol Kurang (Decrease) -->
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition font-bold">-</button>
                                    </form>

                                    <!-- Angka Kuantitas -->
                                    <span class="w-8 text-center font-bold text-gray-900 text-sm">{{ $details['quantity'] }}</span>
                                    
                                    <!-- Tombol Tambah (Increase) -->
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="increase">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-orange-500 transition font-bold">+</button>
                                    </form>
                                    
                                </div>
                                <!-- Subtotal per item (Harga Asli x Jumlah) -->
                                <p class="font-black text-gray-900 text-base md:text-lg">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                            </div>

                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Tampilan Jika Keranjang Kosong -->
                    <div class="py-12 text-center">
                        <i class="bi bi-cart-x text-6xl text-gray-300 mb-4 block"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Cart is empty</h3>
                        <p class="text-gray-500 font-medium">Looks like you haven't added anything yet.</p>
                    </div>
                @endif

                <!-- Tombol Add More Items -->
                <div class="mt-6 pt-4">
                    <a href="{{ route('menu.index') }}" class="text-orange-500 font-bold hover:text-orange-600 transition flex items-center gap-2 w-max">
                        <i class="bi bi-plus-lg"></i> Add more items
                    </a>
                </div>
            </div>
        </div>

        <!-- KANAN: Order Summary (Data Asli) -->
        <div class="lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 sticky top-28 shadow-sm">
                <h3 class="text-xl font-black text-gray-900 mb-6">Order summary</h3>

                <div class="space-y-4 mb-6 text-sm font-medium">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Tax (10%)</span>
                        <span class="text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-6 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-bold text-lg">Total</span>
                        <span class="text-2xl font-black text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Checkout (Arahkan ke rute checkout nanti) -->
                <a href="{{ route('checkout.index') }}" class="block w-full bg-orange-500 text-white text-center py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                    Checkout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection