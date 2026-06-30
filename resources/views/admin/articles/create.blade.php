@extends('layouts.master')
@section('title', 'Write Article')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.articles.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-gray-900 shadow-sm border border-gray-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">Write New Article</h1>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @csrf
        
        <!-- Kolom Kiri (Input Utama) -->
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Article Title</label>
                    <input type="text" name="article_title" value="{{ old('article_title') }}" placeholder="Enter a catchy title..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none text-lg font-bold" required>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Content</label>
                    <textarea name="article_content" rows="15" placeholder="Write your amazing story here..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-orange-500 outline-none leading-relaxed" required>{{ old('article_content') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (Pengaturan) -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Publish Status</label>
                    <select name="is_published" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none" required>
                        <option value="0">Save as Draft</option>
                        <option value="1">Publish Immediately</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Category</label>
                    <select name="article_category" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none" required>
                        <option value="News">News</option>
                        <option value="Tips">Tips</option>
                        <option value="Promo">Promo</option>
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Thumbnail Cover</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-500">
                </div>

                <button type="submit" class="w-full bg-orange-500 text-white py-4 rounded-xl font-black text-lg hover:bg-orange-600 transition shadow-md">
                    Save Article
                </button>
            </div>
        </div>
    </form>
</div>
@endsection