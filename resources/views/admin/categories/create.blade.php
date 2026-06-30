@extends('layouts.master')

@section('title', 'Add New Category')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header dengan Tombol Back -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm" title="Back to Categories">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Add New Category</h1>
    </div>

    <!-- Card Form Utama -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] max-w-2xl">
        
        <form action="{{ route('admin.categories.store') }}" method="POST">
            <!-- Wajib ada @csrf agar tidak kena error 419 Page Expired -->
            @csrf
            
            <div class="mb-6">
                <label for="category_name" class="block text-sm font-bold text-gray-900 mb-2">
                    Category Name <span class="text-red-500">*</span>
                </label>
                
                <!-- Perhatikan fitur 'old' untuk menyimpan ketikan sebelumnya jika form error -->
                <input type="text" id="category_name" name="category_name" value="{{ old('category_name') }}" placeholder="e.g., Signature, Burgers, Coffee" 
                    class="w-full bg-gray-50 border @error('category_name') border-red-500 @else border-gray-200 @enderror text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent transition outline-none shadow-sm" 
                    required autofocus>
                
                <!-- Pesan Error Validasi -->
                @error('category_name')
                    <p class="text-red-500 text-sm font-bold mt-2 flex items-center">
                        <i class="bi bi-exclamation-circle-fill mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-3 mt-8 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-full font-bold text-gray-500 hover:bg-gray-100 hover:text-gray-900 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
                    <i class="bi bi-floppy-fill mr-2"></i> Save Category
                </button>
            </div>
        </form>

    </div>
</div>
@endsection