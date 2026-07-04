@extends('layouts.public')
@section('title', 'Qitchen Pay')

@section('content')
<!-- UBAH MAX-W-MD JADI MAX-W-LG AGAR LEBIH LEBAR DAN PROPORSIONAL -->
<div class="max-w-lg mx-auto px-6 py-12">
    
    <div class="bg-white border border-gray-100 rounded-[2rem] shadow-xl overflow-hidden">
        
        <!-- Header Dark -->
        <div class="bg-[#2a303c] p-6 flex justify-between items-center text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center font-bold text-lg">Q</div>
                <h1 class="font-bold text-lg">Qitchen Pay</h1>
            </div>
            <div class="bg-white/10 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5">
                <i class="bi bi-clock"></i> 14:58
            </div>
        </div>

        <!-- Body Detail -->
        <div class="p-8">
            <p class="text-sm text-gray-400 font-medium mb-1">Order #Q-{{ str_pad($order->order_id, 4, '0', STR_PAD_LEFT) }}</p>
            <div class="flex justify-between items-end mb-8">
                <h2 class="text-3xl font-black text-gray-900">Rp {{ number_format($order->order_total, 0, ',', '.') }}</h2>
                <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">{{ explode(' - ', $order->order_notes)[0] }}</span>
            </div>

            <h3 class="text-sm font-bold text-gray-900 mb-4">Supported Payments</h3>

            <!-- ETALASE 1: QRIS & E-WALLET (Tanpa fungsi klik, murni display) -->
            <div class="border border-gray-200 bg-white rounded-2xl p-5 flex flex-col gap-3 mb-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-orange-500 text-white rounded-xl flex items-center justify-center text-2xl shadow-md">
                        <i class="bi bi-qr-code-scan"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">QRIS & E-Wallet</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Scan or pay directly via app</p>
                    </div>
                </div>
                <!-- Deretan teks pendukung E-Wallet -->
                <div class="pt-3 border-t border-gray-100 mt-1">
                    <div class="flex flex-wrap items-center gap-2 text-[10px] font-black uppercase tracking-wider">
                        <span class="bg-white px-2 py-1 rounded text-green-600 border border-green-100">GoPay</span>
                        <span class="bg-white px-2 py-1 rounded text-purple-600 border border-purple-100">OVO</span>
                        <span class="bg-white px-2 py-1 rounded text-blue-500 border border-blue-100">DANA</span>
                        <span class="bg-white px-2 py-1 rounded text-red-600 border border-red-100">LinkAja</span>
                        <span class="bg-white px-2 py-1 rounded text-orange-500 border border-orange-100">ShopeePay</span>
                    </div>
                </div>
            </div>

            <!-- ETALASE 2: VIRTUAL ACCOUNT (Tanpa fungsi klik, murni display) -->
            <div class="border border-gray-200 bg-white rounded-2xl p-5 flex flex-col gap-3 mb-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-xl flex items-center justify-center text-2xl shadow-md">
                        <i class="bi bi-bank"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Virtual Account</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Pay via Bank Transfer</p>
                    </div>
                </div>
                <!-- Deretan teks pendukung Bank dengan Warna Asli (Brand Colors) -->
                <div class="pt-3 border-t border-gray-100 mt-1">
                    <div class="flex flex-wrap items-center gap-2 text-[9px] sm:text-[10px] font-black uppercase tracking-wider">
                        <span class="bg-blue-50 px-2 py-1 rounded text-blue-800 border border-blue-200">BCA</span>
                        <span class="bg-blue-50 px-2 py-1 rounded text-blue-600 border border-blue-200">BRI</span>
                        <span class="bg-orange-50 px-2 py-1 rounded text-orange-600 border border-orange-200">BNI</span>
                        <span class="bg-yellow-50 px-2 py-1 rounded text-yellow-600 border border-yellow-200">Mandiri</span>
                        <span class="bg-emerald-50 px-2 py-1 rounded text-emerald-600 border border-emerald-200">Permata</span>
                        <span class="bg-red-50 px-2 py-1 rounded text-red-600 border border-red-200">CIMB</span>
                        <span class="bg-cyan-50 px-2 py-1 rounded text-cyan-600 border border-cyan-200">BSI</span>
                    </div>
                </div>
            </div>

            <!-- ETALASE 3: CASH (Digembok sesuai request) -->
            <div class="border border-gray-200 bg-gray-50 rounded-2xl p-4 flex items-center gap-4 mb-8 opacity-60 cursor-not-allowed relative">
                <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-xl flex items-center justify-center text-xl shadow-inner">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-500">Pay at counter (Cash)</h4>
                    <!-- Teks peringatan dalam Bahasa Inggris -->
                    <p class="text-[11px] text-red-400 mt-0.5 font-bold">*Online orders only support online payments</p>
                </div>
                <div class="absolute right-4 text-gray-300">
                    <i class="bi bi-lock-fill text-lg"></i>
                </div>
            </div>

            <div class="text-center mb-6">
                <p class="text-xs text-gray-400 font-medium flex items-center justify-center gap-1.5">
                    <i class="bi bi-shield-fill-check text-green-500 text-base"></i> 
                    Secured by Qitchen Pay • 256-bit encryption
                </p>
            </div>

            <!-- Tombol Pay Now dan script ada di bawah sini... -->

            <!-- TOMBOL AJAIB PEMANGGIL MIDTRANS -->
            <button id="pay-button" class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold text-lg hover:bg-orange-600 transition shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2">
                Pay Now <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- SCRIPT MIDTRANS SNAP -->
