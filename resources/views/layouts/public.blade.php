<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', 'Qitchen - Great food, zero waiting in line.')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="font-sans text-gray-800 antialiased bg-white flex flex-col min-h-screen">

    @include('layouts.partials.navbar')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

</body>
</html>