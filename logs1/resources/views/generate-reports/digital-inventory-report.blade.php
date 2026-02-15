<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Digital Inventory Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        .header { display:flex; align-items:center; justify-content:space-between; border-bottom:2px solid #222; padding-bottom:8px; margin-bottom:12px; }
        .brand { font-size: 16px; font-weight: bold; }
        .subtitle { color:#555; font-size:12px; }
        .section-title { font-size:14px; font-weight:bold; margin:14px 0 6px 0; border-bottom:1px solid #eee; padding-bottom:4px; }
        .grid { display:flex; gap:16px; }
        .card { flex:1; border:1px solid #eee; border-radius:6px; padding:10px; }
        .metric { font-size: 20px; font-weight: 800; }
        .muted { color:#666; font-size:11px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #333; color: #fff; text-align: left; }
        .right { text-align: right; }
        .small { font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">{{ config('app.name', 'LogisticsOne') }}</div>
            <div class="subtitle">Smart Warehousing System</div>
        </div>
        <div class="right small">
            <div><strong>Digital Inventory Report</strong></div>
            <div>Generated: {{ $generated_at ?? now() }}</div>
            <div>Range: {{ $from ?? 'N/A' }} to {{ $to ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="section-title">Executive Summary</div>
    <div class="grid">
        <div class="card">
            <div class="muted">Total Items</div>
            <div class="metric">{{ number_format($summary['total_items'] ?? 0) }}</div>
        </div>
        <div class="card">
            <div class="muted">Total Inventory Value</div>
            <div class="metric">₱ {{ number_format(($summary['total_value'] ?? 0), 2) }}</div>
        </div>
        <div class="card">
            <div class="muted">Low Stock</div>
            <div class="metric">{{ number_format($summary['low_stock_items'] ?? 0) }}</div>
        </div>
        <div class="card">
            <div class="muted">Out of Stock</div>
            <div class="metric">{{ number_format($summary['out_of_stock_items'] ?? 0) }}</div>
        </div>
    </div>

    @php
        $byCategory = [];
        foreach (($items ?? []) as $it) {
            $cat = optional($it->category)->cat_name ?: 'Uncategorized';
            if (!isset($byCategory[$cat])) {
                $byCategory[$cat] = ['count' => 0, 'value' => 0];
            }
            $byCategory[$cat]['count'] += 1;
            $byCategory[$cat]['value'] += ((float)$it->item_unit_price) * (int)($it->item_current_stock ?? 0);
        }
    @endphp

    <div class="section-title">Inventory by Category</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th class="right">Items</th>
                <th class="right">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse($byCategory as $cat => $agg)
            <tr>
                <td>{{ $cat }}</td>
                <td class="right">{{ number_format($agg['count']) }}</td>
                <td class="right">₱ {{ number_format($agg['value'], 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="right">No data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Detailed Appendix</div>
    <table>
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item</th>
                <th>Category</th>
                <th>Stored From</th>
                <th class="right">Current</th>
                <th class="right">Max</th>
                <th class="right">Unit Price</th>
                <th class="right">Total Value</th>
            </tr>
        </thead>
        <tbody>
        @forelse(($items ?? []) as $i)
            <tr>
                <td>{{ $i->item_code }}</td>
                <td>{{ $i->item_name }}</td>
                <td>{{ optional($i->category)->cat_name }}</td>
                <td>{{ $i->item_stored_from }}</td>
                <td class="right">{{ number_format($i->item_current_stock ?? 0) }}</td>
                <td class="right">{{ number_format($i->item_max_stock ?? 0) }}</td>
                <td class="right">{{ number_format((float) $i->item_unit_price, 2) }}</td>
                <td class="right">{{ number_format(((float) $i->item_unit_price) * (int) ($i->item_current_stock ?? 0), 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="8" class="right">No data</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
