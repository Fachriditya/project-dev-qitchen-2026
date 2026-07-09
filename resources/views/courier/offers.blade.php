@extends('layouts.master')
@section('title', 'Delivery Offers')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Delivery Offers</h1>
        <p class="text-gray-500 font-medium mt-1">Accept jobs near you — pay shown before you commit</p>
    </div>

    <!-- 🌟 STATS ROW (100% DINAMIS DARI DATABASE) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Available Now -->
        <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-0.5">Available now</p>
                <p class="text-2xl font-black text-gray-900 leading-none">{{ $availableOrders->count() }}</p>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-bicycle"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-0.5">You're online</p>
                <p class="text-lg font-black text-gray-900 leading-none">Active</p>
            </div>
        </div>

        <!-- Today's Earnings -->
        <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-0.5">Today's earnings</p>
                <p class="text-xl font-black text-gray-900 leading-none">Rp {{ number_format($todayEarnings, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-check2-all"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-0.5">Completed today</p>
                <p class="text-2xl font-black text-gray-900 leading-none">{{ $completedToday }}</p>
            </div>
        </div>
    </div>

    <!-- 🌟 GRID ORDER CARDS (ALGORITMA DINAMIS) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($availableOrders as $order)
            @php
                // ALGORITMA SAKTI MENTOR: 
                // Karena belum ada data jarak & ongkir di database, kita buat secara cerdas.
                // Modulo (%) bikin angkanya bervariasi tiap pesanan, tapi gak bakal berubah kalau di-refresh!
                
                $orderIdNum = $order->order_id ?? $order->id;
                
                // Jarak bervariasi antara 1.2 km sampai 4.0 km
                $distance = 1.2 + ($orderIdNum % 8) * 0.4; 
                
                // Estimasi waktu: 1 km = +/- 4 menit + waktu tunggu 3 menit
                $time = round($distance * 4) + 3; 
                
                // Ongkir: Base Rp 10.000 + (Jarak bulat * Rp 2.500)
                $deliveryFee = 10000 + (floor($distance) * 2500); 
            @endphp

            <div class="bg-white rounded-[1.5rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                
                <!-- Card Header -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-xl font-black font-mono text-gray-900">{{ $order->order_number }}</h2>
                            <span class="bg-orange-50 text-orange-600 px-2.5 py-0.5 rounded-full text-xs font-bold">{{ $order->orderItems->count() }} items</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <!-- Harga Ongkir (Hasil Algoritma) -->
                        <h3 class="text-xl font-black text-green-500 leading-none">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-wider">Delivery fee</p>
                    </div>
                </div>

                <!-- Rute & Waktu -->
                <div class="space-y-3 mb-8">
                    <!-- Rute -->
                    <div class="flex items-start gap-3">
                        <i class="bi bi-geo-alt text-gray-400 mt-0.5"></i>
                        <p class="text-sm font-medium text-gray-600">
                            Qitchen Pusat <i class="bi bi-arrow-right mx-1 text-gray-400"></i> 
                            <span class="text-gray-900">{{ $order->user->address ?? 'Alamat belum diatur' }}</span>
                        </p>
                    </div>
                    <!-- Jarak & Est Waktu (Hasil Algoritma) -->
                    <div class="flex items-center gap-4 text-sm text-gray-500 font-medium pl-7">
                        <span class="flex items-center gap-1.5"><i class="bi bi-cursor"></i> {{ number_format($distance, 1) }} km</span>
                        <span class="flex items-center gap-1.5"><i class="bi bi-clock"></i> {{ $time }} min</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <button type="button" class="w-1/3 bg-white border border-gray-200 hover:border-gray-300 text-gray-700 font-bold py-3 rounded-xl transition text-sm">
                        View detail
                    </button>
                    
                    <!-- TOMBOL ACCEPT -->
                    <form action="{{ route('courier.accept', $order->order_id ?? $order->id) }}" method="POST" class="w-2/3">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition shadow-md shadow-orange-500/20 text-sm">
                            Accept
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-4xl shadow-inner">
                    <i class="bi bi-wind"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">No deliveries available</h3>
                <p class="text-gray-500 mt-2">Hang tight! New orders will appear here once the kitchen finishes them.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Auto-refresh biar kurir dapet orderan terbaru -->
<script>
    setTimeout(function() {
        window.location.reload();
    }, 60000); // Refresh otomatis setiap 60 detik
</script>
@endsection