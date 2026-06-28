@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- ================= HEADER ================= -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-gray-500 font-medium mt-1">{{ now()->format('l, F j, Y') }} &middot; Surabaya location</p>
    </div>

    <!-- ================= 8 STAT CARDS (STYLING HIGH-FIDELITY) ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        @php
            $cards = [
                ['title' => 'Revenue',      'count' => 'Rp ' . number_format($data['total_revenue'], 0, ',', '.'), 'icon' => 'bi-currency-dollar', 'route' => '#'],
                ['title' => 'Transactions', 'count' => number_format($data['total_transactions'], 0, ',', '.'),  'icon' => 'bi-receipt', 'route' => '#'],
                ['title' => 'Deliveries',   'count' => number_format($data['total_deliveries'], 0, ',', '.'),    'icon' => 'bi-truck', 'route' => '#'],
                ['title' => 'Users',        'count' => number_format($data['total_users'], 0, ',', '.'),         'icon' => 'bi-people', 'route' => '#'],
                ['title' => 'Menus',        'count' => number_format($data['total_menus'], 0, ',', '.'),         'icon' => 'bi-journal-richtext', 'route' => '#'],
                ['title' => 'Categories',   'count' => number_format($data['total_categories'], 0, ',', '.'),    'icon' => 'bi-tags', 'route' => '#'],
                ['title' => 'Vouchers',     'count' => number_format($data['total_vouchers'], 0, ',', '.'),      'icon' => 'bi-ticket-perforated', 'route' => '#'],
                ['title' => 'Articles',     'count' => number_format($data['total_articles'], 0, ',', '.'),      'icon' => 'bi-newspaper', 'route' => '#'],
            ];
        @endphp

        @foreach($cards as $card)
            <!-- Tag <a> agar bisa diklik sebagai navigasi -->
            <a href="{{ $card['route'] }}" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:border-orange-500 hover:shadow-lg transition-all duration-300 group block">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-bold text-gray-500 group-hover:text-orange-500 transition">{{ $card['title'] }}</span>
                    <!-- Wadah ikon bundar oranye khas Figma-mu -->
                    <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center">
                        <i class="bi {{ $card['icon'] }} text-lg"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $card['count'] }}</h3>
            </a>
        @endforeach

    </div>

    <!-- ================= BOTTOM SECTION (GRID 2 KOLOM) ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- KIRI: Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Recent orders</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-xs tracking-wider uppercase border-b border-gray-100">
                            <th class="pb-4 font-bold">Order</th>
                            <th class="pb-4 font-bold">Customer</th>
                            <th class="pb-4 font-bold">Type</th>
                            <th class="pb-4 font-bold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 font-bold text-gray-900">#Q-{{ $order->order_number ?? $order->order_id }}</td>
                            <td class="py-4 text-gray-600">{{ $order->user->name ?? 'Walk-in' }}</td>
                            <td class="py-4 text-gray-500 capitalize">{{ $order->order_type }}</td>
                            <td class="py-4 font-bold text-gray-900 text-right">Rp {{ number_format($order->order_total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-400">Belum ada pesanan hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KANAN: Top Sellers -->
        <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
            <h3 class="text-xl font-extrabold text-gray-900 mb-6">Top sellers</h3>
            <div class="space-y-6">
                @forelse($topSellers as $index => $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <!-- Nomor Urut -->
                        <span class="text-gray-400 font-bold w-4">{{ $index + 1 }}</span>
                        
                        <!-- Gambar (Anggap ada kolom 'image' di tabel menu, jika tidak ada/kosong pakai Avatar otomatis) -->
                        @php
                            $menuName = $item->menu->name ?? 'Menu Dihapus';
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($menuName) . '&background=ffedd5&color=f97316';
                            // Kalau kamu punya fitur upload foto menu, nanti bisa diganti pakai asset('storage/'.$item->menu->image)
                        @endphp
                        <img src="{{ $avatarUrl }}" alt="{{ $menuName }}" class="w-12 h-12 rounded-full object-cover">
                        
                        <!-- Nama & Terjual -->
                        <div>
                            <!-- Ganti 'name' dengan nama kolom di tabel menumu (misal: menu_name) -->
                            <h4 class="font-bold text-gray-900 text-sm">{{ $menuName }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->total_sold }} sold</p>
                        </div>
                    </div>
                    <!-- Harga (Ganti 'price' dengan nama kolom hargamu misal: menu_price) -->
                    <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($item->menu->price ?? 0, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-center py-4 text-gray-400">
                    <p>Belum ada data penjualan.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection