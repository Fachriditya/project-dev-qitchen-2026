@extends('layouts.public') 
@section('title', 'Lupa Password - Qitchen')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center items-center px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
        
        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <h2 class="text-2xl font-black text-gray-900 mb-2">Lupa Password?</h2>
        <p class="text-sm text-gray-500 mb-8">
            Masukkan email yang terdaftar pada akun Anda. Kami akan mengirimkan 6 digit kode OTP untuk mereset password.
        </p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div class="mb-6 text-left">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" 
                       class="w-full px-4 py-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition text-gray-700" required autocomplete="email" autofocus>
                @error('email')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition shadow-md">
                Kirim Kode OTP
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-orange-500 transition">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Login
            </a>
        </div>
    </div>
</div>
@endsection