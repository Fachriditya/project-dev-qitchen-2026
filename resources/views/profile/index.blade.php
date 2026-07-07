@extends('layouts.public')

@section('title', 'My Profile - Qitchen')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- HEADER CARD -->
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
        
        <!-- Kiri: Info User -->
        <div class="flex items-center gap-5 w-full md:w-auto">
            <!-- Avatar -->
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-16 h-16 rounded-full object-cover shadow-sm shrink-0 border border-gray-200">
            @else
                <div class="w-16 h-16 bg-slate-800 text-white rounded-full flex items-center justify-center font-bold text-2xl shadow-sm uppercase shrink-0">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
            @endif
            
            <!-- Detail -->
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold text-gray-900">{{ Auth::user()->name }}</h2>
                    <!-- Badge Member -->
                    <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-bold px-2.5 py-1 rounded-full">
                        Member
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    {{ Auth::user()->email }} &bull; Member since {{ Auth::user()->created_at->format('Y') }}
                </p>
            </div>
        </div>

        <!-- Kanan: Total Orders (Sudah dihias, Poin dihapus) -->
        <div class="flex items-center md:pl-8 md:border-l md:border-gray-100 w-full md:w-auto">
            <div class="text-center w-full md:w-auto">
                <div class="text-3xl font-black text-orange-500">
                    {{-- Nanti ini kita ganti jadi variabel dinamis dari database --}}
                    0 
                </div>
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">
                    Total Orders
                </div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="px-5 py-4 bg-green-50 text-green-700 border border-green-200 rounded-2xl text-sm font-bold shadow-sm flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if($errors->any())
            <div class="px-5 py-4 bg-red-50 text-red-600 border border-red-200 rounded-2xl text-sm shadow-sm flex flex-col gap-2">
                <div class="flex items-center gap-3 font-bold">
                    <i class="bi bi-exclamation-triangle-fill text-xl"></i>
                    <span>Oops! Ada yang salah dengan inputanmu:</span>
                </div>
                <ul class="list-disc list-inside ml-8 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- MAIN CONTENT AREA -->
    <div class="flex flex-col md:flex-row gap-8">
        
        <!-- KOLOM KIRI: SIDEBAR -->
        <div class="w-full md:w-64 shrink-0 flex flex-col gap-2">
            
            <!-- Menu Aktif (Profile) -->
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold bg-orange-500 text-white shadow-md transition">
                <i class="bi bi-person text-lg"></i> Profile
            </a>
            
            <!-- Menu Lain -->
            <a href="{{ route('profile.history') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-receipt text-lg"></i> Order History
            </a>
            
            <a href="{{ route('profile.vouchers') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-ticket-perforated text-lg"></i> My Vouchers
            </a>
            
            <a href="{{ route('profile.referral') }}" class="flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-medium text-gray-600 hover:bg-orange-50 hover:text-orange-500 transition">
                <i class="bi bi-gift text-lg"></i> Referral
            </a>

            <!-- Pemisah & Logout -->
            <div class="w-full h-px bg-gray-100 my-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition">
                    <i class="bi bi-box-arrow-right text-lg"></i> Logout
                </button>
            </form>
        </div>

        <!-- KOLOM KANAN: FORM -->
        <div class="w-full flex-grow flex flex-col gap-6">
            
            <!-- Card 1: Profile Information -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Profile information</h3>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <input type="file" name="profile_picture" accept="image/png, image/jpeg, image/jpg" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG (Maks 2MB)</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full name</label>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="+62" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default address</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkapmu..." class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Change Password -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 md:p-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Change Password</h3>

                <form action="{{ route('profile.password') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" placeholder="********" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="password" placeholder="********" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" placeholder="********" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-100 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection