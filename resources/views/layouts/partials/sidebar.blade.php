<aside class="w-64 bg-white border-r border-gray-100 flex flex-col h-screen transition-all duration-300 shrink-0">
    
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-6 border-b border-gray-100">
        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold mr-3 shrink-0">
            Q
        </div>
        <div>
            <h1 class="text-xl font-extrabold text-gray-900 leading-none">Qitchen</h1>
            <p class="text-xs text-gray-500 font-medium mt-1">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        
        <!-- ================= MENU ADMIN ================= -->
        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
            <!-- Tombol Active (Dashboard) -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 bg-orange-500 text-white rounded-xl font-bold transition">
                <i class="bi bi-grid-1x2 mr-3 text-lg"></i> Dashboard
            </a>
            
            <!-- Tombol Inactive -->
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-journal-text mr-3 text-lg"></i> Menu
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-tags mr-3 text-lg"></i> Categories
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-people mr-3 text-lg"></i> Users
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-ticket-perforated mr-3 text-lg"></i> Vouchers
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-receipt mr-3 text-lg"></i> Transactions
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-truck mr-3 text-lg"></i> Deliveries
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-bar-chart-line mr-3 text-lg"></i> Reports
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-newspaper mr-3 text-lg"></i> Articles
            </a>
        @endif

        <!-- ================= MENU CASHIER ================= -->
        @if(Auth::user()->role === 'cashier')
            <a href="#" class="flex items-center px-4 py-3 bg-orange-500 text-white rounded-xl font-bold transition">
                <i class="bi bi-receipt mr-3 text-lg"></i> Transactions
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-pencil-square mr-3 text-lg"></i> Manual Order
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-bag-check mr-3 text-lg"></i> Pickup Counter
            </a>
        @endif

        <!-- ================= MENU KITCHEN ================= -->
        @if(Auth::user()->role === 'kitchen')
            <a href="#" class="flex items-center px-4 py-3 bg-orange-500 text-white rounded-xl font-bold transition">
                <i class="bi bi-display mr-3 text-lg"></i> Kitchen Board
            </a>
        @endif

        <!-- ================= MENU COURIER ================= -->
        @if(Auth::user()->role === 'courier')
            <a href="#" class="flex items-center px-4 py-3 bg-orange-500 text-white rounded-xl font-bold transition">
                <i class="bi bi-box-seam mr-3 text-lg"></i> Delivery Offers
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-500 hover:text-gray-900 hover:bg-gray-50 rounded-xl font-medium transition">
                <i class="bi bi-truck mr-3 text-lg"></i> My Deliveries
            </a>
        @endif

    </nav>

    <!-- Logout Area -->
    <div class="p-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-slate-900 text-white py-3.5 rounded-xl font-bold hover:bg-slate-800 transition shadow-md">
                Logout
            </button>
        </form>
    </div>
</aside>