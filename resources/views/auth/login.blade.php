<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Log in | {{ config('app.name', 'Qitchen') }}</title>

    <!-- Memanggil Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-white">
    
    <div class="min-h-screen flex">
        
        <!-- ================= KIRI: WADAH FORM ================= -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 md:px-24 xl:px-32 relative">
            
            <!-- Tombol Kembali ke Home -->
            <a href="{{ route('home') }}" class="absolute top-8 left-8 sm:left-12 font-bold text-sm text-gray-400 hover:text-orange-500 transition flex items-center gap-2">
                &larr; Back to Home
            </a>

            <!-- Kaca Kosong (Slot) tempat Login disuntikkan -->
            <div class="w-full max-w-sm mx-auto">
                
                <!-- Notifikasi jika ada pesan sukses  -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Logo & Sapaan -->
                <div class="flex items-center gap-2 mb-8 mt-12 lg:mt-0">
                    <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md">Q</div>
                    <span class="font-bold text-2xl tracking-tight text-gray-900">Qitchen</span>
                </div>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Welcome back</h2>
                <p class="text-gray-500 text-sm mb-10">Sign in to continue your order.</p>

                <!-- FORM LOGIN -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-900 mb-2">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="jane.doe@example.com" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-900 mb-2">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="password" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-3.5 text-gray-700 focus:ring-2 focus:ring-orange-500 transition shadow-sm">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Forgot Password Link -->
                    <div class="flex justify-end">
                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-orange-500 hover:text-orange-600 transition" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3.5 px-8 rounded-full transition shadow-lg shadow-orange-200">
                        Sign In
                    </button>
                </form>

                <!-- Pembatas "OR" -->
                <div class="my-8 flex items-center">
                    <div class="flex-grow border-t border-gray-100"></div>
                    <span class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">OR</span>
                    <div class="flex-grow border-t border-gray-100"></div>
                </div>

                <!-- Tombol Google Auth (Sementara Dummy UI) -->
                <button type="button" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold py-3.5 px-8 rounded-full transition shadow-sm flex items-center justify-center gap-3">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Continue with Google
                </button>

                <!-- Link Pendaftaran -->
                <p class="mt-10 text-center text-sm text-gray-500 pb-12 lg:pb-0">
                    New to Qitchen? 
                    <a href="{{ route('register') }}" class="font-bold text-orange-500 hover:text-orange-600 transition">Create an account</a>
                </p>

            </div>
        </div>

        <!-- ================= KANAN: GAMBAR BANNER ================= -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-900 items-end">
            <!-- Gambar Burger Background -->
            <img src="https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&q=80&w=1200&h=1600" alt="Delicious Burger" class="absolute inset-0 w-full h-full object-cover opacity-70">
            
            <!-- Gradien hitam dari bawah agar teks putih terbaca -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
            
            <!-- Teks Slogan -->
            <div class="relative z-10 p-16 xl:p-24 max-w-2xl">
                <h2 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-4">Skip the queue.<br>Order in seconds.</h2>
                <p class="text-white/80 text-lg leading-relaxed">Join 40,000+ diners ordering ahead, earning rewards and tracking their food in real time.</p>
            </div>
        </div>

    </div>

</body>
</html>