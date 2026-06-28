<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Qitchen</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- MEMANGGIL KOMPONEN SIDEBAR -->
        @include('layouts.partials.sidebar')

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
        <!-- Navbar Atas -->
        <header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-8">
            <div class="text-gray-500 font-medium">
                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }} Workspace
            </div>
            
            <!-- Bagian Profil yang bisa diklik -->
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 cursor-pointer hover:opacity-75 transition duration-200">
                <div class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center font-bold shadow-sm overflow-hidden">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Photo" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    @endif
                </div>
                <span class="text-sm font-bold text-gray-900">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
            </a>
        </header>

            <!-- Area Konten Utama -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#F8F9FA] p-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>