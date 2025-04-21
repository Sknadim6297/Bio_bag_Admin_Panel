@extends('layouts.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/manage-customer.css') }}">
@endsection

@section('content')
  <div class="dashboard-header">
    <h1>Manage Customer</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="mb-0">Total Customer: <strong>{{ $customers->count() }}</strong></p>
      <a href="{{ route('admin.customer.create') }}">
        <button class="btn btn-success">
          <i class="fas fa-plus me-2"></i>Add Customer
        </button>
      </a>
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
        <label for="customerSearch" class="me-2 mb-0">Search:</label>
        <input
          type="text"
          id="customerSearch"
          class="form-control d-inline-block w-50"
          placeholder="Search by name or code"
        />
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Sl No</th>
          <th>Customer Name</th>
          <th>Customer Code</th>
          <th>Mobile Number</th>
          <th>Payment Term</th>
          <th>Address</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="customerTableBody">
        @foreach ($customers as $index => $customer)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $customer->customer_name }}</td>
            <td>{{ $customer->customer_code }}</td>
            <td>{{ $customer->mobile_number }}</td>
            <td>{{ $customer->payment_terms }}</td>
            <td>{{ $customer->address }}</td>
            <td>
              <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($customer->status) }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                  <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.customer.destroy', $customer->id) }}" title="Delete">
                  <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                </a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="pagination">
    {{ $customers->links() }}
  </div>

  <div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
    <div class="showing-entries">
      Showing <span>{{ $customers->firstItem() }}</span> to <span>{{ $customers->lastItem() }}</span> of <span>{{ $customers->total() }}</span> entries
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
  $(document).ready(function () {
  
    $('#customerSearch').on('keyup', function () {
      let searchTerm = $(this).val();

      $.ajax({
        url: "{{ route('admin.customer.search') }}",
        type: "GET",
        data: { search: searchTerm },
        success: function (response) {
          $('#customerTableBody').html(response.customers);
          $('.pagination').html(response.pagination);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
        }
      });
    });
    $(document).on('click', '.pagination a', function (e) {
      e.preventDefault();
      let url = $(this).attr('href');

      $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
          $('#customerTableBody').html(response.customers);
          $('.pagination').html(response.pagination);
        }
      });
    });
  });
</script>
@endsection
