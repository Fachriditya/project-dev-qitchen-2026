@extends('layouts.public')

@section('title', $article->article_title . ' | Qitchen')

@section('content')

    <!-- ================= HERO SECTION (FULL WIDTH IMAGE) ================= -->
    <div class="relative w-full h-[60vh] min-h-[450px] bg-slate-900 flex items-end">
        
        <!-- Gambar Background dengan Overlay Gelap -->
        <div class="absolute inset-0 w-full h-full">
            <img src="{{ asset('storage/' . $article->thumbnail) }}" onerror="this.src='https:images.unsplash.com/photo-1574071318508-1cdbab80d002?auto=format&fit=crop&q=80&w=1600&h=900'" alt="{{ $article->article_title }}" class="w-full h-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
        </div>
        
        <!-- Teks Judul di Atas Gambar -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-white/80 hover:text-white transition font-medium text-sm mb-8">
                &larr; Back to journal
            </a>
            
            <div class="mb-4">
                <span class="bg-white text-orange-500 font-bold text-xs uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">
                    Behind the Kitchen
                </span>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-8 max-w-4xl">
                {{ $article->article_title }}
            </h1>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white text-slate-900 rounded-full flex items-center justify-center font-bold text-sm shadow-md">
                    Q
                </div>
                <span class="text-sm font-medium text-white/80">
                    Admin Qitchen &middot; {{ $article->created_at->format('F j, Y') }} &middot; 5 min read
                </span>
            </div>
        </div>
    </div>

    <!-- ================= KONTEN UTAMA & SIDEBAR ================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 flex flex-col lg:flex-row gap-16">
        
        <!-- Kiri: Isi Artikel -->
        <article class="lg:w-2/3">
            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                {{-- Karena data seeder berupa teks biasa, kita gunakan nl2br agar enter/paragrafnya terbaca --}}
                <p class="text-lg">
                    {!! nl2br(e($article->article_content)) !!}
                </p>

                {{-- Catatan: Untuk proyek nyata, kamu bisa mengintegrasikan Text Editor (seperti CKEditor/TinyMCE) 
                     di halaman Admin, sehingga admin bisa membuat tulisan tebal (Bold) dan Heading secara otomatis. --}}
            </div>
        </article>

        <!-- Kanan: Related Articles (Sticky) -->
        <aside class="lg:w-1/3">
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/40 p-8 sticky top-10">
                <h3 class="text-xl font-extrabold text-gray-900 mb-8">Related articles</h3>
                
                <div class="space-y-6">
                    @forelse($relatedArticles as $related)
                    <a href="{{ route('articles.show', $related->article_slug) }}" class="flex items-center gap-4 group">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 bg-gray-50 border border-gray-100">
                            <img src="{{ asset('storage/' . $related->thumbnail) }}" onerror="this.src='https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&q=80&w=200&h=200'" alt="{{ $related->article_title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm group-hover:text-orange-500 transition line-clamp-2 mb-1">
                                {{ $related->article_title }}
                            </h4>
                            <p class="text-xs text-gray-400 font-medium">{{ rand(3, 8) }} min read</p>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500">Tidak ada artikel terkait.</p>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>

@endsection