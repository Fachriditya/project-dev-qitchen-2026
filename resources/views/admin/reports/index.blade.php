@extends('layouts.master')
@section('title', 'Performance Reports')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Reports</h1>
            <p class="text-gray-500 font-medium mt-1">Analyse performance and export data</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm text-sm font-bold text-gray-700">
                <!-- TANGGAL DINAMIS -->
                <span>{{ $startDate }}</span>
                <span class="text-gray-300">|</span>
                <span>{{ $endDate }}</span>
            </div>
            <button>
                <a href="{{ route('admin.reports.export-pdf') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-orange-600 transition shadow-md inline-block">
                    <i class="bi bi-file-earmark-pdf-fill mr-2"></i> Export PDF
                </a>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <p class="text-sm font-medium text-gray-400">Total revenue</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            
            <p class="text-xs font-bold mt-2 flex items-center {{ $metrics['revenue']['is_up'] ? 'text-green-500' : 'text-red-500' }}">
                <i class="bi {{ $metrics['revenue']['is_up'] ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }} mr-1"></i> 
                {{ abs($metrics['revenue']['growth']) }}% 
                <span class="text-gray-400 font-medium ml-1">vs last week</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <p class="text-sm font-medium text-gray-400">Total orders</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">{{ number_format($totalOrders, 0, ',', '.') }}</h3>
            
            <p class="text-xs font-bold mt-2 flex items-center {{ $metrics['orders']['is_up'] ? 'text-green-500' : 'text-red-500' }}">
                <i class="bi {{ $metrics['orders']['is_up'] ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }} mr-1"></i> 
                {{ abs($metrics['orders']['growth']) }}% 
                <span class="text-gray-400 font-medium ml-1">vs last week</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <p class="text-sm font-medium text-gray-400">Avg. order value</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</h3>
            
            <p class="text-xs font-bold mt-2 flex items-center {{ $metrics['avg']['is_up'] ? 'text-green-500' : 'text-red-500' }}">
                <i class="bi {{ $metrics['avg']['is_up'] ? 'bi-caret-up-fill' : 'bi-caret-down-fill' }} mr-1"></i> 
                {{ abs($metrics['avg']['growth']) }}% 
                <span class="text-gray-400 font-medium ml-1">vs last week</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <p class="text-sm font-medium text-gray-400">Avg. prep time</p>
            <h3 class="text-2xl font-black text-gray-900 mt-2">{{ $avgPrepTime }} min</h3>
            <p class="text-xs font-bold text-gray-400 mt-2 flex items-center">
                <i class="bi bi-dash-circle-fill mr-1"></i> 0% <span class="text-gray-400 font-medium ml-1">no data</span>
            </p>
        </div>

    </div>

    <!-- BARIS GRAFIK PERTAMA (SESUAI DESAIN AWAL) -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
        <!-- Sales Over Time -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] lg:col-span-3">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Sales over time</h3>
            <div class="h-64 relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <!-- Revenue by Category -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Revenue by category</h3>
            <div class="space-y-5">
                @foreach($categoryReport as $report)
                    <div>
                        <div class="flex justify-between text-sm font-bold text-gray-900 mb-2">
                            <span>{{ $report['name'] }}</span>
                            <span class="text-gray-400">{{ $report['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                            <div class="{{ $report['color'] }} h-3 rounded-full transition-all duration-500" style="width: {{ $report['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- BARIS GRAFIK KEDUA (TAMBAHAN REAL WORLD METRICS) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Top Selling Items List (Tabel Mini) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)] lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Top Selling Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="pb-3">Menu Item</th>
                            <th class="pb-3 text-center">Items Sold</th>
                            <th class="pb-3 text-right">Revenue Generated</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($topSellingItems as $item)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-4 font-bold text-gray-900">{{ $item['name'] }}</td>
                                <td class="py-4 font-medium text-gray-500 text-center">
                                    <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold">{{ $item['sold'] }}x</span>
                                </td>
                                <td class="py-4 font-bold text-gray-900 text-right">Rp {{ number_format($item['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods (Doughnut Chart) -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.05)]">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Payment Methods</h3>
            <div class="h-48 relative flex justify-center">
                <canvas id="paymentChart"></canvas>
            </div>
            <!-- Custom Legend -->
            <div class="mt-6 grid grid-cols-2 gap-3">
                @foreach($paymentMethods['labels'] as $index => $label)
                    <div class="flex items-center gap-2">
                        <!-- Bulatan warna (orange gradasi manual sesuai index) -->
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ ['#f97316', '#fb923c', '#fdba74', '#fed7aa'][$index] }}"></span>
                        <span class="text-xs font-bold text-gray-600">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>

<!-- SCRIPT CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        // 1. CHART: Sales Over Time (Bar Chart)
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'bar',
            data: {
                labels: @json($salesData['labels']),
                datasets: [{
                    label: 'Sales count',
                    data: @json($salesData['data']),
                    backgroundColor: '#f97316', 
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 32 
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { weight: 'bold' } }
                    },
                    y: {
                        grid: { borderDash: [5, 5], color: '#f3f4f6' },
                        ticks: { color: '#9ca3af', font: { weight: 'bold' }, stepSize: 2 },
                        min: 0, max: 8
                    }
                }
            }
        });

        // 2. CHART: Payment Methods (Doughnut Chart)
        const ctxPayment = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctxPayment, {
            type: 'doughnut',
            data: {
                labels: @json($paymentMethods['labels']),
                datasets: [{
                    data: @json($paymentMethods['data']),
                    backgroundColor: ['#f97316', '#fb923c', '#fdba74', '#fed7aa'], // Palette Orange
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Bikin donatnya agak tipis elegan
                plugins: {
                    legend: { display: false } // Legend kita buat custom HTML di bawah canvas
                }
            }
        });

    });
</script>
@endsection