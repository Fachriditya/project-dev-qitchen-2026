@extends('layouts.master')
@section('title', 'Voucher Management')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Voucher Management</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your promo codes and discounts</p>
        </div>
        
        <a href="{{ route('admin.vouchers.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-plus-lg mr-2 text-lg"></i> Create voucher
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl font-bold flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Search & Filter Area -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <form action="{{ route('admin.vouchers.index') }}" method="GET" class="relative w-full max-w-md">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>

            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm shrink-0">
                <a href="{{ route('admin.vouchers.index', ['search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">All</a>
                <a href="{{ route('admin.vouchers.index', ['status' => 'active', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'active' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">Active</a>
                <a href="{{ route('admin.vouchers.index', ['status' => 'nonactive', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'nonactive' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">Nonactive</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8">No</th>
                        <th class="p-5">Code</th>
                        <th class="p-5">Name</th>
                        <th class="p-5">Type</th>
                        <th class="p-5">Value</th>
                        <th class="p-5">Expiry Date</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vouchers as $index => $voucher)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="p-5 pl-8 text-gray-900 font-bold">{{ $vouchers->firstItem() + $index }}</td>
                            <td class="p-5 font-mono text-gray-900 font-bold">{{ $voucher->voucher_code }}</td>
                            <td class="p-5 text-gray-900 font-bold">{{ $voucher->voucher_name }}</td>
                            <td class="p-5 text-gray-500 font-medium capitalize">{{ str_replace('_', ' ', $voucher->voucher_type) }}</td>
                            <td class="p-5 text-gray-900 font-bold">
                                {{ $voucher->voucher_type === 'percentage' ? $voucher->voucher_value . '%' : 'Rp ' . number_format($voucher->voucher_value, 0, ',', '.') }}
                            </td>
                            <td class="p-5 text-gray-500 font-medium">{{ $voucher->expiration_date ? $voucher->expiration_date->format('d M Y H:i') : 'No Expiry' }}</td>
                            <td class="p-5">
                                @if($voucher->is_active && (!$voucher->expiration_date || $voucher->expiration_date > now()))
                                    <span class="bg-green-50 text-green-500 px-3 py-1 rounded-full text-xs font-bold border border-green-100">Active</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold border border-gray-200">Nonactive</span>
                                @endif
                            </td>
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <!-- TOMBOL SHOW (BARU) -->
                                <a href="{{ route('admin.vouchers.show', $voucher->voucher_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-green-500 hover:bg-green-50 hover:text-green-600 transition shadow-sm" title="View Details"><i class="bi bi-eye-fill text-sm"></i></a>
                                
                                <a href="{{ route('admin.vouchers.edit', $voucher->voucher_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition shadow-sm" title="Edit"><i class="bi bi-pencil-fill text-sm"></i></a>
                                
                                <form action="{{ route('admin.vouchers.destroy', $voucher->voucher_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this voucher?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition shadow-sm" title="Delete"><i class="bi bi-trash3-fill text-sm"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-ticket-perforated text-4xl text-gray-300 block mb-3"></i>
                                No vouchers found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($vouchers->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $vouchers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection