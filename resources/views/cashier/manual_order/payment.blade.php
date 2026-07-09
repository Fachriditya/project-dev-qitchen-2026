@extends('layouts.master')
@section('title', 'Payment - Manual Order')

@section('content')
<div class="p-6 md:p-8 bg-[#f8f9fa] min-h-screen flex justify-center items-start">
    
    <div class="w-full max-w-lg bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mt-4">
        
        <div class="bg-[#1a2332] p-6 flex justify-between items-center text-white">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center font-bold">Q</div>
                <span class="font-bold tracking-wide">Qitchen Pay</span>
            </div>
            <div class="bg-white/10 px-3 py-1 rounded-full text-xs font-medium flex items-center gap-2">
                <i class="bi bi-clock"></i> <span id="clock">14:58</span>
            </div>
        </div>

        <div class="p-8">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Order {{ $order->order_number }}</p>
                    <h1 class="text-3xl font-black text-gray-900" id="order-total" data-total="{{ $order->order_total }}">Rp {{ number_format($order->order_total, 0, ',', '.') }}</h1>
                </div>
                <span class="bg-orange-50 text-orange-500 px-3 py-1 rounded-full text-xs font-bold capitalize border border-orange-100">
                    {{ str_replace('_', ' ', $order->order_type) }}
                </span>
            </div>

            <h3 class="text-sm font-bold text-gray-900 mb-4">Payment method</h3>

            <!-- OPSI PEMBAYARAN -->
            <form action="{{ route('cashier.manual-order.complete', $order->order_id ?? $order->id) }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->order_id ?? $order->id }}">
                
                <div class="space-y-4">
                    
                    <!-- 1. QRIS OPTION -->
                    <div id="box-qris" class="border-2 border-gray-100 rounded-2xl transition hover:bg-orange-50 overflow-hidden">
                        <label class="flex items-center gap-4 p-4 cursor-pointer w-full m-0">
                            <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="bi bi-qr-code-scan"></i>
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-gray-900 text-sm">QRIS</h4>
                                <p class="text-xs text-gray-500">Scan with any e-wallet</p>
                            </div>
                            
                            <!-- CUSTOM RADIO BUTTON (DIJAMIN ORANYE & RAPI) -->
                            <input type="radio" name="payment_method" value="qris" class="peer hidden" onchange="switchPayment('qris')">
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:border-[7px] bg-white transition-all"></div>
                        </label>
                    </div>

                    <!-- 2. CASH OPTION -->
                    <div id="box-cash" class="border-2 border-orange-500 bg-orange-50 rounded-2xl transition overflow-hidden">
                        
                        <!-- Header Label untuk di-klik -->
                        <label class="flex items-center gap-4 p-4 cursor-pointer w-full m-0">
                            <div class="w-12 h-12 bg-white text-gray-600 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-gray-100">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div class="flex-grow">
                                <h4 class="font-bold text-gray-900 text-sm">Pay at counter (Cash)</h4>
                                <p class="text-xs text-gray-500">Receive cash and calculate change</p>
                            </div>
                            
                            <!-- CUSTOM RADIO BUTTON -->
                            <input type="radio" name="payment_method" value="cash" class="peer hidden" onchange="switchPayment('cash')" checked>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:border-[7px] bg-white transition-all"></div>
                        </label>

                        <!-- 🌟 KALKULATOR KASIR (Dikeluarkan dari Label biar layout aman) -->
                        <div id="cash-calculator" class="px-4 pb-4 block">
                            <div class="space-y-4 pt-4 border-t border-orange-200/50">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Uang Diterima (Rp)</label>
                                    <input type="number" name="cash_received" id="cash_received" placeholder="Contoh: 100000" 
                                           class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition font-bold text-lg shadow-sm"
                                           oninput="calculateChange()">
                                </div>
                                
                                <div class="bg-white rounded-xl p-4 border border-orange-100 shadow-sm space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 font-medium">Total Tagihan</span>
                                        <span class="text-gray-900 font-bold">Rp {{ number_format($order->order_total, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 font-medium">Uang Diterima</span>
                                        <span class="text-gray-900 font-bold" id="display-received">Rp 0</span>
                                    </div>
                                    <div class="border-t border-dashed border-gray-200 pt-2 flex justify-between items-center">
                                        <span class="text-gray-900 font-black">Kembalian</span>
                                        <span class="text-xl font-black text-green-500" id="display-change">Rp 0</span>
                                    </div>
                                </div>
                                
                                <p id="warning-msg" class="text-xs text-red-500 font-bold hidden"><i class="bi bi-exclamation-triangle"></i> Uang yang diterima kurang!</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400 font-medium mb-4"><i class="bi bi-shield-check text-green-500"></i> Secured by Qitchen POS System</p>
                    
                    <button type="submit" id="btn-submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-xl transition shadow-md flex items-center justify-center gap-2 opacity-50 cursor-not-allowed" disabled>
                        Selesaikan Pembayaran <i class="bi bi-check2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const orderTotal = parseInt(document.getElementById('order-total').getAttribute('data-total'));
    const cashInput = document.getElementById('cash_received');
    const displayReceived = document.getElementById('display-received');
    const displayChange = document.getElementById('display-change');
    const warningMsg = document.getElementById('warning-msg');
    const btnSubmit = document.getElementById('btn-submit');
    const cashCalculator = document.getElementById('cash-calculator');
    const paymentForm = document.getElementById('payment-form');
    
    // Elemen Wrapper (Box) untuk diubah warnanya
    const boxQris = document.getElementById('box-qris');
    const boxCash = document.getElementById('box-cash');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // 🌟 1. FUNGSI GANTI METODE (ANTI-MELEDAK)
    function switchPayment(method) {
        if (method === 'qris') {
            // Aktifkan QRIS
            boxQris.classList.replace('border-gray-100', 'border-orange-500');
            boxQris.classList.add('bg-orange-50');
            // Matikan Cash
            boxCash.classList.replace('border-orange-500', 'border-gray-100');
            boxCash.classList.remove('bg-orange-50');
            
            cashCalculator.style.display = 'none'; // Sembunyikan kalkulator
            
            // BUKA GEMBOK TOMBOL
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            // Aktifkan Cash
            boxCash.classList.replace('border-gray-100', 'border-orange-500');
            boxCash.classList.add('bg-orange-50');
            // Matikan QRIS
            boxQris.classList.replace('border-orange-500', 'border-gray-100');
            boxQris.classList.remove('bg-orange-50');
            
            cashCalculator.style.display = 'block'; // Munculkan kalkulator
            
            calculateChange(); // Hitung ulang uang
        }
    }

    // 🌟 2. FUNGSI HITUNG KEMBALIAN
    function calculateChange() {
        let received = parseInt(cashInput.value) || 0;
        displayReceived.innerText = formatRupiah(received);
        let change = received - orderTotal;

        if (received === 0 || change < 0) {
            displayChange.innerText = formatRupiah(change);
            displayChange.classList.replace('text-green-500', 'text-red-500');
            if(received === 0) displayChange.innerText = 'Rp 0';
            
            warningMsg.classList.remove('hidden');

            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            displayChange.innerText = formatRupiah(change);
            displayChange.classList.replace('text-red-500', 'text-green-500');
            warningMsg.classList.add('hidden');

            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // 🌟 3. FUNGSI SUBMIT FORM & MIDTRANS
    paymentForm.addEventListener('submit', function(e) {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (selectedMethod === 'qris') {
            e.preventDefault(); 
            
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran QRIS Sukses! Pesanan lunas.");
                    window.location.href = "{{ route('cashier.transactions.index') }}"; 
                },
                onPending: function(result){
                    alert("Menunggu customer menyelesaikan scan QRIS...");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    alert('Popup ditutup oleh kasir.');
                }
            });
        } 
    });

    // 🌟 4. JAM REAL-TIME
    setInterval(() => {
        const now = new Date();
        document.getElementById('clock').innerText = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }, 1000);

    // Initial check
    calculateChange();
</script>
@endsection