@extends('layouts.master')
@section('title', 'Transaction Detail')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header Atas (Sekarang ada tombol Download) -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.transactions.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Order Details</h1>
        </div>
        
        <!-- TOMBOL DOWNLOAD -->
        <button onclick="downloadNota()" class="bg-orange-500 text-white px-6 py-2.5 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-download mr-2"></i> Download Nota
        </button>
    </div>

    <!-- Lebar card aku perbesar sedikit (max-w-4xl) agar muat 3 kolom -->
    <div id="nota-card" class="max-w-4xl bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Header Nota -->
        <div class="p-8 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <p class="text-gray-500 font-medium text-sm mb-1">Order Number</p>
                <h2 class="text-2xl font-black font-mono text-gray-900">{{ $transaction->order_number ?? '#Q-' . str_pad($transaction->order_id, 5, '0', STR_PAD_LEFT) }}</h2>
                
                <!-- JAM & TANGGAL TRANSAKSI -->
                <div class="flex items-center gap-4 mt-2">
                    <p class="text-gray-500 text-sm font-medium"><i class="bi bi-calendar3 mr-1"></i> {{ $transaction->created_at->format('d M Y') }}</p>
                    <p class="text-gray-500 text-sm font-medium"><i class="bi bi-clock mr-1"></i> {{ $transaction->created_at->format('H:i') }} WIB</p>
                </div>
            </div>
            
            <div class="text-left md:text-right">
                <p class="text-gray-500 font-medium text-sm mb-1">Status</p>
                <span class="inline-block bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-sm font-bold capitalize border border-orange-200">
                    {{ $transaction->order_status }}
                </span>
            </div>
        </div>

        <!-- Info Pelanggan, Tipe Pesanan & Pembayaran (Sekarang jadi 3 Kolom) -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8 border-b border-gray-100">
            <!-- 1. Customer Info -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customer Info</p>
                <p class="text-gray-900 font-bold text-lg">
                    @if($transaction->user)
                        {{ $transaction->user->name }}
                    @else
                        Guest (Walk-in)
                    @endif
                </p>
                @if($transaction->user)
                    <p class="text-gray-500">{{ $transaction->user->phone ?? 'No Phone' }}</p>
                    <p class="text-gray-500">{{ $transaction->user->email }}</p>
                @else
                    <p class="text-gray-400 text-sm mt-1">No account details recorded.</p>
                @endif
            </div>

            <!-- 2. Order Type -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Order Type</p>
                <p class="text-gray-900 font-bold text-lg capitalize flex items-center">
                    <i class="bi {{ $transaction->order_type == 'dine_in' ? 'bi-shop' : ($transaction->order_type == 'takeaway' ? 'bi-bag' : 'bi-truck') }} mr-2 text-orange-500"></i>
                    {{ str_replace('_', ' ', $transaction->order_type) }}
                </p>
                @if($transaction->order_notes)
                    <div class="mt-3 p-3 bg-yellow-50 rounded-xl border border-yellow-100 text-yellow-700 text-sm">
                        <strong>Notes:</strong> {{ $transaction->order_notes }}
                    </div>
                @endif
            </div>

            <!-- 3. JENIS PEMBAYARAN -->
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Payment Info</p>
                <p class="text-gray-900 font-bold text-lg capitalize flex items-center">
                    <i class="bi bi-wallet2 mr-2 text-blue-500"></i>
                    <!-- Asumsi kolom di tabel Payment bernama 'payment_method' -->
                    {{ $transaction->payment->payment_method ?? 'Cash' }}
                </p>
                
                @if($transaction->payment)
                    <!-- Asumsi kolom di tabel Payment bernama 'payment_status' -->
                    <p class="text-gray-500 mt-1 capitalize text-sm">
                        Status: <span class="{{ $transaction->payment->payment_status == 'paid' ? 'text-green-500' : 'text-red-500' }} font-bold">{{ $transaction->payment->payment_status }}</span>
                    </p>
                @else
                    <p class="text-gray-500 mt-1 text-sm font-bold text-red-500">Unpaid / No Record</p>
                @endif
            </div>
        </div>

        <!-- Daftar Menu -->
        <div class="p-8 border-b border-gray-100">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Order Summary</p>
            
            <div class="space-y-4">
                @forelse($transaction->orderItems as $item)
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500 text-sm">
                                {{ $item->quantity }}x
                            </div>
                            <div>
                                <p class="text-gray-900 font-bold">{{ $item->menu->menu_name ?? 'Item Deleted' }}</p>
                                <p class="text-gray-400 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <p class="text-gray-900 font-bold">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 italic text-sm">No items found for this order.</p>
                @endforelse
            </div>
        </div>

        <!-- Rincian Biaya & Total -->
        <div class="p-8 bg-gray-50/50">
            <div class="space-y-3 max-w-sm ml-auto">
                
                <div class="flex justify-between text-gray-500">
                    <p>Subtotal</p>
                    <p class="font-bold text-gray-900">Rp {{ number_format($transaction->order_subtotal, 0, ',', '.') }}</p>
                </div>
                
                <!-- Tampilkan Jika Ada Voucher -->
                @if($transaction->voucher_id)
                    <div class="flex justify-between text-green-500">
                        <p class="flex items-center">
                            <i class="bi bi-ticket-perforated mr-2"></i> {{ $transaction->voucher->voucher_code ?? 'Voucher' }}
                        </p>
                        <p class="font-bold">- Rp {{ number_format($transaction->order_discount, 0, ',', '.') }}</p>
                    </div>
                @endif
                
                <div class="border-t border-gray-200 my-2 pt-3 flex justify-between items-center">
                    <p class="font-black text-gray-900 text-lg">Total Amount</p>
                    <p class="font-black text-orange-500 text-2xl">Rp {{ number_format($transaction->order_total, 0, ',', '.') }}</p>
                </div>

            </div>
        </div>
        
    </div>
</div>
@endsection

<!-- Script untuk menyulap HTML menjadi Gambar -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        function downloadNota() {
            // Ambil elemen kotak nota
            const notaElement = document.getElementById('nota-card');
            
            // Ubah menjadi canvas gambar (scale: 2 agar gambarnya tidak pecah / HD)
            html2canvas(notaElement, { scale: 2 }).then(canvas => {
                // Buat link download palsu
                const link = document.createElement('a');
                // Beri nama file sesuai nomor order
                link.download = 'Nota-{{ $transaction->order_number ?? $transaction->order_id }}.png';
                // Ubah canvas menjadi URL gambar PNG
                link.href = canvas.toDataURL('image/png');
                // Klik link tersebut secara otomatis
                link.click();
            });
        }
    </script>