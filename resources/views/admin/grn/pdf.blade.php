<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>GRN Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .document-info {
            margin-bottom: 20px;
        }
        .vendor-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('admin/images/logo.png') }}" alt="Company Logo" class="logo">
        <h2>Goods Received Note</h2>
    </div>

    <div class="document-info">
        <table>
            <tr>
                <td><strong>GRN Number:</strong> {{ $reportNumber }}</td>
                <td><strong>Date:</strong> {{ $date }}</td>
            </tr>
            <tr>
                <td><strong>PO Number:</strong> {{ $po->po_number }}</td>
                <td><strong>PO Date:</strong> {{ date('d/m/Y', strtotime($po->po_date)) }}</td>
            </tr>
        </table>
    </div>

    <div class="vendor-info">
        <h3>Vendor Details</h3>
        <p><strong>Name:</strong> {{ $po->vendor->vendor_name }}</p>
        <p><strong>Address:</strong> {{ $po->vendor->address }}</p>
        <p><strong>Contact:</strong> {{ $po->vendor->contact_number }}</p>
    </div>

    <div class="delivery-info">
        <h3>Delivery Details</h3>
        <p><strong>Deliver To:</strong> {{ $po->deliver_to_location }}</p>
        <p><strong>Address:</strong> {{ $po->deliver_address }}</p>
        <p><strong>Expected Delivery:</strong> {{ date('d/m/Y', strtotime($po->expected_delivery)) }}</p>
    </div>

    <h3>Product Details</h3>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($po->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->description }}</td>
                <td>{{ number_format($item->quantity) }} {{ $item->measurement }}</td>
                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                <td>₹{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table style="width: 300px; margin-left: auto;">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>₹{{ number_format($po->subtotal, 2) }}</td>
            </tr>
            @if($po->cgst)
            <tr>
                <td><strong>CGST:</strong></td>
                <td>₹{{ number_format($po->cgst, 2) }}</td>
            </tr>
            @endif
            @if($po->sgst)
            <tr>
                <td><strong>SGST:</strong></td>
                <td>₹{{ number_format($po->sgst, 2) }}</td>
            </tr>
            @endif
            @if($po->igst)
            <tr>
                <td><strong>IGST:</strong></td>
                <td>₹{{ number_format($po->igst, 2) }}</td>
            </tr>
            @endif
            @if($po->cess)
            <tr>
                <td><strong>CESS:</strong></td>
                <td>₹{{ number_format($po->cess, 2) }}</td>
            </tr>
            @endif
            @if($po->discount)
            <tr>
                <td><strong>Discount:</strong></td>
                <td>₹{{ number_format($po->discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Total:</strong></td>
                <td>₹{{ number_format($po->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($po->terms || $po->notes)
    <div class="terms-section">
        @if($po->terms)
        <p><strong>Terms & Conditions:</strong><br>{{ $po->terms }}</p>
        @endif
        @if($po->notes)
        <p><strong>Notes:</strong><br>{{ $po->notes }}</p>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>This is a computer generated document</p>
    </div>
</body>
</html>