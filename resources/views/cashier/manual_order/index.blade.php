@extends('layouts.master')
@section('title', 'Manual Order Input')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manual Order Input</h1>
        <p class="text-gray-500 font-medium mt-1">Build an order for a walk-in or phone customer</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- KOLOM KIRI: Daftar Menu -->
        <div class="lg:w-2/3 xl:w-3/4">
            
            <!-- 🌟 Search Bar & Filter Category (Zero to Hero Flexbox) -->
            <form action="{{ route('cashier.manual-order.index') }}" method="GET" class="mb-6 flex gap-3">
                
                <!-- Kolom Search (Lebar penuh) -->
                <div class="relative w-full">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search menu..." 
                           class="w-full bg-white border border-gray-200 text-gray-900 rounded-2xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition shadow-sm text-sm" 
                           onchange="this.form.submit()" />
                </div>

                <!-- Dropdown Category (Nempel di sebelah kanan) -->
                <select name="category" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition shadow-sm text-sm cursor-pointer shrink-0 appearance-none min-w-[150px]">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <!-- Sesuaikan category_name atau id dengan struktur databasemu -->
                        <option value="{{ $category->id ?? $category->category_id }}" {{ request('category') == ($category->id ?? $category->category_id) ? 'selected' : '' }}>
                            {{ $category->category_name ?? $category->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Tombol sembunyi untuk jaga-jaga kalau enter -->
                <button type="submit" class="hidden"></button>
            </form>

            <!-- Grid Menu -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($menus as $menu)
                
                <!-- 🌟 Tambahkan class "group" di sini biar animasi hover-nya jalan! -->
                <div class="group bg-white rounded-[1.5rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition">
                    
                    <!-- Bagian Gambar (Sesuai Kodingan Master Fachri) -->
                    <div class="w-full h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center text-center">
                        <!-- Teks Placeholder -->
                        <div class="absolute inset-0 z-0 flex items-center justify-center p-4">
                            <span class="font-bold text-gray-400 text-lg uppercase tracking-wider">{{ $menu->menu_name }}</span>
                        </div>
                        
                        <!-- Gambar Asli -->
                        <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 relative z-10" onerror="this.style.display='none'">
                        
                        <!-- Badge Kategori -->
                        <div class="absolute top-4 left-4 z-20 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm">
                            <span class="text-orange-600 font-black text-[10px] uppercase tracking-wider">
                                <!-- Pastikan relasi 'category' di Model Menu sudah kamu buat ya -->
                                {{ $menu->category->category_name ?? 'Promo' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-1">{{ $menu->menu_name }}</h3>
                        
                        <div class="flex justify-between items-center mt-2">
                            <span class="font-black text-gray-900 text-sm">Rp {{ number_format($menu->menu_price, 0, ',', '.') }}</span>
                            
                            <form action="{{ route('cashier.manual-order.add', $menu->menu_id ?? $menu->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-xs font-bold transition flex items-center shadow-sm">
                                    <i class="bi bi-plus text-lg leading-none mr-1"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                @empty
                <div class="col-span-full py-12 text-center text-gray-400 border-2 border-dashed border-gray-200 rounded-2xl mt-4">
                    <i class="bi bi-search text-3xl mb-2 block"></i>
                    <p class="font-medium">Menu tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="lg:w-1/3 xl:w-1/4">
            <div class="bg-white rounded-[1.5rem] border border-gray-100 shadow-sm p-6 sticky top-8">
                
                <h2 class="text-lg font-bold text-gray-900 mb-6">Current order</h2>

                @if(empty($cart))
                    <div class="text-center py-10 text-gray-400 border-2 border-dashed border-gray-100 rounded-2xl mb-6">
                        <i class="bi bi-cart text-3xl mb-2 block"></i>
                        <p class="text-sm font-medium">Cart is empty</p>
                    </div>
                @else
                    <div class="space-y-5 mb-6 max-h-[50vh] overflow-y-auto pr-2">
                        @foreach($cart as $id => $details)
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1 leading-tight">{{ $details['menu_name'] }}</h4>
                                <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg p-1 shrink-0">
                                <form action="{{ route('cashier.manual-order.update', $id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="decrease">
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-orange-500 transition"><i class="bi bi-dash"></i></button>
                                </form>
                                
                                <span class="w-6 text-center text-xs font-bold text-gray-900">{{ $details['quantity'] }}</span>
                                
                                <form action="{{ route('cashier.manual-order.update', $id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="increase">
                                    <button type="submit" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-orange-500 transition"><i class="bi bi-plus"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                <div class="border-t border-gray-100 pt-5 space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Subtotal</span>
                        <span class="text-gray-900 font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Tax (10%)</span>
                        <span class="text-gray-900 font-bold">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-gray-900 font-black">Total</span>
                        <span class="text-lg font-black text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('cashier.manual-order.process') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full bg-orange-500 hover:bg-orange-600 text-white text-center font-bold py-3.5 rounded-xl transition shadow-md {{ empty($cart) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                        Create order
                    </button>
                </form>

            </div>
        </div>
        
    </div>
</div>
@endsection