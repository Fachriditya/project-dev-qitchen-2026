@extends('layouts.master')
@section('title', 'Delivery Waybill')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header Controls -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.deliveries.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Delivery Waybill</h1>
        </div>
        
        <!-- Tombol Download Resi -->
        <button onclick="downloadWaybill()" class="bg-slate-900 text-white px-6 py-2.5 rounded-full font-bold hover:bg-slate-800 transition shadow-md flex items-center">
            <i class="bi bi-printer-fill mr-2"></i> Print / Download
        </button>
    </div>

    <!-- AREA RESI PAKET (DASHED BORDER) -->
    <div id="waybill-card" class="max-w-2xl bg-white border-2 border-dashed border-gray-400 p-8 rounded-xl relative mx-auto">
        
        <!-- Waybill Header -->
        <div class="flex justify-between items-center border-b-2 border-black pb-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-black text-white rounded-lg flex items-center justify-center font-black text-2xl">
                    Q
                </div>
                <div>
                    <h2 class="text-2xl font-black text-black leading-none">QITCHEN</h2>
                    <p class="text-xs font-bold tracking-widest text-gray-500 uppercase mt-1">Express Delivery</p>
                </div>
            </div>
            <div class="text-right">
                <!-- Barcode Tiruan -->
                <i class="bi bi-upc-scan text-5xl text-black"></i>
                <p class="text-xs font-bold font-mono text-black mt-1">{{ $delivery->order->order_number ?? '#Q-' . str_pad($delivery->order_id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- Alamat Pengirim & Penerima -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            
            <!-- PENGIRIM (SENDER) -->
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-200 pb-1">From / Pengirim</p>
                <p class="text-black font-black text-lg">Qitchen Central</p>
                <p class="text-sm font-medium text-black mt-1">0812-3456-7890</p>
                <p class="text-xs text-black mt-1">Jl. Sudirman No. 123,<br>Kawasan CBD, Jakarta Pusat</p>
            </div>

            <!-- PENERIMA (RECIPIENT) -->
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2 border-b border-gray-200 pb-1">To / Penerima</p>
                <p class="text-black font-black text-xl">{{ $delivery->recipient_name }}</p>
                <p class="text-sm font-bold text-black mt-1 flex items-center">
                    <i class="bi bi-telephone-fill mr-2 text-xs"></i> {{ $delivery->recipient_phone ?? '-' }}
                </p>
                <p class="text-sm text-black mt-2 bg-gray-50 p-3 rounded-lg border border-gray-200 leading-relaxed font-medium">
                    {{ $delivery->delivery_address }}
                </p>
            </div>

        </div>

        <!-- Detail Kurir & Status -->
        <div class="border-t-2 border-black pt-4 mb-6 grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Assigned Courier</p>
                <p class="text-black font-black flex items-center">
                    <i class="bi bi-person-badge-fill mr-2"></i>
                    {{ $delivery->courier->name ?? 'Waiting for Courier...' }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Delivery Status</p>
                <span class="inline-block border-2 border-black px-3 py-1 text-xs font-black uppercase tracking-wider text-black bg-gray-100">
                    {{ str_replace('_', ' ', $delivery->delivery_status) }}
                </span>
            </div>
        </div>

        <!-- Package Contents (Opsional, info singkat order) -->
        <div class="border-t border-gray-200 pt-4">
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Package Contents (Items)</p>
            <p class="text-sm text-black font-medium">
                @if($delivery->order && $delivery->order->orderItems)
                    {{ $delivery->order->orderItems->sum('quantity') }} items included in this package.
                    (Order Ref: {{ $delivery->order->order_number ?? $delivery->order_id }})
                @else
                    Details not available.
                @endif
            </p>
            <p class="text-xs text-gray-400 italic mt-4 text-center">
                * Please check the items upon receiving. Thank you for choosing Qitchen. *
            </p>
        </div>

    </div>
</div>

<!-- Script untuk merubah HTML menjadi Gambar (Sama seperti Nota) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    function downloadWaybill() {
        const waybillElement = document.getElementById('waybill-card');
        html2canvas(waybillElement, { scale: 2 }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'Resi-{{ $delivery->order->order_number ?? $delivery->order_id }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>
@endsection