@extends('layouts.layout')
 
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<style>
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
        color: white;
        font-weight: bold;
        text-align: left;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
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
   
    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    .action-btns {
        display: flex;
        gap: 5px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Invoice Management</h1>
    <div class="header-actions">
        <a href="{{ route('admin.invoice.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Invoice
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>HSN</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Price/kg</th>
                <th>Tax</th>
                <th>Final Price</th>
                <th>Date</th>
                <th>Download invoice</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->customer->customer_name ?? 'N/A' }}</td>
                <td>{{ $invoice->hsn ?? 'N/A' }}</td>
                <td>{{ $invoice->description ?? 'N/A' }}</td>
                <td>{{ number_format($invoice->quantity, 2) }}</td>
                <td>{{ number_format($invoice->price_per_kg, 2) }}</td>
                <td>
                    @if($invoice->cgst > 0)
                        CGST: {{ $invoice->cgst }}%<br>
                    @endif
                    @if($invoice->sgst > 0)
                        SGST: {{ $invoice->sgst }}%<br>
                    @endif
                    @if($invoice->igst > 0)
                        IGST: {{ $invoice->igst }}%<br>
                    @endif
                    Total: {{ number_format($invoice->tax_amount, 2) }}
                </td>
                <td>{{ number_format($invoice->final_price, 2) }}</td>
                <td>{{ $invoice->created_at->format('d-m-Y') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.invoice.show', $invoice->id) }}" class="btn btn-sm btn-info" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.invoice.download', $invoice->id) }}" class="btn btn-sm btn-success" title="Download PDF">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">No invoices found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection