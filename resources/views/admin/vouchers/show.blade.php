@extends('layouts.master')
@section('title', 'Voucher Detail')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.vouchers.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Voucher Details</h1>
    </div>

    <!-- DESAIN KARTU TIKET VOUCHER -->
    <div class="max-w-3xl mx-auto mt-10 flex flex-col md:flex-row shadow-[0_10px_40px_-10px_rgba(249,115,22,0.2)] rounded-3xl overflow-hidden bg-white">
        
        <!-- BAGIAN KIRI (Stub Tiket - Oranye) -->
        <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white p-10 md:w-2/5 flex flex-col justify-center items-center relative border-b-2 md:border-b-0 md:border-r-2 border-dashed border-white/50">
           
           <!-- Efek Irisan Lingkaran (Hanya terlihat di desktop) -->
           <div class="absolute -top-5 -right-5 w-10 h-10 bg-[#f8f9fa] rounded-full hidden md:block z-10"></div>
           <div class="absolute -bottom-5 -right-5 w-10 h-10 bg-[#f8f9fa] rounded-full hidden md:block z-10"></div>
           
           <i class="bi bi-ticket-detailed text-6xl mb-4 opacity-90 drop-shadow-md"></i>
           
           <h2 class="text-5xl font-black text-center drop-shadow-md">
                {{ $voucher->voucher_type === 'percentage' ? $voucher->voucher_value . '%' : 'Rp ' . number_format($voucher->voucher_value, 0, ',', '.') }}
           </h2>
           <p class="font-bold text-orange-100 uppercase tracking-widest mt-3 text-sm text-center">
                {{ $voucher->voucher_type === 'free_delivery' ? 'Discount' : 'Off' }}
           </p>
        </div>

        <!-- BAGIAN KANAN (Detail Tiket - Putih) -->
        <div class="p-10 md:w-3/5 relative">
            
            <div class="flex justify-between items-start mb-8">
                <div>
                    <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 inline-block border border-gray-200">
                        {{ str_replace('_', ' ', $voucher->voucher_type) }}
                    </span>
                    <h1 class="text-3xl font-black text-gray-900 leading-tight">{{ $voucher->voucher_name }}</h1>
                </div>
                <div class="ml-4 shrink-0">
                    @if($voucher->is_active && (!$voucher->expiration_date || $voucher->expiration_date > now()))
                        <span class="bg-green-50 text-green-500 px-4 py-2 rounded-full text-sm font-bold border border-green-200 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span> Active
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-500 px-4 py-2 rounded-full text-sm font-bold border border-gray-200">Nonactive</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-8">
                <!-- Voucher Code -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Voucher Code</p>
                    <div class="inline-block bg-orange-50 border border-orange-200 px-4 py-2 rounded-xl">
                        <p class="text-xl font-mono font-black text-orange-600 tracking-widest">
                            {{ $voucher->voucher_code }}
                        </p>
                    </div>
                </div>

                <!-- Expiry Date -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Expiry Date</p>
                    <p class="text-sm font-bold text-gray-900 mt-2 flex items-center">
                        <i class="bi bi-calendar-x mr-2 text-red-400"></i>
                        {{ $voucher->expiration_date ? $voucher->expiration_date->format('d M Y, H:i') : 'No Expiry Date' }}
                    </p>
                </div>
                
                <!-- Min Order -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Min. Order</p>
                    <p class="text-sm font-bold text-gray-900">{{ $voucher->min_order ? 'Rp ' . number_format($voucher->min_order, 0, ',', '.') : 'No Minimum' }}</p>
                </div>

                <!-- Max Discount -->
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Max. Discount</p>
                    <p class="text-sm font-bold text-gray-900">{{ $voucher->max_discount ? 'Rp ' . number_format($voucher->max_discount, 0, ',', '.') : 'No Maximum' }}</p>
                </div>

                <!-- Quota & Issued -->
                <div class="col-span-2 bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-between items-center mt-2">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Redeemed Usage</p>
                        <p class="text-lg font-black text-gray-900">
                            {{ $voucher->issued_count }} <span class="text-sm text-gray-400 font-medium">/ {{ $voucher->quota ?? 'Unlimited' }}</span>
                        </p>
                    </div>
                    <!-- Mini Progress Bar Visual -->
                    @if($voucher->quota)
                        <div class="w-1/2 bg-gray-200 rounded-full h-2.5 ml-4">
                            @php 
                                $percentage = ($voucher->issued_count / $voucher->quota) * 100;
                                $percentage = $percentage > 100 ? 100 : $percentage;
                            @endphp
                            <div class="bg-orange-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Terms & Conditions (Description) -->
            @if($voucher->voucher_description)
            <div class="border-t-2 border-dashed border-gray-200 pt-6 mt-2 relative">
                <!-- Barcode Tiruan -->
                <div class="absolute top-6 right-0 opacity-20">
                    <i class="bi bi-upc-scan text-4xl"></i>
                </div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-wider mb-2">Terms & Conditions</p>
                <p class="text-sm text-gray-600 leading-relaxed pr-12">{{ $voucher->voucher_description }}</p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection