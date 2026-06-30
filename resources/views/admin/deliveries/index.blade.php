@extends('layouts.master')
@section('title', 'Delivery Monitoring')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Delivery Monitoring</h1>
            <p class="text-gray-500 font-medium mt-1">Track and manage order deliveries</p>
        </div>
        
        <!-- Tombol Export CSV (Desain UI) -->
        <button class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-download mr-2 text-lg"></i> Export CSV
        </button>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Search & Filter Tab -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            
            <form action="{{ route('admin.deliveries.index') }}" method="GET" class="relative w-full max-w-md">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order, courier, or address..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>

            <!-- Filter Buttons: All | Active | Delivered -->
            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm shrink-0 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('admin.deliveries.index', ['search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    All
                </a>
                <a href="{{ route('admin.deliveries.index', ['status' => 'active', 'search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'active' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    Active
                </a>
                <a href="{{ route('admin.deliveries.index', ['status' => 'delivered', 'search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'delivered' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    Delivered
                </a>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8">Order</th>
                        <th class="p-5">Time & Date</th>
                        <th class="p-5">Courier</th>
                        <th class="p-5">Delivery Area / Address</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @forelse($deliveries as $delivery)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            
                            <!-- Order Number -->
                            <td class="p-5 pl-8 font-mono text-gray-900 font-bold">
                                {{ $delivery->order->order_number ?? '#Q-' . str_pad($delivery->order_id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Time & Date -->
                            <td class="p-5 text-gray-500 text-sm font-medium">
                                <span class="block text-gray-900 font-bold">{{ $delivery->created_at->format('H:i') }}</span>
                                {{ $delivery->created_at->format('d M Y') }}
                            </td>

                            <!-- Courier -->
                            <td class="p-5">
                                @if($delivery->courier)
                                    <span class="text-gray-900 font-bold">{{ $delivery->courier->name }}</span>
                                @else
                                    <span class="text-gray-400 italic font-medium text-sm">Not Assigned</span>
                                @endif
                            </td>
                            
                            <!-- Address (dibatasi panjang karakternya agar tidak merusak tabel) -->
                            <td class="p-5 text-gray-500 font-medium text-sm truncate max-w-xs" title="{{ $delivery->delivery_address }}">
                                <span class="block text-gray-900 font-bold">{{ $delivery->recipient_name }}</span>
                                {{ Str::limit($delivery->delivery_address, 40) }}
                            </td>

                            <!-- Status Badges -->
                            <td class="p-5">
                                @php
                                    $statusStr = strtolower($delivery->delivery_status);
                                    $statusColor = match($statusStr) {
                                        'waiting' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        'assigned' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'on_delivery' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'delivered' => 'bg-green-50 text-green-600 border-green-100',
                                        default => 'bg-gray-50 text-gray-500 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-full text-xs font-bold border capitalize {{ $statusColor }}">
                                    {{ str_replace('_', ' ', $delivery->delivery_status) }}
                                </span>
                            </td>
                            
                            <!-- Actions (Kembali Rapat ke Kanan) -->
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.deliveries.show', $delivery->delivery_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-orange-500 hover:bg-orange-50 hover:text-orange-600 transition shadow-sm" title="View Waybill">
                                    <i class="bi bi-eye-fill text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-truck text-4xl text-gray-300 block mb-3"></i>
                                No delivery records found.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        @if($deliveries->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $deliveries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection