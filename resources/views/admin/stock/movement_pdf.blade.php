<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Stock Movement Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .positive { color: green; }
        .negative { color: red; }
        .total-row { font-weight: bold; background-color: #f0f0f0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Stock Movement Report</h2>
    </div>

    <div class="company-info">
        <p><strong>Report No:</strong> {{ $reportNumber }}</p>
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Product:</strong> {{ $stock->product_name }}</p>
        <p><strong>Current Stock:</strong> {{ number_format($available_stock) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ date('d/m/Y H:i', strtotime($transaction['date'])) }}</td>
                    <td>{{ $transaction['type'] }}</td>
                    <td class="{{ $transaction['quantity'] > 0 ? 'positive' : 'negative' }}">
                        {{ $transaction['quantity'] > 0 ? '+' : '' }}{{ number_format($transaction['quantity']) }}
                    </td>
                    <td>{{ number_format($transaction['balance']) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Current Stock Balance:</td>
                <td>{{ number_format($available_stock) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Generated on: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>