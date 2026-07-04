@extends('layouts.public')
@section('title', 'Track Order ' . $order->order_number)

<!-- Tambahkan CSS Leaflet untuk Peta OpenStreetMap -->
@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Styling scrollbar untuk timeline horizontal di HP */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto px-6 py-12">

    <!-- BAGIAN 1: NOTA / RECEIPT (DI ATAS) -->
    <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row justify-between md:items-center border-b border-gray-100 pb-6 mb-6 gap-4">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Order Number</p>
                <h1 class="text-2xl font-black text-gray-900">{{ $order->order_number }}</h1>
                <span class="inline-block mt-2 bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    {{ str_replace('_', ' ', $order->order_type) }}
                </span>
            </div>
            <div class="text-left md:text-right">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Amount</p>
                <h2 class="text-3xl font-black text-orange-500">Rp {{ number_format($order->order_total, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Rincian Item (Ringkas) -->
        <div class="space-y-3">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Order Summary</h3>
            @foreach($order->orderItems as $item)
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center gap-3">
                    <span class="font-black text-gray-900">{{ $item->quantity }}x</span>
                    <span class="text-gray-600">{{ $item->menu->menu_name }}</span>
                </div>
                <span class="font-bold text-gray-900">Rp {{ number_format($item->item_subtotal, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- BAGIAN 2: TRACKING STATUS -->
    <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 shadow-sm">
        
        @if(in_array($order->order_type, ['dine_in', 'takeaway']))
            
            <!-- TRACKING HORIZONTAL (DINE IN / TAKEAWAY) -->
            <h3 class="text-lg font-black text-gray-900 mb-8">Order Status</h3>
            
            <div class="relative w-full overflow-x-auto hide-scrollbar pb-4">
                <!-- Wrapper yang cukup lebar agar tidak berdesakan di HP -->
                <div class="min-w-[600px] flex justify-between items-center relative pt-4">
                    
                    <!-- Garis Latar Belakang -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 z-0"></div>
                    
                    @php
                        // Logika sederhana untuk menentukan progress bar
                        $statuses = ['pending', 'paid', 'cooking', 'ready', 'completed'];
                        $currentIndex = array_search($order->order_status, $statuses);
                        if($currentIndex === false) $currentIndex = -1; // Kalau cancelled
                        
                        // Garis Aktif (Warna Oranye)
                        $progressWidth = ($currentIndex > 0) ? ($currentIndex / (count($statuses) - 1)) * 100 : 0;
                    @endphp

                    <!-- Garis Progress -->
                    <div class="absolute top-1/2 left-0 h-1 bg-orange-500 -translate-y-1/2 z-0 transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>

                    <!-- Titik-Titik Status -->
                    @foreach(['Placed', 'Paid', 'Cooking', 'Ready', 'Completed'] as $index => $label)
                        @php 
                            $isCompleted = $index <= $currentIndex;
                            $isActive = $index == $currentIndex;
                        @endphp
                        <div class="relative z-10 flex flex-col items-center w-24">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-sm transition-all duration-500 {{ $isCompleted ? 'bg-orange-500 text-white border-4 border-orange-100' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                                @if($isCompleted) <i class="bi bi-check-lg"></i> @else {{ $index + 1 }} @endif
                            </div>
                            <p class="mt-3 text-xs font-bold text-center {{ $isActive ? 'text-orange-500' : ($isCompleted ? 'text-gray-900' : 'text-gray-400') }}">
                                {{ $label }}
                            </p>
                        </div>
                    @endforeach

                </div>
            </div>

            @if($order->order_status == 'cancelled')
                <div class="mt-6 bg-red-50 text-red-600 p-4 rounded-xl text-center font-bold text-sm">
                    This order has been cancelled.
                </div>
            @endif

        @else
            <!-- TRACKING DELIVERY DENGAN MAP OPENSTREETMAP -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-gray-900">Delivery Tracking</h3>
                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold capitalize">
                    {{ $order->delivery->delivery_status ?? 'Waiting' }}
                </span>
            </div>

            <!-- Div untuk Peta -->
            <div id="osm-map" class="w-full h-[300px] md:h-[400px] rounded-2xl border border-gray-200 shadow-inner mb-8 z-0"></div>

            <!-- Status Delivery Horizontal -->
            <div class="relative w-full overflow-x-auto hide-scrollbar pb-4">
                <div class="min-w-[500px] flex justify-between items-center relative pt-4">
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 z-0"></div>
                    
                    @php
                        $delStatuses = ['waiting', 'assigned', 'on_delivery', 'delivered'];
                        $currentDelIndex = array_search($order->delivery->delivery_status ?? 'waiting', $delStatuses);
                        $delProgress = ($currentDelIndex > 0) ? ($currentDelIndex / (count($delStatuses) - 1)) * 100 : 0;
                    @endphp

                    <div class="absolute top-1/2 left-0 h-1 bg-blue-500 -translate-y-1/2 z-0 transition-all duration-1000" style="width: {{ $delProgress }}%"></div>

                    @foreach(['Waiting', 'Assigned', 'On Delivery', 'Delivered'] as $index => $label)
                        @php 
                            $isDelCompleted = $index <= $currentDelIndex;
                        @endphp
                        <div class="relative z-10 flex flex-col items-center w-24">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shadow-sm transition-all duration-500 {{ $isDelCompleted ? 'bg-blue-500 text-white ring-4 ring-blue-50' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                                @if($isDelCompleted) <i class="bi bi-check2"></i> @else <i class="bi bi-circle"></i> @endif
                            </div>
                            <p class="mt-2 text-[11px] font-bold text-center uppercase tracking-wider {{ $isDelCompleted ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $label }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
<!-- Panggil Leaflet JS hanya jika ini Delivery -->
@if($order->order_type == 'delivery')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Peta (Koordinat Titik Tengah)
        var map = L.map('osm-map').setView([-7.2756, 112.7939], 14); 

        // Gunakan layer gratis dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan Marker Animasi untuk Lokasi Pengiriman
        var marker = L.marker([-7.2756, 112.7939]).addTo(map);
        marker.bindPopup("<b>Delivery Destination</b><br>Pesanan menuju ke lokasi ini.").openPopup();
    });
</script>
@endif
@endsection