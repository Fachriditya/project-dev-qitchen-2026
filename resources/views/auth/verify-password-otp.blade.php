@extends('layouts.public') 
@section('title', 'Verifikasi OTP Reset - Qitchen')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center items-center px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
        
        <div class="w-16 h-16 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="bi bi-envelope-open-fill"></i>
        </div>

        <h2 class="text-2xl font-black text-gray-900 mb-2">Cek Email Anda</h2>
        <p class="text-sm text-gray-500 mb-8">
            Kami telah mengirimkan 6 digit kode OTP ke email <br>
            <span class="font-bold text-gray-900">{{ session('reset_email') }}</span>
        </p>

        @if($errors->any())
            <div class="mb-6 px-4 py-3 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm font-bold">
                {{ $errors->first('otp') }}
            </div>
        @endif

        <form action="{{ route('password.verify.post') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <input type="text" name="otp" maxlength="6" placeholder="Masukkan 6 Digit OTP" 
                       class="w-full text-center tracking-[0.5em] text-2xl font-bold px-4 py-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition" required autocomplete="off">
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition shadow-md">
                Verifikasi & Lanjutkan
            </button>
        </form>
    </div>
</div>
@endsection