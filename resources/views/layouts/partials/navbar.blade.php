<nav class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">

    <!-- Kiri: Logo -->
    <a href="{{ route('home') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl">Q</div>
        <span class="font-bold text-xl tracking-tight">Qitchen</span>
    </a>

    <!-- Tengah: Links -->
    <div class="hidden md:flex space-x-8 text-sm font-medium">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Home
        </a>
        <a href="{{ route('menu.index') }}" class="{{ request()->routeIs('menu.index') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Menu
        </a>
        <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Articles
        </a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            About
        </a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Contact
        </a>
    </div>

    <!-- Kanan: Cart & Auth -->
    <div class="hidden md:flex items-center gap-5">
        
        <!-- Ikon Keranjang (Dinamis) -->
        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-500 transition mr-2 flex items-center">
            <i class="bi bi-handbag text-xl"></i>
            
            <!-- Logika Zero to Hero: Menghitung total porsi (quantity) -->
            @php
                $totalPorsi = 0;
                if(session('cart')) {
                    foreach(session('cart') as $item) {
                        $totalPorsi += $item['quantity'];
                    }
                }
            @endphp

            <!-- Tampilkan badge hanya jika ada porsi di keranjang -->
            @if($totalPorsi > 0)
                <span class="absolute -top-1.5 -right-2 bg-orange-500 text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white">
                    {{ $totalPorsi }}
                </span>
            @endif
        </a>

        <!-- JIKA GUEST (Belum Login) -->
        @guest
            <div class="flex items-center gap-4 border-l border-gray-200 pl-5">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-orange-500 transition">Log in</a>
                <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-full text-sm font-medium transition shadow-md">Register</a>
            </div>
        @endguest

        <!-- JIKA MEMBER (Sudah Login) -->
        @auth
            <div class="flex items-center gap-3 border-l border-gray-200 pl-5 cursor-pointer group relative">
                <!-- Avatar Profil (Inisial Nama) -->
                <div class="w-9 h-9 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-sm">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <span class="font-bold text-sm text-gray-700">{{ Auth::user()->name }}</span>
                <i class="bi bi-chevron-down text-xs text-gray-400"></i>
                
                <!-- Dropdown Logout -->
                <div class="absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-5 py-3 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition">
                            <i class="bi bi-box-arrow-right mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        @endauth
        
    </div>
</nav>