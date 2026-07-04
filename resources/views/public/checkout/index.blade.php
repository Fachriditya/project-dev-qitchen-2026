@extends('layouts.public')
@section('title', 'Checkout - Qitchen')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 bg-gray-50/50 min-h-screen">

<!-- CCTV Error Penangkap Bug -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-8 shadow-sm">
            <p class="font-bold"><i class="bi bi-exclamation-triangle"></i> Oops, something went wrong!</p>
            <p class="text-sm mt-1">{{ session('error') }}</p>
        </div>
    @endif
    
    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-8">Checkout</h1>

    <!-- FORM UTAMA (Nanti action-nya mengarah ke Midtrans) -->
    <form action="{{ route('checkout.process') }}" method="POST" class="flex flex-col lg:flex-row gap-8">
        @csrf
        
        <!-- KOLOM KIRI (Form Data) -->
        <div class="lg:w-2/3 space-y-6">
            
            <!-- 1. ORDER TYPE -->
            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order type</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Dine In -->
                    <label class="cursor-pointer">
                        <input type="radio" name="order_type" value="dine_in" class="peer sr-only" checked onchange="toggleForms('dine_in')">
                        <div class="order-type-card rounded-2xl border-2 p-5 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-200">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-orange-500">
                                <i class="bi bi-shop"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Dine In</h3>
                            <p class="text-xs text-gray-500 mt-1">Eat at the restaurant</p>
                        </div>
                    </label>

                    <!-- Takeaway -->
                    <label class="cursor-pointer">
                        <input type="radio" name="order_type" value="takeaway" class="peer sr-only" onchange="toggleForms('takeaway')">
                        <div class="order-type-card rounded-2xl border-2 p-5 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-200">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-500">
                                <i class="bi bi-bag"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Takeaway</h3>
                            <p class="text-xs text-gray-500 mt-1">Pick up at counter</p>
                        </div>
                    </label>

                    <!-- Delivery -->
                    <label class="cursor-pointer">
                        <input type="radio" name="order_type" value="delivery" class="peer sr-only" onchange="toggleForms('delivery')">
                        <div class="order-type-card rounded-2xl border-2 p-5 transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 border-gray-200 hover:border-orange-200">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm mb-3 text-gray-500">
                                <i class="bi bi-bicycle"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Delivery</h3>
                            <p class="text-xs text-gray-500 mt-1">Bring it to me</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 2. DYNAMIC FORMS -->

            <!-- A. Dine In Form (Muncul secara default) -->
            <div id="form-dinein" class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Dine In details</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name for the order</label>
                    <input type="text" name="dinein_name" placeholder="e.g., Fachri" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-2"><i class="bi bi-megaphone"></i> We will call this name when your order is ready.</p>
                </div>
            </div>
            
            <!-- B. Takeaway Form (Hidden by default) -->
            <div id="form-takeaway" class="hidden bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Pickup details</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name for the order</label>
                        <input type="text" name="pickup_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pickup time</label>
                        <select name="pickup_time" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition appearance-none">
                            <option>As soon as possible</option>
                            <option>In 30 minutes</option>
                            <option>In 1 hour</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- B. Delivery Form (Hidden by default) -->
            <div id="form-delivery" class="hidden bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Delivery address</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipient</label>
                            <input type="text" name="delivery_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="delivery_phone" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full address</label>
                        <input type="text" name="delivery_address" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    </div>
                    
                    <!-- Peta Dummy dari Figma -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pin your location</label>
                        <div class="w-full h-40 bg-gray-200 rounded-xl overflow-hidden relative">
                            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=800" class="w-full h-full object-cover opacity-60">
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <i class="bi bi-geo-alt-fill text-3xl text-orange-500 drop-shadow-md"></i>
                                <span class="bg-white/90 px-3 py-1 rounded-full text-xs font-bold mt-2 shadow-sm">Drag to adjust pin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ORDER NOTES (Catatan Keseluruhan) -->
            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Notes</h2>
                <textarea name="order_notes" rows="2" placeholder="Any special requests for the restaurant or driver?" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition"></textarea>
            </div>

            <!-- 3. ITEMS LIST (Compact) -->
            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Items ({{ count($cart) }})</h2>
                
                <div class="space-y-4">
                    @foreach($cart as $details)
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('storage/' . $details['image']) }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100" onerror="this.style.display='none'">
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm">{{ $details['quantity'] }}× {{ $details['menu_name'] }}</h4>
                                @if(!empty($details['item_notes']))
                                    <p class="text-xs text-orange-500 mt-0.5"><i class="bi bi-chat-left-text text-[10px]"></i> {{ $details['item_notes'] }}</p>
                                @endif
                            </div>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            
        </div>

        <!-- KOLOM KANAN (Order Summary) -->
        <div class="lg:w-1/3">
            <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 sticky top-28 shadow-sm">
                <h3 class="text-xl font-black text-gray-900 mb-6">Order summary</h3>

                <div class="space-y-4 mb-6 text-sm font-medium">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Tax (10%)</span>
                        <span class="text-gray-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Service fee</span>
                        <span class="text-gray-900">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Voucher Box -->
                <div class="flex gap-2 mb-6">
                    <input type="text" placeholder="Voucher code" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    <button type="button" class="px-5 py-2 border border-gray-200 rounded-xl text-sm font-bold hover:bg-gray-50 transition">Apply</button>
                </div>

                <div class="border-t border-gray-100 pt-6 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-bold text-lg">Total</span>
                        <span class="text-2xl font-black text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tombol Submit Form (Nanti ke Midtrans) -->
                <button type="submit" class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                    Continue to payment <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>

    </form>
</div>

<!-- Script untuk Tab Order Type -->
<script>
    function toggleForms(type) {
        // Ambil elemen form
        const dineinForm = document.getElementById('form-dinein'); // Tangkap form baru
        const takeawayForm = document.getElementById('form-takeaway');
        const deliveryForm = document.getElementById('form-delivery');
        
        // Sembunyikan semua dulu
        dineinForm.classList.add('hidden');
        takeawayForm.classList.add('hidden');
        deliveryForm.classList.add('hidden');

        // Munculkan sesuai pilihan
        if (type === 'dine_in') {
            dineinForm.classList.remove('hidden');
        } else if (type === 'takeaway') {
            takeawayForm.classList.remove('hidden');
        } else if (type === 'delivery') {
            deliveryForm.classList.remove('hidden');
        }

        // Efek warna ikon
        document.querySelectorAll('input[name="order_type"]').forEach(radio => {
            const icon = radio.nextElementSibling.querySelector('i').parentElement;
            if (radio.checked) {
                icon.classList.remove('text-gray-500');
                icon.classList.add('text-orange-500');
            } else {
                icon.classList.remove('text-orange-500');
                icon.classList.add('text-gray-500');
            }
        });
    }
</script>
@endsection