<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Flow Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
        .header { text-align: center; margin-bottom: 16px; }
        .meta { margin-bottom: 12px; }
        .meta div { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
        .small { font-size: 11px; color: #444; }
    </style>
    </head>
<body>
    <div class="header">
        <h2>Smart Warehousing System</h2>
        <h3>Inventory Flow Report</h3>
    </div>

    <div class="meta">
        <div class="small">Generated at: {{ now()->format('Y-m-d H:i:s') }}</div>
        <div class="small">Date From: {{ $filters['from'] ?? 'N/A' }} | Date To: {{ $filters['to'] ?? 'N/A' }}</div>
        <div class="small">Warehouse: {{ $filters['warehouse_id'] ?? 'All' }}</div>
        <div class="small">Total Records: {{ $transactions->count() }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>From Location</th>
                <th>To Location</th>
                <th>Warehouse</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ $t->tra_id }}</td>
                <td>{{ optional($t->tra_transaction_date)->format('Y-m-d H:i') }}</td>
                <td>{{ $t->tra_type }}</td>
                <td>{{ $t->tra_status }}</td>
                <td>{{ $t->tra_quantity }}</td>
                <td>{{ optional($t->item)->item_code }}</td>
                <td>{{ optional($t->item)->item_name }}</td>
                <td>{{ optional(optional($t->item)->category)->cat_name }}</td>
                <td>{{ optional($t->fromLocation)->loc_name }}</td>
                <td>{{ optional($t->toLocation)->loc_name }}</td>
                <td>{{ optional($t->warehouse)->ware_name }}</td>
                <td>{{ $t->tra_reference_id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Flow Report</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        h2 { font-size: 14px; margin: 16px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px; text-align: left; }
        th { background: #eee; }
        .meta { margin-bottom: 12px; }
    </style>
    </head>
<body>
    <h1>Inventory Flow Report</h1>
    <div class="meta">
        <div>Range: {{ $filters['range'] ?? 'custom' }}</div>
        <div>From: {{ $filters['from'] ?? '-' }} | To: {{ $filters['to'] ?? '-' }}</div>
        <div>Generated: {{ now()->format('Y-m-d H:i') }}</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>From</th>
                <th>To</th>
                <th>Warehouse</th>
                <th>Status</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ \Carbon\Carbon::parse($t->tra_transaction_date)->format('Y-m-d H:i') }}</td>
                <td>{{ $t->tra_type }}</td>
                <td>{{ optional($t->item)->item_name }}</td>
                <td>{{ $t->tra_quantity }}</td>
                <td>{{ optional($t->fromLocation)->loc_name }}</td>
                <td>{{ optional($t->toLocation)->loc_name }}</td>
                <td>{{ optional($t->warehouse)->ware_name }}</td>
                <td>{{ $t->tra_status }}</td>
                <td>{{ $t->tra_reference_id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>