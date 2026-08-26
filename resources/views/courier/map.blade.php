@extends('layouts.master')
@section('title', 'Delivery Map')
@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold">Delivery Map - Order #{{ $order->order_number }}</h1>
    <p>Recipient: {{ $order->delivery->recipient_name ?? 'N/A' }}</p>
    <p>Address: {{ $order->delivery->delivery_address ?? 'N/A' }}</p>
    <div id="map" class="w-full h-96 bg-gray-200 mt-4">Map Placeholder</div>
    <form method="POST" action="{{ route('courier.complete', $order->order_id) }}" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-primary">Complete Delivery</button>
    </form>
</div>
@endsection
