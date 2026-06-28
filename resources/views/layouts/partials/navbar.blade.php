<nav class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">

    <a href="{{ route('home') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xl">Q</div>
        <span class="font-bold text-xl tracking-tight">Qitchen</span>
    </a>

    <div class="hidden md:flex space-x-8 text-sm font-medium">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Home
        </a>
        <a href="#" class="text-gray-600 hover:text-orange-500 px-4 py-2 transition">
            Menu
        </a>
        <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Articles
        </a>
        <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            About
        </a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'text-orange-500 bg-orange-50 px-4 py-2 rounded-full' : 'text-gray-600 hover:text-orange-500 px-4 py-2 transition' }}">
            Contact
        </a>
    </div>

    <div class="hidden md:flex items-center gap-4">
        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-orange-500 transition">Log in</a>
        <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-full text-sm font-medium transition shadow-md">Register</a>
    </div>
</nav>