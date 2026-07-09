@extends('layouts.master')
@section('title', 'Transaction Detail')

@section('head')
<style>
    /* Styling scrollbar untuk timeline horizontal di HP[cite: 4] */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- HEADER -->
    <div class="flex justify-between items-center mb-8 max-w-5xl mx-auto">
        <div class="flex items-center gap-4">
            <a href="{{ route('cashier.transactions.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Order Details</h1>
        </div>
        
        <!-- TOMBOL DOWNLOAD NOTA -->
        <button onclick="downloadNota()" class="bg-orange-500 text-white px-6 py-2.5 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-download mr-2"></i> Download Nota
        </button>
    </div>

    <!-- WRAPPER UTAMA -->
    <div class="max-w-5xl mx-auto space-y-8">

        <!-- KARTU 1: TIMELINE TRACKING (Diadaptasi dari Customer UI)[cite: 4] -->
        <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 shadow-sm">
            <h3 class="text-lg font-black text-gray-900 mb-8">Order Progress</h3>
            
            <div class="relative w-full overflow-x-auto hide-scrollbar pb-4">
                <div class="min-w-[600px] flex justify-between items-center relative pt-4">
                    
                    <!-- Garis Latar Belakang[cite: 4] -->
                    <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 z-0"></div>
                    
                    @php
                        // Logika progress bar[cite: 4]
                        $statuses = ['pending', 'paid', 'cooking', 'ready', 'completed'];
                        $currentIndex = array_search($transaction->order_status, $statuses);
                        if($currentIndex === false) $currentIndex = -1; // Cancelled
                        
                        // Kalkulasi lebar garis oranye[cite: 4]
                        $progressWidth = ($currentIndex > 0) ? ($currentIndex / (count($statuses) - 1)) * 100 : 0;
                    @endphp

                    <!-- Garis Progress[cite: 4] -->
                    <div class="absolute top-1/2 left-0 h-1 bg-orange-500 -translate-y-1/2 z-0 transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>

                    <!-- Titik-Titik Status[cite: 4] -->
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
            
            @if($transaction->order_status == 'cancelled')
                <div class="mt-6 bg-red-50 text-red-600 p-4 rounded-xl text-center font-bold text-sm">
                    This order has been cancelled.
                </div>
            @endif
        </div>

        <!-- KARTU 2: NOTA LENGKAP (Diadaptasi dari Admin UI)[cite: 3] -->
        <div id="nota-card" class="bg-white rounded-[2rem] border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
            
            <!-- Header Nota[cite: 3] -->
            <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <p class="text-gray-500 font-medium text-sm mb-1">Order Number</p>
                    <h2 class="text-2xl font-black font-mono text-gray-900">{{ $transaction->order_number ?? '#Q-' . str_pad($transaction->order_id, 5, '0', STR_PAD_LEFT) }}</h2>
                    <div class="flex items-center gap-4 mt-2">
                        <p class="text-gray-500 text-sm font-medium"><i class="bi bi-calendar3 mr-1"></i> {{ $transaction->created_at->format('d M Y') }}</p>
                        <p class="text-gray-500 text-sm font-medium"><i class="bi bi-clock mr-1"></i> {{ $transaction->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-gray-500 font-medium text-sm mb-1">Status</p>
                    <span class="inline-block bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-sm font-bold capitalize border border-orange-200">
                        {{ $transaction->order_status }}
                    </span>
                </div>
            </div>

            <!-- Info Pelanggan, Order, & Payment (3 Kolom)[cite: 3] -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-gray-100">
                <!-- Customer Info -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customer Info</p>
                    <p class="text-gray-900 font-bold text-lg">
                        @if($transaction->user)
                            {{ $transaction->user->name }}
                        @else
                            Guest (Walk-in)
                        @endif
                    </p>
                    @if($transaction->user)
                        <p class="text-gray-500">{{ $transaction->user->phone ?? 'No Phone' }}</p>
                        <p class="text-gray-500">{{ $transaction->user->email }}</p>
                    @endif
                </div>

                <!-- Order Type -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Order Type</p>
                    <p class="text-gray-900 font-bold text-lg capitalize flex items-center">
                        <i class="bi {{ $transaction->order_type == 'dine_in' ? 'bi-shop' : ($transaction->order_type == 'takeaway' ? 'bi-bag' : 'bi-truck') }} mr-2 text-orange-500"></i>
                        {{ str_replace('_', ' ', $transaction->order_type) }}
                    </p>
                    @if($transaction->order_notes)
                        <div class="mt-3 p-3 bg-yellow-50 rounded-xl border border-yellow-100 text-yellow-700 text-sm">
                            <strong>Notes:</strong> {{ $transaction->order_notes }}
                        </div>
                    @endif
                </div>

                <!-- Payment Info -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Payment Info</p>
                    <p class="text-gray-900 font-bold text-lg capitalize flex items-center">
                        <i class="bi bi-wallet2 mr-2 text-blue-500"></i>
                        {{ $transaction->payment->payment_method ?? 'Cash' }}
                    </p>
                    @if($transaction->payment)
                        <p class="text-gray-500 mt-1 capitalize text-sm">
                            Status: <span class="{{ $transaction->payment->payment_status == 'success' || $transaction->payment->payment_status == 'paid' ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $transaction->payment->payment_status }}</span>
                        </p>
                    @else
                        <p class="text-gray-500 mt-1 text-sm font-bold text-red-500">Unpaid / Pending</p>
                    @endif
                </div>
            </div>

            <!-- Daftar Menu[cite: 3] -->
            <div class="p-8 border-b border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Order Summary</p>
                <div class="space-y-4">
                    @forelse($transaction->orderItems as $item)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 text-sm">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <p class="text-gray-900 font-bold">{{ $item->menu->menu_name ?? 'Item Deleted' }}</p>
                                    <p class="text-gray-400 text-sm">Rp {{ number_format($item->item_price ?? $item->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <p class="text-gray-900 font-bold">
                                Rp {{ number_format($item->item_subtotal ?? ($item->quantity * $item->price), 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 italic text-sm">No items found.</p>
                    @endforelse
                </div>
            </div>

            <!-- Rincian Biaya[cite: 3] -->
            <div class="p-8 bg-gray-50/50">
                <div class="space-y-3 max-w-sm ml-auto">
                    <div class="flex justify-between text-gray-500">
                        <p>Subtotal</p>
                        <p class="font-bold text-gray-900">Rp {{ number_format($transaction->order_subtotal, 0, ',', '.') }}</p>
                    </div>
                    
                    @if($transaction->order_discount > 0)
                        <div class="flex justify-between text-green-500">
                            <p class="flex items-center"><i class="bi bi-ticket-perforated mr-2"></i> Discount</p>
                            <p class="font-bold">- Rp {{ number_format($transaction->order_discount, 0, ',', '.') }}</p>
                        </div>
                    @endif
                    
                    <div class="border-t border-gray-200 my-2 pt-3 flex justify-between items-center">
                        <p class="font-black text-gray-900 text-lg">Total Amount</p>
                        <p class="font-black text-orange-500 text-2xl">Rp {{ number_format($transaction->order_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
<!-- Script untuk menyulap HTML menjadi Gambar[cite: 3] -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadNota() {
        const notaElement = document.getElementById('nota-card');
        
        // Ubah menjadi canvas HD (scale: 2)[cite: 3]
        html2canvas(notaElement, { scale: 2 }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'Nota-{{ $transaction->order_number ?? $transaction->order_id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>
@endsection