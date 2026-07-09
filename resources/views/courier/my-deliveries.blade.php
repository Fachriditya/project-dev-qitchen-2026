@extends('layouts.master')
@section('title', 'My Deliveries')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">My Deliveries</h1>
        <p class="text-gray-500 font-medium mt-1">Track everything you've taken on today</p>
    </div>

    <div class="max-w-4xl">
        <!-- 🌟 TABS NAVIGATION -->
        <div class="flex items-center gap-8 border-b border-gray-200 mb-6">
            <a href="{{ route('courier.my-deliveries', ['tab' => 'active']) }}" 
               class="pb-3 text-sm font-bold transition-colors border-b-2 {{ $tab == 'active' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Active ({{ $activeDeliveries->count() }})
            </a>
            
            <a href="{{ route('courier.my-deliveries', ['tab' => 'accepted']) }}" 
               class="pb-3 text-sm font-bold transition-colors border-b-2 {{ $tab == 'accepted' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                Accepted ({{ $acceptedDeliveries->count() }})
            </a>
            
            <a href="{{ route('courier.my-deliveries', ['tab' => 'history']) }}" 
               class="pb-3 text-sm font-bold transition-colors border-b-2 {{ $tab == 'history' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                History ({{ $historyDeliveries->count() }})
            </a>
        </div>

        <!-- 🌟 KONTEN TABS -->
        <div class="space-y-4">
            
            <!-- TAB 1: ACTIVE (On Delivery) -->
            @if($tab == 'active')
                @forelse($activeDeliveries as $delivery)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-lg font-black font-mono text-gray-900">{{ $delivery->order->order_number }}</h2>
                                <span class="bg-orange-50 text-orange-600 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide">On the way</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $delivery->order->user->name ?? 'Guest' }} · {{ $delivery->order->user->address ?? 'Alamat Customer' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-5 justify-between md:justify-end">
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900">
                                {{ round((($delivery->order_id ?? $delivery->id) % 8) * 4) + 5 }} min
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">eta</p>
                        </div>
                        <a href="{{ route('courier.map', $delivery->order_id ?? $delivery->id) }}" class="bg-white border border-gray-200 hover:border-gray-300 text-gray-700 font-bold py-2 px-6 rounded-xl transition text-sm shadow-sm">
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 italic text-sm py-4">No active deliveries right now.</p>
                @endforelse

            <!-- TAB 2: ACCEPTED (Assigned) -->
            @elseif($tab == 'accepted')
                @forelse($acceptedDeliveries as $delivery)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-lg font-black font-mono text-gray-900">{{ $delivery->order->order_number }}</h2>
                                <span class="bg-yellow-50 text-yellow-600 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide">Heading to restaurant</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $delivery->order->user->name ?? 'Guest' }} · {{ $delivery->order->user->address ?? 'Alamat Customer' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-5 justify-between md:justify-end">
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900">—</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">eta</p>
                        </div>
                        <a href="{{ route('courier.map', $delivery->order_id ?? $delivery->id) }}" class="bg-white border border-gray-200 hover:border-gray-300 text-gray-700 font-bold py-2 px-6 rounded-xl transition text-sm shadow-sm">
                            Detail
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 italic text-sm py-4">No accepted deliveries right now.</p>
                @endforelse

            <!-- TAB 3: HISTORY (Delivered) -->
            @elseif($tab == 'history')
                @forelse($historyDeliveries as $delivery)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:shadow-md transition opacity-80">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-orange-50 text-orange-400 rounded-full flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h2 class="text-lg font-black font-mono text-gray-900">{{ $delivery->order->order_number }}</h2>
                                <span class="bg-green-50 text-green-600 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide">Delivered</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $delivery->order->user->name ?? 'Guest' }} · {{ $delivery->order->user->address ?? 'Alamat Customer' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-5 justify-between md:justify-end">
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900">{{ $delivery->updated_at->format('M d') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">delivered</p>
                        </div>
                        <button disabled class="bg-gray-50 border border-gray-200 text-gray-400 font-bold py-2 px-6 rounded-xl transition text-sm">
                            Detail
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 italic text-sm py-4">Delivery history is empty.</p>
                @endforelse
            @endif

        </div>
    </div>

</div>
@endsection