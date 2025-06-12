@extends('layouts.layout')
 
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<style>
    .invoice-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        background-color: #fff;
    }
    
    .invoice-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .invoice-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }
    
    .invoice-details {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .invoice-row {
        display: flex;
        margin-bottom: 10px;
    }
    
    .invoice-label {
        font-weight: bold;
        width: 150px;
    }
    
    .invoice-value {
        flex: 1;
    }
    
    .invoice-items {
        margin-bottom: 20px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    
    .table th, .table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }
    
    .table thead th {
        background-color: #4a6fdc;
        color: white;
        font-weight: bold;
        text-align: left;
    }
    
    .invoice-summary {
        margin-top: 20px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .summary-label {
        font-weight: bold;
    }
    
    .summary-value {
        text-align: right;
        min-width: 100px;
    }
    
    .summary-total {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
    }
    
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
    
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    /* Added print button styles */
    .btn-primary {
        color: #fff;
        background-color: #4a6fdc;
        border-color: #4a6fdc;
    }
    
    .btn-primary:hover {
        background-color: #375bc8;
        border-color: #3356b9;
    }

    /* Enhanced styling for invoice */
    .company-header {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #4a6fdc;
    }
    
    .company-name {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #4a6fdc;
    }
    
    .company-address {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
    }
    
    /* Customer and company info in two columns */
    .billing-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .billing-info {
        width: 48%;
    }
    
    .billing-title {
        font-weight: bold;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 5px;
        margin-bottom: 10px;
    }

    @media print {
        .dashboard-header, .header-actions, .btn {
            display: none;
        }
        
        body {
            font-size: 12px;
        }
        
        .invoice-container {
            max-width: 100%;
            margin: 0;
            padding: 15px;
            box-shadow: none;
        }
        
        .table thead th {
            background-color: #f1f1f1 !important;
            color: #333 !important;
            -webkit-print-color-adjust: exact;
        }
        
        @page {
            size: A4;
            margin: 1cm;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Invoice Details</h1>
    <div class="header-actions">
        <a href="{{ route('admin.invoice.pdf', $invoice->id) }}" class="btn btn-primary" target="_blank">
            <i class="fas fa-file-pdf"></i> Download PDF
        </a>
        <a href="javascript:window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print
        </a>
        <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
    </div>
</div>

<div class="invoice-container">
    <div class="company-header">
        <div class="company-name">GREENWARE REVOLUTION</div>
        <div class="company-address">42E, CHAULPATTY ROAD</div>
        <div class="company-address">KOLKATA- 700010</div>
        <div class="company-address">GSTIN/UIN: 19ADNPG4007H1ZM</div>
        <div class="company-address">State Name: West Bengal, Code: 19</div>
        <div class="company-address">Contact: 6290515957</div>
        <div class="company-address">E-Mail: info@greenware.co.in</div>
        <div class="company-address">www.greenware.co.in</div>
    </div>

    <div class="invoice-header">
        <div class="invoice-title">
            Invoice #{{ $invoice->invoice_number }}
        </div>
        <div class="invoice-date">
            Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
        </div>
    </div>
    
    <div class="billing-section">
        <div class="billing-info">
            <div class="billing-title">Bill To:</div>
            <div>{{ $invoice->customer->customer_name ?? 'N/A' }}</div>
            <div>{{ $invoice->customer->address ?? 'N/A' }}</div>
            <div>GST: {{ $invoice->customer->gstin ?? 'N/A' }}</div>
        </div>
        <div class="billing-info">
            <div class="billing-title">Payment Information:</div>
            <div>Payment Mode: Bank Transfer</div>
            <div>Due Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->addDays(30)->format('d-m-Y') }}</div>
        </div>
    </div>
    
    <div class="invoice-items">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 35%">Description</th>
                    <th>HSN</th>
                    <th>Micron</th>
                    <th>Size</th>
                    <th>Qty (kg)</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->description ?? 'Product' }}</td>
                    <td>{{ $invoice->hsn ?? 'N/A' }}</td>
                    <td>{{ $invoice->micron ?? 'N/A' }}</td>
                    <td>{{ $invoice->size ?? 'N/A' }}</td>
                    <td>{{ number_format($invoice->quantity, 2) }}</td>
                    <td>{{ number_format($invoice->price_per_kg, 2) }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="invoice-summary">
        <div class="summary-row">
            <div class="summary-label">Subtotal:</div>
            <div class="summary-value">₹{{ number_format($invoice->total_amount, 2) }}</div>
        </div>
        
        @if($invoice->cgst > 0)
        <div class="summary-row">
            <div class="summary-label">CGST ({{ $invoice->cgst }}%):</div>
            <div class="summary-value">₹{{ number_format(($invoice->total_amount * $invoice->cgst / 100), 2) }}</div>
        </div>
        @endif
        
        @if($invoice->sgst > 0)
        <div class="summary-row">
            <div class="summary-label">SGST ({{ $invoice->sgst }}%):</div>
            <div class="summary-value">₹{{ number_format(($invoice->total_amount * $invoice->sgst / 100), 2) }}</div>
        </div>
        @endif
        
        @if($invoice->igst > 0)
        <div class="summary-row">
            <div class="summary-label">IGST ({{ $invoice->igst }}%):</div>
            <div class="summary-value">₹{{ number_format(($invoice->total_amount * $invoice->igst / 100), 2) }}</div>
        </div>
        @endif
        
        <div class="summary-row">
            <div class="summary-label">Tax Amount:</div>
            <div class="summary-value">₹{{ number_format($invoice->tax_amount, 2) }}</div>
        </div>
        
        <div class="summary-row summary-total">
            <div class="summary-label">Total Amount:</div>
            <div class="summary-value">₹{{ number_format($invoice->final_price, 2) }}</div>
        </div>
    </div>
    
    <div style="margin-top: 40px; font-size: 14px;">
        <div style="margin-bottom: 10px;"><strong>Terms & Conditions:</strong></div>
        <ol style="padding-left: 20px;">
            <li>Payment due within 30 days from invoice date.</li>
            <li>Please make payment by bank transfer to the account details provided.</li>
            <li>Goods once sold cannot be returned or exchanged.</li>
            <li>This is a computer-generated invoice and does not require a signature.</li>
        </ol>
    </div>
    
    <div style="margin-top: 40px; text-align: right;">
        <div style="margin-bottom: 30px;">For GREENWARE REVOLUTION</div>
        <div style="margin-top: 10px;">Authorized Signatory</div>
    </div>
</div>
@endsection