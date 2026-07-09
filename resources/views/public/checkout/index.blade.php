@extends('layouts.public')
@section('title', 'Checkout - Qitchen')

@section('head')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 bg-gray-50/50 min-h-screen">

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-8 shadow-sm">
            <p class="font-bold"><i class="bi bi-exclamation-triangle"></i> Oops, something went wrong!</p>
            <p class="text-sm mt-1">{{ session('error') }}</p>
        </div>
    @endif
    
    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-8">Checkout</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="lg:w-2/3 space-y-6">
            @csrf
            
            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order type</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

            <div id="form-dinein" class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Dine In details</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name for the order</label>
                    <input type="text" name="dinein_name" placeholder="e.g., Fachri" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    <p class="text-xs text-gray-500 mt-2"><i class="bi bi-megaphone"></i> We will call this name when your order is ready.</p>
                </div>
            </div>
            
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

            <div id="form-delivery" class="hidden bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Delivery address</h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Recipient</label>
                            <input type="text" name="delivery_name" value="{{ Auth::user()->name ?? '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" name="delivery_phone" value="{{ Auth::user()->phone ?? '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full address</label>
                        <input type="text" name="delivery_address" value="{{ Auth::user()->address ?? '' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition">
                    </div>
                    
                    <!-- 🌟 BAGIAN PETA INTERAKTIF ZERO TO HERO -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pin your location</label>
                        <p class="text-xs text-gray-500 mb-2">Drag the marker to pinpoint your exact delivery location.</p>
                        
                        <!-- Box Peta -->
                        <div id="checkout-map" class="w-full h-48 md:h-64 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shadow-inner z-0 relative"></div>
                        
                        <!-- Input tersembunyi untuk dikirim ke Controller -->
                        <input type="hidden" name="latitude" id="input_latitude">
                        <input type="hidden" name="longitude" id="input_longitude">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-[1.5rem] p-6 md:p-8 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Notes</h2>
                <textarea name="order_notes" rows="2" placeholder="Any special requests for the restaurant or driver?" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-orange-500 outline-none transition"></textarea>
            </div>

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
            
        </form> <div class="lg:w-1/3 mt-8 lg:mt-0">
            <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 sticky top-28 shadow-sm">
                <h3 class="text-xl font-black text-gray-900 mb-6">Order summary</h3>

                <div class="space-y-4 mb-4 text-sm font-medium">
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

                    @if(isset($discount) && $discount > 0)
                    <div class="flex justify-between text-green-500 font-bold pt-2">
                        <span>Discount (Promo)</span>
                        <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                <div class="py-5 border-y border-gray-100 my-4">
                    @if(session('applied_voucher'))
                        <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                            <div>
                                <span class="block text-xs text-green-500 font-bold uppercase">Promo Dipakai</span>
                                <span class="block text-sm font-black text-green-700">{{ session('applied_voucher')['code'] }}</span>
                            </div>
                            <form action="{{ route('checkout.remove-voucher') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm bg-transparent border-none">Batalkan</button>
                            </form>
                        </div>
                    @else
                        <h4 class="text-sm font-bold text-gray-900 mb-3">Punya Kode Voucher?</h4>
                        <form action="{{ route('checkout.apply-voucher') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="voucher_code" placeholder="MASUKKAN KODE PROMO" 
                                class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm text-gray-700 focus:ring-2 focus:ring-orange-500 uppercase">
                            <button type="submit" class="bg-gray-900 hover:bg-black text-white px-5 py-3 rounded-xl text-sm font-bold transition shrink-0">
                                Apply
                            </button>
                        </form>
                    @endif
                </div>

                <div class="pt-2 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-900 font-bold text-lg">Total</span>
                        <span class="text-2xl font-black text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="submit" form="checkout-form" class="block w-full bg-orange-500 text-white text-center py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                    Continue to payment <i class="bi bi-arrow-right"></i>
                </button>
                
            </div>
        </div>

    </div>
</div>
@endsection

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // ... Logika toggleForms() milikmu yang lama tetap biarkan di sini ...
    function toggleForms(type) {
        const dineinForm = document.getElementById('form-dinein');
        const takeawayForm = document.getElementById('form-takeaway');
        const deliveryForm = document.getElementById('form-delivery');
        
        dineinForm.classList.add('hidden');
        takeawayForm.classList.add('hidden');
        deliveryForm.classList.add('hidden');

        if (type === 'dine_in') {
            dineinForm.classList.remove('hidden');
        } else if (type === 'takeaway') {
            takeawayForm.classList.remove('hidden');
        } else if (type === 'delivery') {
            deliveryForm.classList.remove('hidden');
            
            // Waktu form delivery muncul, paksa peta untuk me-refresh ukurannya
            // (Karena peta suka 'error kotak abu-abu' kalau di-load saat display: hidden)
            setTimeout(function() {
                if(typeof map !== 'undefined') {
                    map.invalidateSize();
                }
            }, 100);
        }

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

    // ==========================================
    // 🌟 LOGIKA MAP LEAFLET
    // ==========================================
    var map, marker;
    
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Koordinat default (Misal: Pusat Kota Surabaya)
        var defaultLat = -7.2756;
        var defaultLng = 112.7939;

        // 2. Isi nilai awal ke input tersembunyi
        document.getElementById('input_latitude').value = defaultLat;
        document.getElementById('input_longitude').value = defaultLng;

        // 3. Inisialisasi Peta
        map = L.map('checkout-map').setView([defaultLat, defaultLng], 15);

        // 4. Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // 5. Ikon Custom biar cakep
        var pinIcon = L.divIcon({
            html: '<div class="text-orange-500 drop-shadow-md text-4xl"><i class="bi bi-geo-alt-fill"></i></div>',
            className: 'custom-pin',
            iconSize: [30, 42],
            iconAnchor: [15, 42]
        });

        // 6. Pasang Marker yang bisa di-drag (digeser)
        marker = L.marker([defaultLat, defaultLng], {
            icon: pinIcon,
            draggable: true // BISA DIGESER!
        }).addTo(map);

        // 7. Event Listener: Kalau markernya selesai digeser, update koordinatnya!
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            
            // Masukkan koordinat baru ke input hidden untuk di-submit
            document.getElementById('input_latitude').value = position.lat;
            document.getElementById('input_longitude').value = position.lng;
            
            // Tambahkan efek popup sesaat (Biar user tau koordinat tersimpan)
            marker.bindPopup("Location pinned!").openPopup();
            setTimeout(function() { marker.closePopup(); }, 1500);
        });
        
        // Opsional: Lacak lokasi asli user (kalau dia allow location)
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                
                // Pindahkan peta dan marker ke lokasi aslinya
                map.setView([lat, lng], 15);
                marker.setLatLng([lat, lng]);
                
                // Update input hidden
                document.getElementById('input_latitude').value = lat;
                document.getElementById('input_longitude').value = lng;
            });
        }
    });
</script>