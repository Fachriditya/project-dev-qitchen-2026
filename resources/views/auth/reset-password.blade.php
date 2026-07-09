@extends('layouts.public') 
@section('title', 'Buat Password Baru - Qitchen')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center items-center px-4">
    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
        
        <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-6">
            <i class="bi bi-key-fill"></i> </div>

        <h2 class="text-2xl font-black text-gray-900 mb-2">Buat Password Baru</h2>
        <p class="text-sm text-gray-500 mb-8">
            OTP valid! Silakan masukkan password baru Anda yang kuat dan mudah diingat.
        </p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            
            <div class="mb-5 text-left">
                <input type="password" name="password" placeholder="Password Baru" 
                       class="w-full px-4 py-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition text-gray-700" required>
                @error('password')
                    <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 text-left">
                <input type="password" name="password_confirmation" placeholder="Ulangi Password Baru" 
                       class="w-full px-4 py-4 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 transition text-gray-700" required>
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl transition shadow-md">
                Simpan & Login
            </button>
        </form>
    </div>
</div>
@endsection