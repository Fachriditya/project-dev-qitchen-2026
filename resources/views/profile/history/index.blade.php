@extends('layouts.public')

@section('title', 'Order History - Qitchen')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5 w-full md:w-auto">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-sm shrink-0 border border-gray-200">
            @else
                <div class="w-16 h-16 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-2xl shadow-sm uppercase shrink-0">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif
            
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-bold px-2.5 py-1 rounded-full">
                        Member
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Auth::user()->email }} &bull; Member since {{ Auth::user()->created_at->format('Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-center md:pl-8 md:border-l md:border-gray-100 w-full md:w-auto">
            <div class="text-center w-full md:w-auto">
                <div class="text-3xl font-black text-orange-500">{{ $totalOrders ?? 0 }}</div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">Total Orders</div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-64 shrink-0 flex flex-col gap-2">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-person text-lg"></i> Profile
            </a>
            
            <a href="{{ route('profile.history') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold bg-orange-500 text-white shadow-md transition">
                <i class="bi bi-receipt text-lg"></i> Order History
            </a>
            
            <a href="{{ route('profile.vouchers') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-ticket-perforated text-lg"></i> My Vouchers
            </a>
            
            <a href="{{ route('profile.referral') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-gift text-lg"></i> Referral
            </a>

            <div class="w-full h-px bg-gray-100 my-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition">
                    <i class="bi bi-box-arrow-right text-lg"></i> Logout
                </button>
            </form>
        </div>

        <div class="w-full flex-grow flex flex-col md:flex-row gap-6">
            
            @if($orders->isEmpty())
                <div class="w-full bg-white border border-gray-100 rounded-2xl p-10 shadow-sm flex flex-col items-center justify-center text-center">
                    <i class="bi bi-receipt text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada pesanan</h3>
                    <p class="text-gray-500 mb-6">Yuk pesan menu favoritmu sekarang di Qitchen!</p>
                    <a href="{{ route('menu.index') }}" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-600 transition shadow-md">Lihat Menu</a>
                </div>
            @else
                
                <div class="w-full md:w-5/12 flex flex-col gap-4">
                    @foreach($orders as $order)
                        @php
                            // Cek apakah order ini sedang diklik
                            $isActive = $selectedOrder && $selectedOrder->order_id === $order->order_id;
                            
                            // Logika Warna Badge Status Dinamis
                            $statusColor = match(strtolower($order->order_status)) {
                                'completed', 'paid', 'settlement' => 'bg-green-50 text-green-600',
                                'cancelled', 'expire', 'deny', 'failed' => 'bg-red-50 text-red-600',
                                'cooking', 'ready' => 'bg-blue-50 text-blue-600',
                                default => 'bg-gray-100 text-gray-600'
                            };
                        @endphp

                        <a href="{{ route('profile.history', ['order_id' => $order->order_id]) }}" 
                           class="block bg-white border {{ $isActive ? 'border-orange-500 shadow-md ring-1 ring-orange-500' : 'border-gray-100 shadow-sm hover:border-orange-300' }} rounded-2xl p-5 transition cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-gray-900">#{{ $order->order_number }}</h4>
                                <span class="{{ $statusColor }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                    {{ $order->order_status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-end mt-4">
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($order->order_total, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="w-full md:w-7/12">
                    @if($selectedOrder)
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm sticky top-6">
                            
                            <div class="flex justify-between items-center pb-6 border-b border-gray-100 mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Order #{{ $selectedOrder->order_number }}</h3>
                                @php
                                    $detailStatusColor = match(strtolower($selectedOrder->order_status)) {
                                        'completed', 'paid', 'settlement' => 'bg-green-50 text-green-600',
                                        'cancelled', 'expire', 'deny', 'failed' => 'bg-red-50 text-red-600',
                                        'cooking', 'ready' => 'bg-blue-50 text-blue-600',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span class="{{ $detailStatusColor }} text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                                    {{ $selectedOrder->order_status }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-5 mb-6">
                                @foreach($selectedOrder->orderItems as $item)
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset('storage/' . $item->menu->image) }}" alt="{{ $item->menu->name }}" class="w-16 h-16 rounded-xl object-cover border border-gray-100">
                                        <div class="flex-grow">
                                            <h4 class="font-bold text-gray-900 text-sm">{{ $item->menu->name }}</h4>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->quantity }}x @ Rp {{ number_format($item->item_price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="font-bold text-gray-900 text-sm">
                                            Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="pt-6 border-t border-gray-100">
                                <div class="flex justify-between items-center mb-6">
                                    <span class="text-gray-500 font-medium text-lg">Total</span>
                                    <span class="text-2xl font-black text-gray-900">Rp {{ number_format($selectedOrder->order_total, 0, ',', '.') }}</span>
                                </div>
                                
                                <a href="{{ route('tracking.show', $selectedOrder->order_number) }}" class="block w-full py-3.5 rounded-xl border-2 border-gray-200 text-gray-700 text-center font-bold hover:bg-gray-50 hover:border-gray-300 transition text-sm">
                                    Track Order
                                </a>
                            </div>
                            
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
@endsection