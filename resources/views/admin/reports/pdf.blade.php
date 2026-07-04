<!DOCTYPE html>
<html>
<head>
    <title>Qitchen Performance Report</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        
        .section-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        
        /* Tabel Layout untuk Summary Card */
        .summary-table { w-full; margin-bottom: 20px; }
        .summary-box { background-color: #f8f9fa; border: 1px solid #ddd; padding: 15px; text-align: center; }
        .summary-box .title { font-size: 11px; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .summary-box .value { font-size: 18px; font-weight: bold; color: #000; }

        /* Tabel Data Standar */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data-table th, table.data-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table.data-table th { background-color: #f2f2f2; font-size: 12px; text-transform: uppercase; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>QITCHEN RESTAURANT</h1>
        <p>Performance Report: {{ $startDate }} - {{ $endDate }}</p>
    </div>

    <div class="section-title">Key Performance Indicators</div>
    <table width="100%" class="summary-table" cellspacing="10">
        <tr>
            <td width="33%" class="summary-box">
                <div class="title">Total Revenue</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td width="33%" class="summary-box">
                <div class="title">Total Orders</div>
                <div class="value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            </td>
            <td width="33%" class="summary-box">
                <div class="title">Avg. Order Value</div>
                <div class="value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Top Selling Items</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="50%">Menu Item Name</th>
                <th width="20%" class="text-center">Items Sold</th>
                <th width="25%" class="text-right">Revenue Generated</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topSellingItems as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td class="text-center">{{ $item->sold }}x</td>
                    <td class="text-right">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No sales data available.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="width: 100%; display: table;">
        <div style="display: table-cell; width: 48%; padding-right: 2%;">
            <div class="section-title">Revenue by Category</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryData as $cat)
                        <tr>
                            <td>{{ $cat->category_name }}</td>
                            <td class="text-right">Rp {{ number_format($cat->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="display: table-cell; width: 48%; padding-left: 2%;">
            <div class="section-title">Payment Methods</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Method</th>
                        <th class="text-center">Usage Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentMethods as $payment)
                        <tr>
                            <td style="text-transform: capitalize;">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                            <td class="text-center">{{ $payment->count }}x</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer">
        Generated automatically by Qitchen System on {{ \Carbon\Carbon::now()->format('l, j F Y - H:i:s') }}
    </div>

</body>
</html>