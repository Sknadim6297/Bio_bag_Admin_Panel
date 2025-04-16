@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-consumption.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Consumption</h1>
  <a href="{{ route('admin.consumption.create') }}"><button class="btn btn-primary">
    <i class="fas fa-plus"></i> Add Consumption
  </button></a>
</div>

<!-- Filter and Search Section -->
<div class="filter-container">
  <div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search consumption...">
  </div>
  <div class="date-filter">
    <label>From:</label>
    <input type="date" id="from-date">
    <label>To:</label>
    <input type="date" id="to-date">
    <button class="btn btn-secondary">
      <i class="fas fa-filter"></i> Filter
    </button>
  </div>
</div>

<!-- Consumption Table -->
<table class="consumption-table">
  <thead>
    <tr>
      <th>SL No</th>
      <th>Date & Time</th>
      <th>Product</th>
      <th>Quantity</th>
      <th>Total Consumption</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($consumptions as $consumption)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $consumption->date }} {{ $consumption->time }}</td>
        <td>{{ $consumption->sku->product_name ?? 'N/A' }}</td>
        <td>{{ $consumption->quantity }}</td>
        <td>{{ $consumption->quantity * $consumption->unit_price }} kg</td>
        <td>
          <div class="action-buttons">
            <a href="{{ route('admin.consumption.edit', $consumption->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                <i class="fas fa-edit"></i><span class="action-text">Edit</span>
            </a>
            <a href="javascript:void(0);" class="action-btn delete-btn delete-item" data-url="{{ route('admin.consumption.destroy', $consumption->id) }}" title="Delete">
              <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
          </a>
        </div>
        </td>
      </tr>
    @endforeach
  </tbody>
  
</table>

<!-- Pagination -->
<div class="pagination">
  <button class="page-btn"><i class="fas fa-angle-double-left"></i></button>
  <button class="page-btn"><i class="fas fa-angle-left"></i></button>
  <button class="page-btn active">1</button>
  <button class="page-btn">2</button>
  <button class="page-btn">3</button>
  <button class="page-btn"><i class="fas fa-angle-right"></i></button>
  <button class="page-btn"><i class="fas fa-angle-double-right"></i></button>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

</script>
@endsection
