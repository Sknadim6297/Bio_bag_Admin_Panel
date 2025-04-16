@extends('layouts.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/manage-purchase.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Purchase Order</h1>
  <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.grn.create') }}'">
    <i class="fas fa-plus"></i> Add Purchase Order
  </button>
</div>

<!-- Action Bar with Filters -->
<div class="action-bar">
  <div class="action-group">
    <div class="entries-filter">
      <span>Show</span>
      <select id="entries-select">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <span>entries</span>
    </div>
    <div class="date-filter">
      <span>From:</span>
      <input type="date" id="from-date" value="2025-01-01">
      <span>To:</span>
      <input type="date" id="to-date">
    </div>
  </div>
  <div class="search-filter">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Search...">
  </div>
</div>

<!-- Purchase Order Table -->
<div style="overflow-x: auto;">
  <table class="purchase-table" id="purchase-table">
    <thead>
      <tr>
        <th>Sl. No.</th>
        <th>Date</th>
        <th>Purchase Order#</th>
        <th>Vendor</th>
        <th>Deliver To</th>
        <th>Total Price</th>
        <th>Payment Terms</th>
        <th>Expected Delivery</th>
        <th>Terms & Condition</th>
        <th>Customer Notes</th>
        <th>Reference#</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach($purchaseOrders as $key => $po)
      <tr data-date="{{ $po->po_date }}" data-po="{{ $po->po_number }}">
        <td>{{ $key + 1 }}</td>
        <td>{{ $po->po_date }}</td>
        <td>{{ $po->po_number }}</td>
        <td>{{ $po->vendor->vendor_name ?? '-' }}</td>
        <td>{{ $po->deliver_to_location }}</td>
        <td>${{ number_format($po->total, 2) }}</td>
        <td>{{ $po->payment_terms }}</td>
        <td>{{ $po->expected_delivery }}</td>
        <td>{{ $po->terms }}</td>
        <td>{{ $po->notes }}</td>
        <td>{{ $po->reference }}</td>
        <td>
          <div class="action-buttons">
            <a href="{{ route('admin.grn.edit', $po->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                <i class="fas fa-edit"></i><span class="action-text">Edit</span>
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.grn.destroy', $po->id) }}" title="Delete">
              <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
          </a>
        </div>
      </tr>
      @endforeach
    </tbody>
</table>

</div>

<!-- Pagination -->
<div class="pagination">
  <button class="pagination-btn" id="prev-page">&laquo;</button>
  <button class="pagination-btn active">1</button>
  <button class="pagination-btn">2</button>
  <button class="pagination-btn">3</button>
  <button class="pagination-btn" id="next-page">&raquo;</button>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
</script>
@endsection
