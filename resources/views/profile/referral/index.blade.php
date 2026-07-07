@extends('layouts.public')

@section('title', 'Referral - Qitchen')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- HEADER CARD -->
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

    <!-- MAIN CONTENT AREA -->
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- KOLOM 1: SIDEBAR -->
        <div class="w-full lg:w-64 shrink-0 flex flex-col gap-2">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-person text-lg"></i> Profile
            </a>
            
            <a href="{{ route('profile.history') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-receipt text-lg"></i> Order History
            </a>
            
            <a href="{{ route('profile.vouchers') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-ticket-perforated text-lg"></i> My Vouchers
            </a>
            
            <!-- Referral (AKTIF - Warna Oranye) -->
            <a href="{{ route('profile.referral') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold bg-orange-500 text-white shadow-md transition">
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

        <!-- KOLOM 2: KONTEN REFERRAL -->
        <div class="w-full flex-grow grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            
            <!-- CARD 1: INVITE FRIENDS -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center text-2xl mb-6">
                    <i class="bi bi-gift-fill"></i>
                </div>
                
                <h3 class="text-xl font-bold text-gray-900 mb-3">Invite friends, earn rewards</h3>
                <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                    Berikan temanmu diskon spesial untuk pesanan pertama mereka. Kamu juga akan mendapatkan voucher gratis setelah pesanan mereka selesai!
                </p>

                <div class="space-y-3">
                    <label class="block text-sm font-bold text-gray-900">Your referral code</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-grow border border-dashed border-orange-300 bg-orange-50/50 px-5 py-3.5 rounded-xl text-center">
                            <!-- Menampilkan kode unik milik user -->
                            <span class="font-mono font-bold text-orange-600 tracking-widest text-lg" id="refCode">
                                {{ $user->referral_code ?? 'QITCHEN-' . strtoupper(substr($user->name, 0, 4)) . '-' . $user->user_id }}
                            </span>
                        </div>
                        <button onclick="copyReferral()" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold px-5 py-3.5 rounded-xl transition flex items-center gap-2 shadow-sm shrink-0">
                            <i class="bi bi-copy"></i> Copy
                        </button>
                    </div>
                </div>
            </div>

            <!-- CARD 2: STATISTIK -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Your referral stats</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <!-- Stat 1: Joined -->
                    <div class="bg-gray-50 rounded-2xl p-5 text-center">
                        <div class="text-3xl font-black text-orange-500 mb-1">{{ $totalJoined }}</div>
                        <div class="text-xs font-medium text-gray-500">Friends Joined</div>
                    </div>
                    
                    <!-- Stat 2: Vouchers Earned -->
                    <div class="bg-gray-50 rounded-2xl p-5 text-center">
                        <div class="text-3xl font-black text-orange-500 mb-1">{{ $vouchersEarned }}</div>
                        <div class="text-xs font-medium text-gray-500">Vouchers Earned</div>
                    </div>
                    
                    <!-- Stat 3: Pending -->
                    <div class="bg-gray-50 rounded-2xl p-5 text-center">
                        <div class="text-3xl font-black text-orange-500 mb-1">{{ $pendingRewards }}</div>
                        <div class="text-xs font-medium text-gray-500">Pending Orders</div>
                    </div>

                    <!-- Stat 4: Success Rate -->
                    <div class="bg-gray-50 rounded-2xl p-5 text-center">
                        <div class="text-3xl font-black text-orange-500 mb-1">{{ $successRate }}%</div>
                        <div class="text-xs font-medium text-gray-500">Success Rate</div>
                    </div>
                </div>

                <!-- Footer Pesan -->
                <div class="mt-6 px-4 py-3 bg-orange-50 border border-orange-100 rounded-xl flex items-start gap-3">
                    <i class="bi bi-check2 text-orange-500 text-lg"></i>
                    <p class="text-sm font-medium text-orange-800">
                        Pastikan temanmu menyelesaikan pesanan pertama agar status "Pending" berubah menjadi Voucher!
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script Copy to Clipboard -->
<script>
    function copyReferral() {
        var copyText = document.getElementById("refCode").innerText;
        navigator.clipboard.writeText(copyText).then(function() {
            alert("Kode referral berhasil disalin: " + copyText);
        });
    }
</script>
@endsection