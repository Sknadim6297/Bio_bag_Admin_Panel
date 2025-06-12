@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />

@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Category</h1>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0">Total Category: <strong>{{ $totalCategories }}</strong></p>

    <a href="{{route('admin.category.create')}}"><button class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add Category
      </button></a>
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
      <label for="vendorSearch" class="me-2 mb-0">Search:</label>
      <input type="text" id="vendorSearch" class="form-control d-inline-block w-50"
  placeholder="Search by name or code" />

    </div>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Sl No</th>
        <th>Category Name</th>
        <th>Category Code</th>
         <th>Category Description</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="vendorTableBody">
      @forelse($categories as $index => $category)
      <tr>
        <td>{{ $categories->firstItem() + $index }}</td>
          <td>{{ $category->category_name }}</td>
          <td>{{ $category->category_code }}</td>
          <td>{{ $category->description }}</td>
          <td>
              <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                  {{ ucfirst($category->status) }}
              </span>
          </td>
          
          <td>
              <div class="action-buttons">
                  <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                      <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.category.destroy', $category->id) }}" title="Delete">
                    <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                </a>
              </div>
          </td>
      </tr>
      @empty
      <tr><td colspan="8" class="text-center">No vendors found.</td></tr>
      @endforelse
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
      <i class="fas fa-angle-left"></i> Prev
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
  $(document).ready(function () {
    $('#vendorSearch').on('keyup', function () {
      let searchTerm = $(this).val();
  
      $.ajax({
        url: "{{ route('admin.category.search') }}", 
        method: 'GET',
        data: {
          search: searchTerm
        },
        success: function (response) {
          let rows = '';
  
          if (response.data && response.data.length > 0) {
            response.data.forEach(function (category, index) {
              rows += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${category.category_name}</td>
                  <td>${category.category_code}</td>
                  <td>${category.description}</td>
                  <td>
                    <span class="badge ${category.status == 'active' ? 'bg-success' : 'bg-secondary'}">
                      ${category.status.charAt(0).toUpperCase() + category.status.slice(1)}
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="${category.edit_url}" class="btn btn-sm btn-primary me-1" title="Edit">
                        <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                      </a>
                      <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="${category.delete_url}" title="Delete">
                        <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                      </a>
                    </div>
                  </td>
                </tr>
              `;
            });
          } else {
            rows = `<tr><td colspan="6" class="text-center text-muted">No data found</td></tr>`;
          }
  
          $('#vendorTableBody').html(rows);
        }
      });
    });
  });
  </script>
  


@endsection
