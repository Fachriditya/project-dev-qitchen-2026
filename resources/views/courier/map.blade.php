@extends('layouts.master')
@section('title', 'Delivery Map - ' . $order->order_number)

@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 400px; }
    .location-btn { transition: all 0.2s; }
    .location-btn:active { transform: scale(0.95); }
    .leaflet-control-custom {
        background: white;
        border: 2px solid rgba(0,0,0,0.2);
        border-radius: 4px;
        cursor: pointer;
        padding: 6px 10px;
        font-size: 14px;
        font-weight: bold;
        box-shadow: 0 1px 5px rgba(0,0,0,0.3);
    }
    .leaflet-control-custom:hover { background: #f0f0f0; }
</style>
@endsection

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white border border-gray-200 rounded-[2rem] p-6 md:p-8 shadow-sm mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900">Delivery Map</h1>
                <p class="text-gray-500 font-medium">Order #{{ $order->order_number }}</p>
            </div>
            <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold capitalize">
                {{ $order->delivery->delivery_status ?? 'waiting' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="font-bold text-gray-900">Recipient</p>
                <p class="text-gray-600">{{ $order->delivery->recipient_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="font-bold text-gray-900">Phone</p>
                <p class="text-gray-600">{{ $order->delivery->recipient_phone ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="font-bold text-gray-900">Address</p>
                <p class="text-gray-600">{{ $order->delivery->delivery_address ?? 'N/A' }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl text-sm font-bold">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
            </div>
        @endif
    </div>

    <div id="map" class="w-full rounded-2xl border border-gray-200 shadow-inner z-0 mb-4"></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <button id="update-location-btn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-2xl transition shadow-lg location-btn">
            <i class="bi bi-crosshair"></i> Update My Location
        </button>

        <form method="POST" action="{{ route('courier.complete', $order->order_id) }}">
            @csrf
            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-2xl transition shadow-lg location-btn">
                <i class="bi bi-check2-circle"></i> Complete Delivery
            </button>
        </form>
    </div>

    <div id="location-status" class="text-center text-sm text-gray-500 font-medium"></div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var destLat = {{ $order->delivery->latitude ?? -7.2756 }};
        var destLng = {{ $order->delivery->longitude ?? 112.7939 }};
        var courierLat = {{ $order->delivery->courier_latitude ?? -7.2756 }};
        var courierLng = {{ $order->delivery->courier_longitude ?? 112.7939 }};

        var map = L.map('map').setView([courierLat, courierLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Restaurant marker
        var restIcon = L.divIcon({
            html: '<div style="background:#1a2332;color:white;padding:6px 10px;border-radius:12px;font-size:12px;font-weight:bold;white-space:nowrap;"><i class="bi bi-shop"></i> Qitchen</div>',
            className: '',
            iconSize: [80, 30],
            iconAnchor: [40, 15]
        });

        // Destination marker
        var destIcon = L.divIcon({
            html: '<div style="background:#f97316;color:white;padding:6px 10px;border-radius:12px;font-size:12px;font-weight:bold;white-space:nowrap;"><i class="bi bi-geo-alt-fill"></i> Destination</div>',
            className: '',
            iconSize: [90, 30],
            iconAnchor: [45, 15]
        });

        // Courier marker (draggable)
        var courierIcon = L.divIcon({
            html: '<div style="background:#3b82f6;color:white;padding:6px 10px;border-radius:12px;font-size:12px;font-weight:bold;white-space:nowrap;box-shadow:0 2px 8px rgba(59,130,246,0.4);"><i class="bi bi-person-bounding-box"></i> You</div>',
            className: '',
            iconSize: [60, 30],
            iconAnchor: [30, 15]
        });

        L.marker([-7.2756, 112.7939], { icon: restIcon }).addTo(map).bindPopup('<b>Qitchen Restaurant</b>');
        L.marker([destLat, destLng], { icon: destIcon }).addTo(map).bindPopup('<b>Delivery Destination</b><br>' + '{{ $order->delivery->delivery_address ?? "" }}');
        
        var courierMarker = L.marker([courierLat, courierLng], { icon: courierIcon, draggable: true }).addTo(map);
        courierMarker.bindPopup('<b>Your Location</b>');

        // Route line
        L.polyline([
            [-7.2756, 112.7939],
            [destLat, destLng]
        ], { color: '#f97316', weight: 3, opacity: 0.6, dashArray: '10, 10' }).addTo(map);

        // Fit bounds to show all markers
        var group = L.featureGroup([
            courierMarker,
            L.marker([-7.2756, 112.7939]),
            L.marker([destLat, destLng])
        ]);
        map.fitBounds(group.getBounds().pad(0.1));

        // ==========================================
        // UPDATE LOCATION BUTTON (FR-38)
        // ==========================================
        document.getElementById('update-location-btn').addEventListener('click', function() {
            var statusEl = document.getElementById('location-status');
            statusEl.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Getting your location...';

            if (!navigator.geolocation) {
                statusEl.innerHTML = '<span class="text-red-500">Geolocation is not supported by your browser</span>';
                return;
            }

            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                // Update marker on map
                courierMarker.setLatLng([lat, lng]);
                map.setView([lat, lng], 15);

                // Send to server
                fetch('{{ route("courier.update-location", $order->order_id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    statusEl.innerHTML = '<span class="text-green-500"><i class="bi bi-check-circle"></i> Location updated successfully!</span>';
                    setTimeout(function() { statusEl.innerHTML = ''; }, 3000);
                })
                .catch(function() {
                    statusEl.innerHTML = '<span class="text-red-500">Failed to update location</span>';
                });
            }, function() {
                statusEl.innerHTML = '<span class="text-red-500">Could not get your location. Please enable GPS.</span>';
            });
        });
    });
</script>
@endsection