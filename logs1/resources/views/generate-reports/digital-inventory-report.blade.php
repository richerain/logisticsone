<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Digital Inventory Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 8px 0; }
        .meta { color: #555; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #333; color: #fff; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h2>Digital Inventory Report</h2>
    <div class="meta">
        <div>Generated: {{ $generated_at ?? now() }}</div>
        <div>Range: {{ $from ?? 'N/A' }} to {{ $to ?? 'N/A' }}</div>
    </div>
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
                <td class="right">{{ number_format($i->item_unit_price ?? 0, 2) }}</td>
                <td class="right">{{ number_format(($i->item_unit_price ?? 0) * ($i->item_current_stock ?? 0), 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;color:#666;">No data</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>