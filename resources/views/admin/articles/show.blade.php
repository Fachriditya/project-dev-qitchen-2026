@extends('layouts.master')
@section('title', 'Preview Article')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Action Bar Atas -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.articles.index') }}" class="flex items-center text-gray-500 font-bold hover:text-orange-500 transition">
            <i class="bi bi-arrow-left mr-2"></i> Back to Articles
        </a>
        <a href="{{ route('admin.articles.edit', $article->article_id) }}" class="bg-blue-50 text-blue-600 px-5 py-2 rounded-full font-bold border border-blue-100 hover:bg-blue-100 transition">
            <i class="bi bi-pencil-fill mr-2"></i> Edit Article
        </a>
    </div>

    <!-- KANVAS PREVIEW ARTIKEL (Dibikin tengah dan max-width kaya blog beneran) -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-[0_2px_20px_-3px_rgba(6,81,237,0.08)] overflow-hidden border border-gray-100">
        
        <!-- Thumbnail -->
        <div class="w-full h-80 bg-gray-100 relative">
            @if($article->thumbnail)
                <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover" alt="Thumbnail">
            @else
                <div class="flex items-center justify-center w-full h-full text-gray-400 font-bold">No Image Provided</div>
            @endif
            
            <!-- Badge Category & Status -->
            <div class="absolute top-6 left-6 flex gap-2">
                <span class="bg-orange-500 text-white px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-md">
                    {{ $article->article_category }}
                </span>
                @if(!$article->is_published)
                    <span class="bg-gray-800/80 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider shadow-md">
                        Draft Mode
                    </span>
                @endif
            </div>
        </div>

        <!-- Body Artikel -->
        <div class="p-10 md:p-14">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-6">
                {{ $article->article_title }}
            </h1>
            
            <!-- Meta Info (Penulis & Tanggal) -->
            <div class="flex items-center gap-4 mb-10 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-bold text-xl">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <p class="text-gray-900 font-bold">{{ $article->admin->name ?? 'Admin Qitchen' }}</p>
                    <p class="text-sm text-gray-500 font-medium">
                        {{ $article->is_published && $article->published_at ? $article->published_at->format('F d, Y') : 'Not published yet' }}
                    </p>
                </div>
            </div>

            <!-- Konten (Menggunakan nl2br agar enter (baris baru) dari textarea terbaca) -->
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($article->article_content)) !!}
            </div>
        </div>
    </div>
</div>
@endsection