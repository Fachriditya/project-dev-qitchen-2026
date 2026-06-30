@extends('layouts.master')
@section('title', 'Article Management')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Articles</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your blog and news content</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-plus-lg mr-2 text-lg"></i> Write Article
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-600 rounded-2xl font-bold flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Filter Bar -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <form action="{{ route('admin.articles.index') }}" method="GET" class="relative w-full max-w-md">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search article title..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:outline-none" />
            </form>

            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm">
                <a href="{{ route('admin.articles.index', ['search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium {{ !request('status') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500' }}">All</a>
                <a href="{{ route('admin.articles.index', ['status' => 'published', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium {{ request('status') == 'published' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500' }}">Published</a>
                <a href="{{ route('admin.articles.index', ['status' => 'draft', 'search' => request('search')]) }}" class="px-5 py-1.5 rounded-full text-sm font-medium {{ request('status') == 'draft' ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500' }}">Drafts</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8 w-16">No</th>
                        <th class="p-5">Title</th>
                        <th class="p-5">Category</th>
                        <th class="p-5">Author</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($articles as $index => $article)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-5 pl-8 font-bold text-gray-900">{{ $articles->firstItem() + $index }}</td>
                            <td class="p-5">
                                <p class="font-bold text-gray-900 truncate max-w-xs">{{ $article->article_title }}</p>
                                <p class="text-xs text-gray-400 mt-1">/{{ $article->article_slug }}</p>
                            </td>
                            <td class="p-5 font-bold text-orange-500">{{ $article->article_category }}</td>
                            <td class="p-5 font-medium text-gray-500">{{ $article->admin->name ?? 'Unknown' }}</td>
                            <td class="p-5">
                                @if($article->is_published)
                                    <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-xs font-bold">Published</span>
                                @else
                                    <span class="bg-gray-100 text-gray-500 border border-gray-200 px-3 py-1 rounded-full text-xs font-bold">Draft</span>
                                @endif
                            </td>
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.articles.show', $article->article_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:text-green-600 hover:bg-green-50 shadow-sm" title="Preview"><i class="bi bi-eye-fill"></i></a>
                                <a href="{{ route('admin.articles.edit', $article->article_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:text-blue-600 hover:bg-blue-50 shadow-sm" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                <form action="{{ route('admin.articles.destroy', $article->article_id) }}" method="POST" onsubmit="return confirm('Delete this article?');">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:text-red-600 hover:bg-red-50 shadow-sm" title="Delete"><i class="bi bi-trash3-fill"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-8 text-center text-gray-400">No articles found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($articles->hasPages()) <div class="p-5 border-t border-gray-100">{{ $articles->links() }}</div> @endif
    </div>
</div>
@endsection