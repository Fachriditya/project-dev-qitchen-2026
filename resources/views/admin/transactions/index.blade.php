@extends('layouts.master')
@section('title', 'Transaction Monitoring')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Transaction Monitoring</h1>
            <p class="text-gray-500 font-medium mt-1">All transactions across locations</p>
        </div>
        
        <button class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-download mr-2 text-lg"></i> Export CSV
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Area Search & Date Filter -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="relative w-full max-w-md">
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order number or customer..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>

            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm shrink-0 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('admin.transactions.index', ['search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('date') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">All</a>
                <a href="{{ route('admin.transactions.index', ['date' => 'today', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'today' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">Today</a>
                <a href="{{ route('admin.transactions.index', ['date' => 'week', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'week' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">This week</a>
                <a href="{{ route('admin.transactions.index', ['date' => 'month', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'month' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">This month</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8">Order</th>
                        <th class="p-5">Time</th>
                        <th class="p-5">Customer</th>
                        <th class="p-5">Type</th>
                        <th class="p-5">Payment</th>
                        <th class="p-5">Status</th>
                        <th class="p-5">Total</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <!-- Order Number -->
                            <td class="p-5 pl-8 font-mono text-gray-900 font-bold">
                                {{ $transaction->order_number ?? '#Q-' . str_pad($transaction->order_id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            <!-- Time (JAM & TANGGAL) -->
                            <td class="p-5 text-gray-500 text-sm font-medium">
                                {{ $transaction->created_at->format('d M, H:i') }}
                            </td>

                            <!-- Customer (Member vs Guest Logic) -->
                            <td class="p-5">
                                @if($transaction->user)
                                    <span class="text-gray-900 font-bold">{{ $transaction->user->name }}</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-3 py-1.5 rounded-full text-xs font-bold border border-gray-200">
                                        Guest
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Order Type -->
                            <td class="p-5 text-gray-500 font-medium capitalize">
                                {{ str_replace('_', ' ', $transaction->order_type) }}
                            </td>

                            <!-- Payment Method -->
                            <td class="p-5 text-gray-900 font-bold capitalize">
                                @if($transaction->payment)
                                    {{ $transaction->payment->payment_method ?? 'Cash' }}
                                @else
                                    <span class="text-gray-400 font-normal italic">-</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-5">
                                @php
                                    $statusStr = strtolower($transaction->order_status);
                                    $statusColor = match($statusStr) {
                                        'pending' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                        'cooking' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'ready' => 'bg-green-50 text-green-600 border-green-100',
                                        'paid' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'completed' => 'bg-gray-100 text-gray-600 border-gray-200',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-gray-50 text-gray-500 border-gray-200'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border capitalize {{ $statusColor }}">
                                    {{ $transaction->order_status }}
                                </span>
                            </td>

                            <!-- Total -->
                            <td class="p-5 text-gray-900 font-bold">
                                Rp {{ number_format($transaction->order_total, 0, ',', '.') }}
                            </td>
                            
                            <!-- Action -->
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.transactions.show', $transaction->order_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-orange-500 hover:bg-orange-50 hover:text-orange-600 transition shadow-sm" title="View Detail">
                                    <i class="bi bi-eye-fill text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-receipt text-4xl text-gray-300 block mb-3"></i>
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection