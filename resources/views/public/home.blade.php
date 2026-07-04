@extends('layouts.public')

@section('content')

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col md:flex-row items-center gap-12">
        
        <!-- Kolom Teks Kiri -->
        <div class="md:w-1/2">
            <span class="text-orange-500 font-bold text-xs tracking-widest uppercase bg-orange-50 px-4 py-1.5 rounded-full">
                #1 Self-service experience in town
            </span>
            <h1 class="mt-6 text-5xl md:text-[5.5rem] font-extrabold text-gray-900 tracking-tight leading-[1.1]">
                Great food, <br>
                <span class="text-orange-500">zero waiting</span> <br>
                in line.
            </h1>
            <p class="mt-6 text-lg text-gray-500 max-w-lg leading-relaxed">
                Order from your table, takeaway or get it delivered. Track every step from kitchen to pickup — no waiter required.
            </p>
            
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <a href="{{ route('menu.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-4 rounded-full font-bold transition shadow-lg shadow-orange-200 flex items-center gap-2">
                    Order Now 
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
                <a href="#how-it-works" class="bg-white border border-gray-200 hover:border-orange-500 hover:text-orange-500 text-gray-900 px-8 py-4 rounded-full font-bold transition shadow-sm">
                    How it works
                </a>
            </div>

            <!-- Statistik -->
            <div class="mt-12 flex gap-10">
                <div>
                    <h4 class="text-2xl font-extrabold text-gray-900">40k+</h4>
                    <p class="text-sm text-gray-500 font-medium">Happy diners</p>
                </div>
                <div>
                    <h4 class="text-2xl font-extrabold text-gray-900">1.2M</h4>
                    <p class="text-sm text-gray-500 font-medium">Order Served</p>
                </div>
                <div>
                    <h4 class="text-2xl font-extrabold text-gray-900">~12 min</h4>
                    <p class="text-sm text-gray-500 font-medium">Avg. prep time</p>
                </div>
            </div>
        </div>

        <!-- Kolom Gambar Kanan -->
        <div class="md:w-1/2 relative flex justify-center md:justify-end mt-12 md:mt-0">
            <div class="w-full max-w-md lg:max-w-lg aspect-[4/5] relative">
                <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=800&h=1000" alt="Delicious Burger" class="rounded-[2.5rem] object-cover w-full h-full shadow-2xl">
            </div>
        </div>
    </section>

    <!-- ================= CROWD FAVOURITES (ID MENU) ================= -->
    <section id="menu" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="text-orange-500 font-bold text-xs tracking-widest uppercase">Featured</span>
                <h2 class="mt-2 text-4xl font-extrabold text-gray-900">Crowd favourites</h2>
                <p class="mt-2 text-gray-500">The dishes our regulars can't stop ordering.</p>
            </div>
            <a href="{{ route('menu.index') }}" class="hidden md:flex font-bold text-gray-900 hover:text-orange-500 items-center gap-1 transition">
                View full menu &rarr;
            </a>
        </div>

        <!-- Grid Menu -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            @foreach($featuredMenus as $index => $menu)
            <!-- Card -->
            <div class="group cursor-pointer flex flex-col h-full">
                <!-- Wadah Gambar & Placeholder -->
                <div class="relative h-64 rounded-3xl overflow-hidden mb-4 bg-gray-50 border border-gray-100 flex items-center justify-center text-center">
                    
                    <!-- Badge Best Seller (Hanya muncul untuk menu urutan pertama) -->
                    @if($index === 0)
                        <span class="absolute top-4 left-4 z-20 bg-white px-3 py-1 rounded-full text-xs font-bold text-orange-500 shadow-sm">🏆 Top #1</span>
                    @endif

                    <!-- TEKS PLACEHOLDER (Berada di lapisan bawah / z-0) -->
                    <div class="absolute inset-0 z-0 flex items-center justify-center p-4">
                        <span class="font-bold text-gray-400 text-lg uppercase tracking-wider">{{ $menu->menu_name }}</span>
                    </div>

                    <!-- GAMBAR UTAMA (Berada di lapisan atas / z-10) -->
                    <!-- Trik Zero to Hero: 'onerror' akan menghilangkan tag img ini jika gambar gagal dimuat, sehingga teks placeholder di bawahnya akan terlihat! -->
                    <img src="{{ asset('storage/' . $menu->menu_image) }}" 
                         alt="{{ $menu->menu_name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500 relative z-10"
                         onerror="this.style.display='none'">
                </div>
                
                <!-- Info Konten -->
                <div class="flex justify-between items-start mb-1">
                    <h3 class="font-extrabold text-lg text-gray-900 line-clamp-1" title="{{ $menu->menu_name }}">{{ $menu->menu_name }}</h3>
                </div>
                
                <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-grow">{{ $menu->menu_description ?? 'Sajian istimewa dari dapur Qitchen yang disiapkan dengan bahan berkualitas.' }}</p>
                
                <!-- Harga & Tombol -->
                <div class="flex justify-between items-center mt-auto">
                    <p class="font-extrabold text-lg text-gray-900">Rp {{ number_format($menu->menu_price, 0, ',', '.') }}</p>
                    <a href="{{ route('menu.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-full font-bold text-sm transition">Buy Now</a>
                </div>
            </div>
            @endforeach
            
        </div>
            
        </div>
    </section>

    <!-- ================= HOW ORDERING WORKS ================= -->
    <section id="how-it-works" class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-orange-500 font-bold text-xs tracking-widest uppercase">How Ordering Works</span>
            <h2 class="mt-2 text-4xl font-extrabold text-gray-900">Four taps to a great meal</h2>
            <p class="mt-2 text-gray-500">No app download, no queues, no confusion.</p>

            <div class="mt-16 grid grid-cols-1 md:grid-cols-4 gap-8 text-left">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute -top-4 -right-4 text-[8rem] font-black text-gray-50 opacity-50 group-hover:text-orange-50 transition">01</div>
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center mb-6 relative z-10">📱</div>
                    <h3 class="font-extrabold text-lg text-gray-900 relative z-10">Browse & order</h3>
                    <p class="mt-2 text-sm text-gray-500 relative z-10">Pick your dishes and customize right from your phone or our kiosk.</p>
                </div>
                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute -top-4 -right-4 text-[8rem] font-black text-gray-50 opacity-50 group-hover:text-orange-50 transition">02</div>
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center mb-6 relative z-10">💳</div>
                    <h3 class="font-extrabold text-lg text-gray-900 relative z-10">Pay securely</h3>
                    <p class="mt-2 text-sm text-gray-500 relative z-10">QRIS, card or cash at the counter your choice, fully secure.</p>
                </div>
                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute -top-4 -right-4 text-[8rem] font-black text-gray-50 opacity-50 group-hover:text-orange-50 transition">03</div>
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center mb-6 relative z-10">🍳</div>
                    <h3 class="font-extrabold text-lg text-gray-900 relative z-10">We cook fresh</h3>
                    <p class="mt-2 text-sm text-gray-500 relative z-10">Watch your order move through our live kitchen board in real-time.</p>
                </div>
                <!-- Step 4 -->
                <div class="bg-white p-8 rounded-[2rem] shadow-sm relative overflow-hidden group hover:shadow-md transition">
                    <div class="absolute -top-4 -right-4 text-[8rem] font-black text-gray-50 opacity-50 group-hover:text-orange-50 transition">04</div>
                    <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-2xl flex items-center justify-center mb-6 relative z-10">🛍️</div>
                    <h3 class="font-extrabold text-lg text-gray-900 relative z-10">Pickup or delivery</h3>
                    <p class="mt-2 text-sm text-gray-500 relative z-10">Grab it at the counter when your number lights up, or get it delivered.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= DINE-IN & DELIVERY ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 flex flex-col md:flex-row items-center gap-16">
        <div class="md:w-1/2">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=800&h=600" alt="Restaurant Interior" class="rounded-[2.5rem] shadow-xl w-full object-cover">
        </div>
        <div class="md:w-1/2">
            <span class="text-orange-500 font-bold text-xs tracking-widest uppercase">Delivery & Dine-In</span>
            <h2 class="mt-2 text-4xl font-extrabold text-gray-900">However you like to eat</h2>
            
            <div class="mt-10 space-y-8">
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 shrink-0 text-xl">🛵</div>
                    <div>
                        <h4 class="font-extrabold text-gray-900">Lightning delivery</h4>
                        <p class="text-sm text-gray-500 mt-1">Live courier tracking with an accurate ETA, every single order.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 shrink-0 text-xl">🪑</div>
                    <div>
                        <h4 class="font-extrabold text-gray-900">Dine-in made simple</h4>
                        <p class="text-sm text-gray-500 mt-1">Scan your table code, order, and we'll call your number when it's ready.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 shrink-0 text-xl">✨</div>
                    <div>
                        <h4 class="font-extrabold text-gray-900">Quality guaranteed</h4>
                        <p class="text-sm text-gray-500 mt-1">Fresh ingredients prepped daily, or your next order is on us.</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('menu.index') }}" class="inline-block mt-10 bg-orange-500 hover:bg-orange-600 text-white px-8 py-3.5 rounded-full font-bold transition shadow-lg shadow-orange-200">Start your order &rarr;</a>
        </div>
    </section>

    <!-- ================= TESTIMONIAL ================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="bg-slate-900 rounded-[3rem] p-12 md:p-20 relative overflow-hidden">
            <div class="absolute -bottom-10 -right-6 text-[15rem] font-serif text-slate-800 leading-none select-none opacity-50">"</div>
            
            <div class="relative z-10 md:w-2/3">
                <span class="text-orange-500 font-bold text-xs tracking-widest uppercase">Members eat better</span>
                <h3 class="mt-6 text-3xl md:text-4xl font-semibold text-white leading-tight">
                    "I order my lunch on the walk over and it's ready the moment I arrive. The rewards points are just a bonus."
                </h3>
                
                <div class="mt-10 flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-900 font-bold text-lg">RE</div>
                    <div>
                        <h4 class="font-bold text-white">Ryan Ermaulid</h4>
                        <p class="text-xs text-slate-400">Qitchen member since 2024</p>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3.5 rounded-full font-bold transition">Join free</a>
                    <a href="{{ route('menu.index') }}" class="bg-slate-800 hover:bg-slate-700 text-white px-8 py-3.5 rounded-full font-bold transition">Browse menu</a>
                </div>
            </div>
        </div>
    </section>

@endsection