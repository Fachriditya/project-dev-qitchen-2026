@extends('layouts.master')

@section('title', 'Category Management')

@section('content')
<div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Category Management</h1>
        
        <a href="{{ route('admin.categories.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-plus-lg mr-2 text-lg"></i> Add category
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl font-bold flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <div class="p-6 border-b border-gray-100">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="relative w-full max-w-md">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8 w-20">No</th>
                        <th class="p-5">Category Name</th>
                        <th class="p-5">Items</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="p-5 pl-8 text-gray-900 font-bold">
                                {{ $categories->firstItem() + $index }}
                            </td>

                            <td class="p-5 text-gray-900 font-bold">
                                {{ $category->category_name }}
                            </td>
                            
                            <td class="p-5 text-gray-500 text-sm font-medium">
                                {{ $category->menus->count() }} items
                            </td>
                            
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.categories.edit', $category->category_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-fill text-sm"></i>
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                            <td colspan="4" class="p-8 text-center text-gray-500 font-medium">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="bi bi-inbox text-4xl text-gray-300"></i>
                                    @if(request('search'))
                                        <p>No categories found for "<strong>{{ request('search') }}</strong>".</p>
                                        <a href="{{ route('admin.categories.index') }}" class="text-orange-500 hover:underline mt-2">Clear search</a>
                                    @else
                                        <p>No categories found. Let's create your first category!</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        @if($categories->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
@endsection