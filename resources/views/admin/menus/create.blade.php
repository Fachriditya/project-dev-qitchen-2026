@extends('layouts.master')
@section('title', 'Add New Menu')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.menus.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Add New Menu</h1>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] max-w-3xl">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Menu -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Menu Name <span class="text-red-500">*</span></label>
                    <input type="text" name="menu_name" value="{{ old('menu_name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Price (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="menu_price" value="{{ old('menu_price') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>

                <!-- Status Ketersediaan -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="is_available" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="1" {{ old('is_available', $menu->is_available ?? '') == '1' ? 'selected' : '' }}>Available</option>
                        <option value="0" {{ old('is_available', $menu->is_available ?? '') == '0' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-900 mb-2">Description</label>
                <textarea name="menu_description" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">{{ old('menu_description') }}</textarea>
            </div>

            <!-- Upload Foto -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-900 mb-2">Menu Image <span class="text-red-500">*</span></label>
                <input type="file" name="menu_image" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-500 hover:file:bg-orange-100 transition" required>
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.menus.index') }}" class="px-6 py-3 rounded-full font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</a>
                <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                    Save Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection