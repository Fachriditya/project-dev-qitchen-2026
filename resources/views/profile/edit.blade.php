@extends('layouts.master')

@section('title', 'My Profile')

@section('content')
<style>
    aside { display: none !important; }
</style>

<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <div class="max-w-4xl mx-auto">
        
        <!-- Tombol Back -->
        <div class="mb-6">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-gray-500 hover:text-orange-500 font-bold transition">
                <i class="bi bi-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>

        <!-- ================= KARTU HEADER PROFIL ================= -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] mb-8 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- Lingkaran Avatar Hitam -->
                <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center text-white text-3xl font-bold overflow-hidden shadow-sm">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Photo" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-black text-gray-900">{{ Auth::user()->name }}</h2>
                        <!-- Badge Role Oranye -->
                        <span class="bg-orange-50 text-orange-500 px-3 py-1 rounded-full text-xs font-bold capitalize border border-orange-100">
                            {{ str_replace('_', ' ', Auth::user()->role) }} member
                        </span>
                    </div>
                    <p class="text-gray-500 font-medium mt-1">{{ Auth::user()->email }} &middot; Member since {{ Auth::user()->created_at->format('Y') }}</p>
                </div>
            </div>
        </div>

        <!-- ================= FORM BLOCKS ================= -->
        <div class="space-y-8">
            
            <!-- Blok 1: Profile Information -->
            <div class="p-8 bg-white border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] rounded-3xl">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Blok 2: Change Password -->
            <div class="p-8 bg-white border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] rounded-3xl">
                @include('profile.partials.update-password-form')
            </div>

        </div>
    </div>
</div>
@endsection