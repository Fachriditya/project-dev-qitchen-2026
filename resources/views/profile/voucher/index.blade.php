@extends('layouts.public')

@section('title', 'My Vouchers - Qitchen')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-5 w-full md:w-auto">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-sm shrink-0 border border-gray-200">
            @else
                <div class="w-16 h-16 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-2xl shadow-sm uppercase shrink-0">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-bold px-2.5 py-1 rounded-full">Member</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Auth::user()->email }} &bull; Member since {{ Auth::user()->created_at->format('Y') }}
                </p>
            </div>
        </div>

        <div class="flex items-center md:pl-8 md:border-l md:border-gray-100 w-full md:w-auto">
            <div class="text-center w-full md:w-auto">
                <div class="text-3xl font-black text-orange-500">{{ $totalOrders ?? 0 }}</div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">Total Orders</div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-64 shrink-0 flex flex-col gap-2">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-person text-lg"></i> Profile
            </a>
            
            <a href="{{ route('profile.history') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-receipt text-lg"></i> Order History
            </a>
            
            <a href="{{ route('profile.vouchers') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold bg-orange-500 text-white shadow-md transition">
                <i class="bi bi-ticket-perforated text-lg"></i> My Vouchers
            </a>
            
            <a href="{{ route('profile.referral') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-gift text-lg"></i> Referral
            </a>

            <div class="w-full h-px bg-gray-100 my-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition">
                    <i class="bi bi-box-arrow-right text-lg"></i> Logout
                </button>
            </form>
        </div>

        <div class="w-full flex-grow">
            
            @if($userVouchers->isEmpty())
                <div class="w-full bg-white border border-gray-100 rounded-2xl p-10 shadow-sm flex flex-col items-center justify-center text-center">
                    <i class="bi bi-ticket-perforated text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Yah, belum ada voucher nih!</h3>
                    <p class="text-gray-500 mb-6">Terus bertransaksi atau bagikan kode referralmu untuk mendapatkan voucher menarik.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($userVouchers as $userVoucher)
                        @php
                            $voucher = $userVoucher->voucher;
                        @endphp
                        
                        <div class="relative bg-white border border-gray-100 rounded-2xl p-6 shadow-sm overflow-hidden flex flex-col justify-between min-h-[160px] hover:shadow-md transition">
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-orange-500"></div>
                            
                            <div class="pl-2 flex flex-col h-full">
                                <div class="flex justify-between items-start mb-2 gap-4">
                                    <h3 class="text-lg font-black text-gray-900 leading-tight">{{ $voucher->voucher_name }}</h3>
                                    <span class="bg-green-50 text-green-600 border border-green-100 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shrink-0">
                                        Active
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-500 mb-6">
                                    {{ $voucher->voucher_description ?? 'Min. spend Rp ' . number_format($voucher->min_order, 0, ',', '.') }}
                                </p>
                                
                                <div class="flex justify-between items-end mt-auto gap-2">
                                    <div class="border border-dashed border-gray-300 bg-gray-50 px-4 py-2 rounded-xl inline-block cursor-copy hover:bg-gray-100 transition" onclick="navigator.clipboard.writeText('{{ $voucher->voucher_code }}'); alert('Kode voucher disalin!');">
                                        <span class="font-mono font-bold text-gray-800 tracking-wider">{{ $voucher->voucher_code }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400 font-medium text-right">
                                        Exp {{ $voucher->expiration_date->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
@endsection