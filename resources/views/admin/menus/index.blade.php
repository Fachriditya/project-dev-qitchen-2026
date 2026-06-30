@extends('layouts.master')

@section('title', 'Menu Management')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Menu Management</h1>
            <p class="text-gray-500 font-medium mt-1">Add, edit and organise dishes</p>
        </div>
        
        <a href="{{ route('admin.menus.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-plus-lg mr-2 text-lg"></i> Add menu item
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl font-bold flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Area Search & Filter -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Form Pencarian -->
            <form action="{{ route('admin.menus.index') }}" method="GET" class="relative w-full max-w-md">
                <!-- PENTING: Bawa status filter saat ini jika user melakukan pencarian -->
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search menus..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>

            <!-- Filter Buttons (All, Available, Unavailable) -->
            <div class="flex items-center bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm shrink-0">
                <!-- Tombol All -->
                <a href="{{ route('admin.menus.index', ['search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    All
                </a>
                
                <!-- Tombol Available -->
                <a href="{{ route('admin.menus.index', ['status' => 'available', 'search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'available' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    Available
                </a>
                
                <!-- Tombol Unavailable -->
                <a href="{{ route('admin.menus.index', ['status' => 'unavailable', 'search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ request('status') == 'unavailable' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    Unavailable
                </a>
            </div>
        </div>

        <!-- Area Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8 w-16">No</th>
                        <th class="p-5">Item</th>
                        <th class="p-5">Category</th>
                        <th class="p-5">Price</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @forelse($menus as $index => $menu)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="p-5 pl-8 text-gray-900 font-bold">
                                {{ $menus->firstItem() + $index }}
                            </td>

                            <td class="p-5">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $menu->menu_image) }}" alt="{{ $menu->menu_name }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-100">
                                    <span class="text-gray-900 font-bold">{{ $menu->menu_name }}</span>
                                </div>
                            </td>
                            
                            <td class="p-5 text-gray-500 font-medium">
                                {{ $menu->category->category_name ?? 'Uncategorized' }}
                            </td>
                            
                            <td class="p-5 text-gray-900 font-bold">
                                Rp {{ number_format($menu->menu_price, 0, ',', '.') }}
                            </td>

                            <!-- UBAH DI SINI: Badge Status -->
                            <td class="p-5">
                                @if($menu->is_available)
                                    <span class="bg-green-50 text-green-500 px-4 py-1.5 rounded-full text-xs font-bold border border-green-100">Available</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-4 py-1.5 rounded-full text-xs font-bold border border-gray-200">Unavailable</span>
                                @endif
                            </td>
                            
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.menus.edit', $menu->menu_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-fill text-sm"></i>
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu->menu_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition shadow-sm" title="Delete">
                                        <i class="bi bi-trash3-fill text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-inbox text-4xl text-gray-300 block mb-3"></i>
                                No menus found.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        @if($menus->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $menus->links() }}
            </div>
        @endif
        </div>
    
    </div>
</div>
@endsection