@extends('layouts.public')

@section('title', 'The Qitchen Journal | Qitchen')

@section('content')

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        
        <!-- ================= HEADER ARTIKEL ================= -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">The Qitchen Journal</h1>
            <p class="mt-4 text-gray-500 text-lg">Recipes, stories and the people behind your favourite meals.</p>
            
            <!-- Search Bar -->
            <div class="mt-8 relative max-w-lg mx-auto">
                <form action="{{ route('articles.index') }}" method="GET">
                    {{-- Bawa serta filter kategori jika sedang aktif --}}
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles..." class="w-full bg-gray-50 border-none rounded-full px-6 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 shadow-sm transition">
                    
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-orange-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>

            <!-- Category Pills (Sekarang Dinamis) -->
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('articles.index', ['search' => request('search')]) }}" 
                   class="{{ !request('category') ? 'bg-orange-500 text-white shadow-orange-200' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-500 hover:text-orange-500' }} px-5 py-2 rounded-full text-sm font-bold shadow-md transition">
                   All
                </a>
                
                <a href="{{ route('articles.index', ['category' => 'News', 'search' => request('search')]) }}" 
                   class="{{ request('category') == 'News' ? 'bg-orange-500 text-white shadow-orange-200' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-500 hover:text-orange-500' }} px-5 py-2 rounded-full text-sm font-bold shadow-md transition">
                   News
                </a>
                
                <a href="{{ route('articles.index', ['category' => 'Tips', 'search' => request('search')]) }}" 
                   class="{{ request('category') == 'Tips' ? 'bg-orange-500 text-white shadow-orange-200' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-500 hover:text-orange-500' }} px-5 py-2 rounded-full text-sm font-bold shadow-md transition">
                   Tips
                </a>
                
                <a href="{{ route('articles.index', ['category' => 'Promo', 'search' => request('search')]) }}" 
                   class="{{ request('category') == 'Promo' ? 'bg-orange-500 text-white shadow-orange-200' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-500 hover:text-orange-500' }} px-5 py-2 rounded-full text-sm font-bold shadow-md transition">
                   Promo
                </a>
            </div>
        </div>

        <!-- ================= ARTIKEL UTAMA (FEATURED) ================= -->
        @if($featuredArticle)
        <div class="mb-12 cursor-pointer group">
            <a href="{{ route('articles.show', $featuredArticle->article_slug) }}" class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-200/40 p-4 md:p-6 flex flex-col md:flex-row gap-8 lg:gap-12 items-center hover:shadow-2xl transition duration-300 block">
                <!-- Gambar -->
                <div class="w-full md:w-1/2 lg:w-3/5 h-64 md:h-[22rem] relative rounded-[2rem] overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . $featuredArticle->thumbnail) }}" onerror="this.src='https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&q=80&w=1000'" alt="{{ $featuredArticle->article_title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                </div>
                <!-- Konten Teks -->
                <div class="w-full md:w-1/2 lg:w-2/5 px-4 md:px-0 md:pr-8 py-4">
                    <!-- Kategori Dinamis -->
                    <span class="text-orange-500 text-xs font-bold uppercase tracking-widest bg-orange-50 px-3 py-1 rounded-full">{{ $featuredArticle->article_category }}</span>
                    
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mt-4 mb-4 leading-tight group-hover:text-orange-500 transition line-clamp-3">
                        {{ $featuredArticle->article_title }}
                    </h2>
                    <p class="text-gray-500 mb-8 leading-relaxed line-clamp-3">
                        {{ Str::limit($featuredArticle->article_content, 200) }}
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                            Q
                        </div>
                        <span class="text-xs font-bold text-gray-500">Admin Qitchen · {{ $featuredArticle->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </a>
        </div>
        @endif

        <!-- ================= GRID ARTIKEL BAWAH ================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @forelse ($articles as $article)
            <!-- Card Artikel Looping -->
            <article class="bg-white rounded-[2rem] border border-gray-100 shadow-lg shadow-gray-200/40 overflow-hidden p-4 group hover:shadow-2xl transition duration-300">
                <a href="{{ route('articles.show', $article->article_slug) }}" class="flex flex-col h-full">
                    
                    <!-- Gambar -->
                    <div class="w-full h-48 md:h-52 rounded-2xl overflow-hidden mb-5 relative bg-gray-100 shrink-0">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&q=80&w=600'" alt="{{ $article->article_title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </div>
                    
                    <!-- Teks di bawah -->
                    <div class="px-2 pb-2 flex flex-col flex-1">
                        <!-- Kategori Dinamis -->
                        <span class="text-orange-500 text-[10px] font-bold uppercase tracking-widest bg-orange-50 px-3 py-1 rounded-full w-max mb-3">{{ $article->article_category }}</span>
                        
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 group-hover:text-orange-500 transition line-clamp-2">
                            {{ $article->article_title }}
                        </h3>
                        
                        <p class="text-gray-500 text-sm mb-4 flex-1 line-clamp-3">
                            {{ Str::limit($article->article_content, 120) }}
                        </p>
                        
                        <p class="text-xs text-gray-400 font-bold mt-auto">{{ $article->created_at->format('d M Y') }}</p>
                    </div>
                </a>
            </article>
            @empty
            <div class="col-span-3 text-center py-20">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No articles found</h3>
                <p class="text-gray-500">We couldn't find any articles matching your search.</p>
                <a href="{{ route('articles.index') }}" class="inline-block mt-6 text-orange-500 font-bold hover:underline">Clear Filters</a>
            </div>
            @endforelse

        </div>

        <!-- ================= PAGINATION ================= -->
        <div class="mt-20 flex justify-center pagination-qitchen">
            {{ $articles->links() }}
        </div>

    </section>

@endsection