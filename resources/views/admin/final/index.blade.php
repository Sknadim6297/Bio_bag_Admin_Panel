@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/final-output.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="dashboard-header">
  <h1>Manage Final Output</h1>
  <a href="{{ route('admin.final-output.create') }}" style="text-decoration: none;">
    <button class="btn btn-primary">
      <i class="fas fa-plus"></i> Add Final Output
    </button>
  </a>
</div>

<!-- Filters Section -->
<div class="action-bar">
  <div class="action-group">
    <div class="filters">
      <div class="date-filter">
        <span>From:</span>
        <input type="date" id="from-date" value="2025-01-01">
        <span>To:</span>
        <input type="date" id="to-date">
      </div>

      <div class="dropdown-filters styled-filters">
        <div class="form-group">
          <label for="customer-id">Customer Name:</label>
          <select id="customer-id" class="form-control">
            <option value="">-- Select Customer --</option>
            @foreach ($customers as $customer)
              <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="micron">Micron:</label>
          <select id="micron" class="form-control">
            <option value="">-- Select Micron --</option>
            @foreach ($micronList as $micron)
              <option value="{{ $micron }}">{{ $micron }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="size">Size:</label>
          <select id="size" class="form-control">
            <option value="">-- Select Size --</option>
            @foreach ($sizeList as $size)
              <option value="{{ $size }}">{{ $size }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>&nbsp;</label>
          <div class="d-flex gap-2">
            <button id="filter-btn" class="btn btn-primary w-100 me-2">
              <i class="fas fa-filter"></i> Filter
            </button>
            <button id="reset-btn" class="btn btn-outline-secondary w-100">
              <i class="fas fa-undo"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="search-filter">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Search...">
  </div>
</div>
<div id="total-final-output" class="summary-box mt-3">
    <h5>Total Final Output: <span id="total-output-value">0</span> kg</h5>
  </div>
<!-- Table Section -->
<table class="output-table" id="output-table">
  <thead>
    <tr>
      <th>SL No</th>
      <th>Date & Time</th>
      <th>Customer Name</th>
      <th>Size</th>
      <th>Micron</th>
      <th>Final Output</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <!-- Data will be loaded here -->
  </tbody>
</table>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Set CSRF token for all AJAX requests
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function fetchData() {
    const search = $('#search-input').val();
    const from_date = $('#from-date').val();
    const to_date = $('#to-date').val();
    const customer_id = $('#customer-id').val();
    const micron = $('#micron').val();
    const size = $('#size').val();

    $.ajax({
      url: "{{ route('admin.final-output.index') }}",
      method: 'GET',
      data: {
        search,
        from_date,
        to_date,
        customer_id,
        micron,
        size
      },
      success: function (res) {
        let rows = '';
        if (res.status && res.data.length > 0) {
          res.data.forEach((item, index) => {
            const customerName = item.customer ? item.customer.customer_name : 'N/A';
            rows += `
              <tr data-id="${item.id}">
                <td>${index + 1}</td>
                <td>${item.final_output_datetime}</td>
                <td>${customerName}</td>
                <td>${item.size}</td>
                <td>${item.micron}</td>
                <td>${item.quantity} kg</td>
                <td>
                  <a href="/admin/final-output/${item.id}/edit" class="btn btn-outline-primary btn-sm me-2" style="font-weight:600; border-radius:5px;">
                    <i class="fas fa-pen"></i> Edit
                  </a>
                  <button class="btn btn-outline-danger btn-sm delete-btn" style="font-weight:600; border-radius:5px;">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </td>
              </tr>
            `;
          });

          $('#total-output-value').text(res.total);
        } else {
          rows = `<tr><td colspan="6" class="text-center text-muted">No data found</td></tr>`;
          $('#total-output-value').text('0');
        }

        $('#output-table tbody').html(rows);
      },
      error: function () {
        alert('Error fetching data!');
      }
    });
  }

  $(document).ready(function () {
    fetchData();

    $('#search-input, #from-date, #to-date').on('change keyup', function () {
      fetchData();
    });

    $('#filter-btn').on('click', function () {
      fetchData();
    });

    // Reset button functionality
    $('#reset-btn').on('click', function(e) {
      e.preventDefault();
      $('#from-date').val('');
      $('#to-date').val('');
      $('#customer-id').val('');
      $('#micron').val('');
      $('#size').val('');
      $('#search-input').val('');
      fetchData();
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
      const id = $(this).closest('tr').data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the record.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: `/admin/final-output/${id}`,
            method: 'POST',
            data: { _method: 'DELETE' },
            success: function(res) {
              if (res.status) {
                toastr.success(res.message);
                fetchData();
              } else {
                toastr.error(res.message || 'Delete failed.');
              }
            },
            error: function() {
              toastr.error('Something went wrong.');
            }
          });
        }
      });
    });
  });
</script>
@endsection