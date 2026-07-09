@extends('layouts.master')
@section('title', 'Transaction Monitoring - Cashier')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Transaction Monitoring</h1>
            <p class="text-gray-500 font-medium mt-1">Live feed of every order across the floor</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-sm font-medium text-gray-500 mb-2">Open orders</div>
            <div class="text-3xl font-black text-gray-900">{{ $openOrders }}</div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-sm font-medium text-gray-500 mb-2">Awaiting payment</div>
            <div class="text-3xl font-black text-gray-900">{{ $awaitingPayment }}</div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-sm font-medium text-gray-500 mb-2">Ready to serve</div>
            <div class="text-3xl font-black text-gray-900">{{ $readyToServe }}</div>
        </div>
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="text-sm font-medium text-gray-500 mb-2">Today's revenue</div>
            <div class="text-2xl font-black text-gray-900">Rp {{ number_format($todaysRevenue, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            
            <form action="{{ route('cashier.transactions.index') }}" method="GET" class="relative w-full max-w-md flex gap-3">
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <div class="relative w-full">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order # or customer..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white outline-none transition shadow-sm text-sm" />
                </div>
                <button type="submit" class="shrink-0 bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-full hover:bg-gray-50 transition text-sm font-bold flex items-center shadow-sm">
                    <i class="bi bi-funnel mr-2"></i> Filter
                </button>
            </form>

            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm shrink-0 overflow-x-auto whitespace-nowrap">
                <a href="{{ route('cashier.transactions.index', ['search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('date') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">All</a>
                <a href="{{ route('cashier.transactions.index', ['date' => 'today', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'today' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">Today</a>
                <a href="{{ route('cashier.transactions.index', ['date' => 'week', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'week' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">This week</a>
                <a href="{{ route('cashier.transactions.index', ['date' => 'month', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('date') == 'month' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">This month</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8">Order</th>
                        <th class="p-5">Customer</th>
                        <th class="p-5">Type</th>
                        <th class="p-5">Status</th>
                        <th class="p-5">Total</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="p-5 pl-8 font-bold text-gray-900">
                                {{ $transaction->order_number }}
                            </td>

                            <td class="p-5 text-gray-700">
                                @if($transaction->user)
                                    {{ $transaction->user->name }}
                                @else
                                    {{-- Mengambil nama Guest dari order_notes --}}
                                    @php
                                        preg_match('/Name: (.*?) \|/', $transaction->order_notes, $matches);
                                        $guestName = $matches[1] ?? 'Walk-in Guest';
                                    @endphp
                                    {{ $guestName }}
                                @endif
                            </td>
                            
                            <td class="p-5 text-gray-500 capitalize">
                                {{ str_replace('_', ' ', $transaction->order_type) }}
                            </td>

                            <td class="p-5">
                                @php
                                    $statusStr = strtolower($transaction->order_status);
                                    $statusColor = match($statusStr) {
                                        'pending' => 'bg-yellow-50 text-yellow-600',
                                        'cooking' => 'bg-orange-50 text-orange-500',
                                        'ready' => 'bg-green-50 text-green-500',
                                        'paid' => 'bg-orange-100 text-orange-600', // Sesuai warna paid di Figma
                                        'completed' => 'bg-gray-100 text-gray-500',
                                        'cancelled' => 'bg-red-50 text-red-500',
                                        default => 'bg-gray-50 text-gray-500'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold capitalize {{ $statusColor }}">
                                    {{ $transaction->order_status }}
                                </span>
                            </td>

                            <td class="p-5 text-gray-900 font-bold">
                                Rp {{ number_format($transaction->order_total, 0, ',', '.') }}
                            </td>
                            
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('cashier.transactions.show', $transaction->order_id ?? $transaction->id) }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-gray-600 text-sm font-bold hover:border-orange-500 hover:text-orange-500 transition shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-receipt text-4xl text-gray-300 block mb-3"></i>
                                No active orders found.
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