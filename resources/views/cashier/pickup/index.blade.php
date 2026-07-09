@extends('layouts.master')
@section('title', 'Pickup Counter')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pickup Counter</h1>
        <p class="text-gray-500 font-medium mt-1">Confirm hand-off when customers collect their order</p>
    </div>

    <!-- SEARCH BAR -->
    <form action="{{ route('cashier.pickup.index') }}" method="GET" class="mb-6">
        <div class="relative w-full max-w-xl">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Type order number..." 
                   class="w-full bg-white border border-gray-100 text-gray-900 rounded-2xl px-5 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition shadow-sm text-sm" 
                   onchange="this.form.submit()" />
        </div>
    </form>

    <!-- NOTIFICATION TEXT -->
    <div class="flex items-center gap-2 mb-6 text-sm">
        <i class="bi bi-bell text-orange-500 font-bold"></i>
        <span class="text-gray-500 font-medium">{{ $readyCount }} orders ready for pickup</span>
    </div>

    <!-- GRID CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                
                <div>
                    <!-- Order Number & Badge -->
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-2xl font-black text-gray-900 font-mono">{{ $order->order_number }}</h2>
                        <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                            Ready
                        </span>
                    </div>

                    <!-- Customer Info -->
                    <p class="text-sm text-gray-500 font-medium mb-1">
                        {{ $order->user ? $order->user->name : 'Walk-in' }} · <span class="capitalize">{{ str_replace('_', ' ', $order->order_type) }}</span>
                    </p>

                    <!-- Waiting Time -->
                    <p class="text-xs text-gray-400 mb-6">
                        <!-- Menghitung selisih waktu dari pesanan dibuat sampai saat ini -->
                        Waiting {{ $order->created_at->diffInMinutes(now()) }} min
                    </p>
                </div>

                <!-- Confirm Button -->
                <form action="{{ route('cashier.pickup.confirm', $order->order_id ?? $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="bi bi-check2 text-lg"></i> Confirm pickup
                    </button>
                </form>

            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 text-3xl">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No orders ready</h3>
                <p class="text-gray-500 text-sm mt-1">Waiting for the kitchen to complete orders.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection