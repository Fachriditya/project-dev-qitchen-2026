@extends('layouts.public')
@section('title', 'Menu - Qitchen')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    
    <!-- HERO BANNER -->
    <div class="w-full bg-[#2a303c] rounded-3xl overflow-hidden relative flex flex-col md:flex-row items-center justify-between mb-12 shadow-lg">
        <div class="p-10 md:p-14 md:w-2/3 relative z-10">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold px-4 py-1.5 rounded-full inline-flex items-center mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span> Open now • ~12 min prep
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">Qitchen</h1>
            <p class="text-gray-300 font-medium text-sm md:text-base max-w-md leading-relaxed">
                Order ahead for pickup, dine-in or delivery.
            </p>
        </div>
        <!-- Gambar Burger Kanan -->
        <div class="md:w-1/3 h-full absolute right-0 top-0 bottom-0 hidden md:block">
            <div class="w-full h-full bg-orange-500 rounded-l-full" style="background-image: url('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'); background-size: cover; background-position: center;"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#2a303c] to-transparent"></div>
        </div>
    </div>

    <!-- MAIN KONTEN: SIDEBAR & GRID MENU -->
    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- SIDEBAR (KIRI) -->
        <div class="w-full lg:w-1/4 shrink-0">
            <div class="sticky top-28">
                <!-- Search Box -->
                <div class="relative mb-8">
                    <form action="{{ route('menu.index') }}" method="GET" class="relative mb-8">
                        
                        <!-- Trik agar filter kategori tetap nempel saat kita melakukan pencarian -->
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        
                        <!-- Tambahkan atribut name="search" dan value agar teks yang dicari tidak hilang dari kotak -->
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search dishes..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm font-bold text-gray-700 outline-none focus:ring-2 focus:ring-orange-500 transition">
                    </form>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-gray-400 font-black text-xs uppercase tracking-widest mb-4">Categories</h3>
                    <ul class="space-y-1">
                        <!-- Tombol ALL -->
                        <li>
                            <a href="{{ route('menu.index') }}" 
                               class="block px-4 py-2.5 font-bold rounded-xl transition 
                               {{ empty($selectedCategory) ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                All
                            </a>
                        </li>
                        
                        <!-- Tombol Looping Kategori -->
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('menu.index', ['category' => $category->category_name]) }}" 
                               class="block px-4 py-2.5 font-bold rounded-xl transition 
                               {{ $selectedCategory == $category->category_name ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                {{ $category->category_name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- GRID MENU (KANAN) -->
        <div class="w-full lg:w-3/4">
            <div class="mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-black text-gray-900">All <span class="text-gray-400 font-medium text-lg ml-2">• {{ $allMenus->count() }} items</span></h2>
            </div>

            <!-- Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                
                @forelse($allMenus as $menu)
                <div class="bg-white border border-gray-100 rounded-3xl overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group flex flex-col h-full">
                    
                    <!-- WRAPPER UNTUK KLIK SELURUH CARD -->
                    <!-- Kita bungkus gambar dan info konten dengan SATU link <a> -->
                    <a href="{{ route('menu.show', $menu->menu_id) }}" class="flex flex-col flex-grow">
                        
                        <!-- Bagian Gambar -->
                        <div class="w-full h-48 bg-gray-100 relative overflow-hidden flex items-center justify-center text-center">
                            <!-- Teks Placeholder -->
                            <div class="absolute inset-0 z-0 flex items-center justify-center p-4">
                                <span class="font-bold text-gray-400 text-lg uppercase tracking-wider">{{ $menu->menu_name }}</span>
                            </div>
                            <!-- Gambar Asli -->
                            <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 relative z-10" onerror="this.style.display='none'">
                            
                            <div class="absolute top-4 left-4 z-20 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm">
                                <span class="text-orange-600 font-black text-[10px] uppercase tracking-wider">{{ $menu->category->category_name ?? 'Promo' }}</span>
                            </div>
                        </div>

                        <!-- Bagian Tulisan (Info Konten) -->
                        <div class="p-5 flex-grow">
                            <h3 class="font-black text-gray-900 text-lg leading-tight mb-2 group-hover:text-orange-500 transition">{{ $menu->menu_name }}</h3>
                            <p class="text-sm text-gray-500 font-medium line-clamp-2 leading-relaxed">
                                {{ $menu->menu_description ?? 'Delicious meal prepared with love.' }}
                            </p>
                        </div>
                    </a>

                    <!-- BAGIAN BAWAH (Form/Tombol tidak ikut dibungkus <a>) -->
                    <div class="p-5 pt-0 mt-auto border-t border-gray-100">
                        <div class="flex justify-between items-center pt-4">
                            <p class="text-lg font-black text-gray-900">Rp {{ number_format($menu->menu_price, 0, ',', '.') }}</p>
                            
                            <form action="{{ route('cart.add', $menu->menu_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-orange-500 text-white px-5 py-2 rounded-full font-bold text-sm hover:bg-orange-600 transition shadow-md shadow-orange-500/20 flex items-center gap-1">
                                    Add <i class="bi bi-plus text-lg leading-none"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-gray-400 font-bold text-lg">No menu items available at the moment.</p>
                </div>
                @endforelse

            </div>
        </div>

    </div>
</div>
@endsection