<!-- ============================================== -->
<!-- Gunakan URL Sandbox Midtrans -->
 <script>
    function selectPayment(method) {
        // Elemen Card
        const cardQris = document.getElementById('card-qris');
        const cardVa = document.getElementById('card-va');
        // Elemen Radio Indicator
        const radioQris = document.getElementById('radio-qris');
        const radioVa = document.getElementById('radio-va');

        // Reset semua ke state tidak aktif (abu-abu)
        cardQris.className = "payment-card border border-gray-200 bg-white rounded-2xl p-5 flex flex-col gap-3 mb-4 relative cursor-pointer hover:border-orange-200 shadow-sm transition-all duration-300";
        cardVa.className = "payment-card border border-gray-200 bg-white rounded-2xl p-5 flex flex-col gap-3 mb-4 relative cursor-pointer hover:border-orange-200 shadow-sm transition-all duration-300";
        radioQris.className = "absolute right-5 top-6 w-5 h-5 rounded-full border-2 border-gray-200 bg-white";
        radioVa.className = "absolute right-5 top-6 w-5 h-5 rounded-full border-2 border-gray-200 bg-white";

        // Aktifkan yang dipilih (Warna Oranye)
        if (method === 'qris') {
            cardQris.className = "payment-card border-2 border-orange-500 bg-orange-50 rounded-2xl p-5 flex flex-col gap-3 mb-4 relative cursor-pointer shadow-sm transition-all duration-300";
            radioQris.className = "absolute right-5 top-6 w-5 h-5 rounded-full border-[5px] border-orange-500 bg-white";
        } else if (method === 'va') {
            cardVa.className = "payment-card border-2 border-orange-500 bg-orange-50 rounded-2xl p-5 flex flex-col gap-3 mb-4 relative cursor-pointer shadow-sm transition-all duration-300";
            radioVa.className = "absolute right-5 top-6 w-5 h-5 rounded-full border-[5px] border-orange-500 bg-white";
        }
    }
</script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            // Jalur Frontend: Redirect setelah sukses
            onSuccess: function(result){
                // Langsung lempar ke halaman tracking
                window.location.href = "/track/{{ $order->order_number }}"; 
            },
            // Jalur Frontend: Redirect jika customer menutup tapi belum bayar (misal: mau transfer nanti)
            onPending: function(result){
                window.location.href = "/track/{{ $order->order_number }}";
            },
            onError: function(result){
                alert("Payment failed! Please try again.");
            },
            onClose: function(){
                alert('You closed the payment window.');
            }
        });
    };
</script>
@endsection