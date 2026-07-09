@extends('layouts.master')
@section('title', 'Kitchen Board')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- HEADER -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kitchen Board</h1>
            <p class="text-gray-500 font-medium mt-1">Live order queue — tap a card to advance it</p>
        </div>
        <div class="hidden md:flex items-center gap-2 text-gray-500 font-bold bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm">
            <i class="bi bi-fire text-orange-500"></i> Station: <span class="text-gray-900">Grill 1</span>
        </div>
    </div>

    <!-- KANBAN BOARD (3 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- ============================================== -->
        <!-- KOLOM 1: WAITING (Status: paid) -->
        <!-- ============================================== -->
        <div class="bg-gray-50 rounded-[2rem] p-4 border border-gray-100 flex flex-col gap-4">
            
            <!-- Header Kolom -->
            <div class="flex justify-between items-center px-2 mb-2">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center"><i class="bi bi-clock-history"></i></div>
                    Waiting
                </h3>
                <span class="bg-white text-orange-600 font-black px-3 py-1 rounded-full text-xs shadow-sm">{{ $waiting->count() }}</span>
            </div>

            <!-- List Card -->
            @foreach($waiting as $order)
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-xl font-black font-mono text-gray-900">{{ $order->order_number }}</h4>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>

                <div class="space-y-3 mb-5 border-l-2 border-orange-200 pl-3">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#1a2332] text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $item->quantity }}</span>
                        <span class="text-sm font-bold text-gray-700 leading-tight">{{ $item->menu->menu_name ?? 'Menu Dihapus' }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Notes / Catatan Pesanan -->
                @if($order->order_notes && $order->order_notes != 'Name: Walk-in Guest | POS Order')
                <div class="bg-yellow-50 text-yellow-700 text-xs font-bold p-3 rounded-xl mb-4 flex gap-2 items-start border border-yellow-100">
                    <i class="bi bi-pencil shrink-0 mt-0.5"></i> <span>{{ $order->order_notes }}</span>
                </div>
                @endif

                <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-100">
                    <span class="text-xs font-bold text-gray-400 flex items-center gap-1"><i class="bi bi-stopwatch"></i> {{ $order->updated_at->diffInMinutes(now()) }} min</span>
                    
                    <form action="{{ route('kitchen.update-status', $order->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="cooking">
                        <button type="submit" class="bg-white border border-gray-200 hover:border-orange-500 hover:text-orange-500 text-gray-900 font-bold text-xs px-4 py-2 rounded-xl transition shadow-sm">
                            Start cooking
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ============================================== -->
        <!-- KOLOM 2: COOKING (Status: cooking) -->
        <!-- ============================================== -->
        <div class="bg-orange-50/50 rounded-[2rem] p-4 border border-orange-100 flex flex-col gap-4">
            
            <!-- Header Kolom -->
            <div class="flex justify-between items-center px-2 mb-2">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center"><i class="bi bi-fire"></i></div>
                    Cooking
                </h3>
                <span class="bg-white text-orange-600 font-black px-3 py-1 rounded-full text-xs shadow-sm">{{ $cooking->count() }}</span>
            </div>

            <!-- List Card -->
            @foreach($cooking as $order)
            <div class="bg-white rounded-3xl p-5 border-2 border-orange-500 shadow-sm relative overflow-hidden">
                <!-- Aksen Pita Sedang Dimasak -->
                <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
                    <div class="absolute w-24 h-6 bg-orange-500 text-white text-[8px] font-black uppercase text-center flex items-center justify-center transform rotate-45 translate-x-4 -translate-y-2 tracking-widest shadow-sm">Hot</div>
                </div>

                <div class="flex justify-between items-start mb-4 pr-6">
                    <h4 class="text-xl font-black font-mono text-gray-900">{{ $order->order_number }}</h4>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>

                <div class="space-y-3 mb-5 border-l-2 border-orange-500 pl-3">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 rounded-full bg-[#1a2332] text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $item->quantity }}</span>
                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ $item->menu->menu_name ?? 'Menu Dihapus' }}</span>
                    </div>
                    @endforeach
                </div>

                @if($order->order_notes && $order->order_notes != 'Name: Walk-in Guest | POS Order')
                <div class="bg-yellow-50 text-yellow-700 text-xs font-bold p-3 rounded-xl mb-4 flex gap-2 items-start border border-yellow-100">
                    <i class="bi bi-pencil shrink-0 mt-0.5"></i> <span>{{ $order->order_notes }}</span>
                </div>
                @endif

                <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-100">
                    <span class="text-xs font-bold text-orange-500 flex items-center gap-1 animate-pulse"><i class="bi bi-stopwatch"></i> {{ $order->updated_at->diffInMinutes(now()) }} min</span>
                    
                    <form action="{{ route('kitchen.update-status', $order->order_id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ready">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition shadow-md">
                            Mark ready
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- ============================================== -->
        <!-- KOLOM 3: READY (Status: ready) -->
        <!-- ============================================== -->
        <div class="bg-green-50/30 rounded-[2rem] p-4 border border-green-100 flex flex-col gap-4 opacity-75 hover:opacity-100 transition">
            
            <!-- Header Kolom -->
            <div class="flex justify-between items-center px-2 mb-2">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center"><i class="bi bi-check2-all text-xl"></i></div>
                    Ready
                </h3>
                <span class="bg-white text-green-600 font-black px-3 py-1 rounded-full text-xs shadow-sm border border-green-100">{{ $ready->count() }}</span>
            </div>

            <!-- List Card -->
            @foreach($ready as $order)
            <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-green-400"></div>

                <div class="flex justify-between items-start mb-4 pl-3">
                    <h4 class="text-xl font-black font-mono text-gray-900 line-through decoration-gray-300">{{ $order->order_number }}</h4>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">{{ str_replace('_', ' ', $order->order_type) }}</span>
                </div>

                <div class="space-y-2 mb-4 pl-3 opacity-60">
                    @foreach($order->orderItems as $item)
                    <div class="flex items-center gap-3">
                        <span class="w-5 h-5 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ $item->quantity }}</span>
                        <span class="text-xs font-bold text-gray-500 leading-tight">{{ $item->menu->menu_name ?? 'Menu Dihapus' }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-gray-100 pl-3">
                    <span class="text-xs font-bold text-gray-400 flex items-center gap-1"><i class="bi bi-stopwatch"></i> {{ $order->updated_at->diffInMinutes(now()) }} min</span>
                    <span class="text-[10px] font-bold text-green-500 uppercase tracking-wider bg-green-50 px-2 py-1 rounded">Awaiting pickup</span>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

<!-- AUTO REFRESH SCRIPT -->
<!-- Merefresh halaman secara diam-diam setiap 30 detik agar koki mendapat pesanan terbaru -->
<script>
    setTimeout(function() {
        window.location.reload();
    }, 30000); 
</script>
@endsection