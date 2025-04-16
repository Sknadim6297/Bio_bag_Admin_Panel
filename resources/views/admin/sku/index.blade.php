@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-sku.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage SKU/Product</h1>
  <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="mb-0">Total Products: <strong>5</strong></p>
      <a href="{{ route('admin.sku.create') }}"><button class="btn btn-success">
          <i class="fas fa-plus me-2"></i>Add Product
      </button></a>
      <a href="bulk-upload.html"><button class="btn btn-success">
          <i class="fas fa-upload me-2"></i>Bulk Upload
      </button></a>
      <button class="btn btn-success">
          <i class="fas fa-download me-2"></i>Download
      </button>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6 d-flex align-items-center">
      <label for="entriesSelect" class="me-2 mb-0">Show</label>
      <select id="entriesSelect" class="form-select w-auto me-2">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
      </select>
      <span>entries</span>
  </div>
  <div class="col-md-6 text-md-end mt-2 mt-md-0">
      <label for="productSearch" class="me-2 mb-0">Search:</label>
      <input type="text" id="productSearch" class="form-control d-inline-block w-50"
          placeholder="Search by product or category" />
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped">
      <thead class="table-dark">
          <tr>
              <th>SL No.</th>
              <th>Category Name</th>
              <th>SKU</th>
              <th>Product Name</th>
              <th>Last Updated</th>
              <th>Quantity</th>
              <th>Total Price</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody id="productTableBody">
        @foreach($skus as $index => $sku)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sku->category->category_name ?? 'N/A' }}</td>
                <td>{{ $sku->sku_code }}</td>
                <td>{{ $sku->product_name }}</td>
                <td>{{ \Carbon\Carbon::parse($sku->updated_at)->format('Y-m-d H:i:s') }}</td>
                <td>{{ $sku->quantity }} {{ $sku->measurement }}</td>
                <td>{{ number_format($sku->total_price, 2) }}</td>
                <td>
                  <div class="action-buttons">
                    <a href="{{ route('admin.sku.edit', $sku->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                        <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                    </a>
                    <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.sku.destroy', $sku->id) }}" title="Delete">
                      <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                  </a>
                </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    
  </table>
</div>

<!-- Pagination Controls -->
<div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
  <div class="showing-entries">
    Showing <span>1</span> to <span>2</span> of <span>4</span> entries
  </div>
  <div class="pagination-buttons">
    <button class="btn btn-outline-secondary btn-sm disabled" disabled>
      <i class="fas fa-angle-left"></i> Previous
    </button>
    <button class="btn btn-outline-primary btn-sm active">1</button>
    <button class="btn btn-outline-primary btn-sm">2</button>
    <button class="btn btn-outline-secondary btn-sm">
      Next <i class="fas fa-angle-right"></i>
    </button>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
</script>


</script>

@endsection
