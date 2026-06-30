@extends('layouts.master')
@section('title', 'Edit Article')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.articles.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-gray-900 shadow-sm border border-gray-200">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">Edit Article</h1>
    </div>

    <!-- Jangan lupa tambahkan $article->article_id pada action -->
    <form action="{{ route('admin.articles.update', $article->article_id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @csrf
        @method('PUT') <!-- Method wajib untuk fungsi Update di Laravel -->
        
        <!-- Kolom Kiri (Input Utama) -->
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Article Title</label>
                    <input type="text" name="article_title" value="{{ old('article_title', $article->article_title) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none text-lg font-bold" required>
                    @error('article_title') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Content</label>
                    <textarea name="article_content" rows="15" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:ring-2 focus:ring-orange-500 outline-none leading-relaxed" required>{{ old('article_content', $article->article_content) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (Pengaturan) -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Publish Status</label>
                    <select name="is_published" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none" required>
                        <option value="0" {{ old('is_published', $article->is_published) == '0' ? 'selected' : '' }}>Save as Draft</option>
                        <option value="1" {{ old('is_published', $article->is_published) == '1' ? 'selected' : '' }}>Publish</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Category</label>
                    <select name="article_category" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none" required>
                        <option value="News" {{ old('article_category', $article->article_category) == 'News' ? 'selected' : '' }}>News</option>
                        <option value="Tips" {{ old('article_category', $article->article_category) == 'Tips' ? 'selected' : '' }}>Tips</option>
                        <option value="Promo" {{ old('article_category', $article->article_category) == 'Promo' ? 'selected' : '' }}>Promo</option>
                    </select>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Thumbnail Cover</label>
                    
                    <!-- Preview Gambar Lama -->
                    @if($article->thumbnail)
                        <div class="mb-4 rounded-xl overflow-hidden border border-gray-200 h-32 relative group">
                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover" alt="Current Cover">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <span class="text-white text-xs font-bold">Current Image</span>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="thumbnail" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-500">
                    <p class="text-xs text-gray-400 mt-2 font-medium">Leave blank if you don't want to change the image.</p>
                </div>

                <button type="submit" class="w-full bg-orange-500 text-white py-4 rounded-xl font-black text-lg hover:bg-orange-600 transition shadow-md">
                    Update Article
                </button>
            </div>
        </div>
    </form>
</div>
@endsection