@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/final-output.css') }}">
@endsection

@section('content')

<div class="dashboard-header">
  <h1>Manage Final Output</h1>
  <a href="{{ route('admin.final-output.create') }}">
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
          <button id="filter-btn" class="btn btn-primary w-100">
            <i class="fas fa-filter"></i> Filter
          </button>
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
              <tr>
                <td>${index + 1}</td>
                <td>${item.final_output_datetime}</td>
                <td>${customerName}</td>
                <td>${item.size}</td>
                <td>${item.micron}</td>
                <td>${item.quantity} kg</td>
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
  });
</script>
@endsection
