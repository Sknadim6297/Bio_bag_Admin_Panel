@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-production.css') }}">
@endsection

@section('content')
<div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center;">
  <h1>Manage Production</h1>
  <a href="{{ route('admin.production.create') }}" class="btn-add">
    <i class="fas fa-plus"></i> Add Production
  </a>  
</div>

<!-- Add Production Button -->
<div class="action-bar">
  

  <!-- Filters -->
<div class="filter-container">
  <!-- Date Filter -->
  <div class="filter-group">
      <label>From:</label>
      <input type="date" id="from-date">
      <label>To:</label>
      <input type="date" id="to-date">
  </div>

  <div class="filter-group">
      <label>Search:</label>
      <input type="text" id="search" placeholder="Search by customer name">
  </div>

  <div class="filter-group">
      <label>Customer:</label>
      <select id="customer-id">
          <option value="">-- Select Customer --</option>
          @foreach ($customers as $customer)
              <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
          @endforeach
      </select>

      <label>Micron:</label>
      <select name="micron" id="micron">
          <option value="">-- Select Micron --</option>
          @foreach ($micronList as $micron)
              <option value="{{ $micron }}">{{ $micron }}</option>
          @endforeach
      </select>

      <label>Size:</label>
      <select name="size" id="size">
          <option value="">-- Select Size --</option>
          @foreach ($sizeList as $size)
              <option value="{{ $size }}">{{ $size }}</option>
          @endforeach
      </select>
  </div>

  <div class="filter-group">
      <label>Rolls Done:</label>
      <select name="rolls_done" id="rolls_done">
          <option value="">-- Select Rolls Done --</option>
          @foreach ($rollsDoneList as $rollsDone)
              <option value="{{ $rollsDone }}">{{ $rollsDone }}</option>
          @endforeach
      </select>

      <label>Machine No:</label>
      <select name="machine_number" id="machine_number">
          <option value="">-- Select Machine No --</option>
          @foreach ($machineNumberList as $machineNumber)
              <option value="{{ $machineNumber }}">{{ $machineNumber }}</option>
          @endforeach
      </select>

      <label>Kgs Produced:</label>
      <select name="kilograms_produced" id="kilograms_produced">
          <option value="">-- Select Kgs Produced --</option>
          @foreach ($kilogramsProducedList as $kilogramsProduced)
              <option value="{{ $kilogramsProduced }}">{{ $kilogramsProduced }}</option>
          @endforeach
      </select>
  </div>
  <div class="form-group">
    <label>&nbsp;</label> <!-- spacing fix -->
    <button id="filter-btn" class="btn btn-primary w-100"><i class="fas fa-filter"></i>Filter</button>
</div>
</div>

</div>
<div id="total-kilogram-produced" class="summary-box mt-3">
  <h5>Total Kilogram Produced: <span id="total-output-value">0</span> kg</h5>
</div>
<!-- Production Table -->
<table class="production-table">
  <thead>
    <tr>
      <th>Sl No</th>
      <th>Datetime</th>
      <th>Customer Name</th>
      <th>Rolls Done</th>
      <th>Size</th>
      <th>Kilogram Produced</th>
      <th>Machine Number</th>
      <th>Micron</th>
    </tr>
  </thead>
  <tbody>
    @foreach($productions as $key => $production)
      <tr>
        <td>{{ $productions->firstItem() + $key }}</td>
        <td>{{ \Carbon\Carbon::parse($production->production_datetime)->format('Y-m-d H:i') }}</td>
        <td>{{ $production->customer->customer_name ?? 'N/A' }}</td>
        <td>{{ $production->rolls_done }}</td>
        <td>{{ $production->size }}</td>
        <td>{{ $production->kilograms_produced }}</td>
        <td>{{ $production->machine_number }}</td>
        <td>{{ $production->micron }}</td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <td colspan="8">
        {{ $productions->links() }} <!-- Pagination links -->
      </td>
    </tr>
  </tfoot>
</table>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
  
    // Function to fetch data
    function fetchData(page = 1) {
      let from = $('#from-date').val();
      let to = $('#to-date').val();
      let search = $('#search').val();
      let customer_id = $('#customer-id').val();
      let micron = $('#micron').val();
      let size = $('#size').val();
      let rolls_done = $('#rolls_done').val();
      let machine_number = $('#machine_number').val();
      let kilograms_produced = $('#kilograms_produced').val();
  
      $.ajax({
        url: "{{ route('admin.production.index') }}",
        method: 'GET',
        data: {
          page: page,
          from_date: from,
          to_date: to,
          search: search,
          customer_id: customer_id,
          micron: micron,
          size: size,
          rolls_done: rolls_done,
          machine_number: machine_number,
          kilograms_produced: kilograms_produced
        },
        success: function (res) {
          let rows = '';
          if (res.status && res.data.length > 0) {
            res.data.forEach((item, index) => {
              rows += `
                <tr>
                  <td>${index + 1}</td>
                  <td>${item.production_datetime}</td>
                  <td>${item.customer ? item.customer.customer_name : 'N/A'}</td>
                  <td>${item.rolls_done}</td>
                  <td>${item.size}</td>
                  <td>${item.kilograms_produced}</td>
                  <td>${item.machine_number}</td>
                  <td>${item.micron}</td>
                </tr>
              `;
            });
            
            // Update total kilograms produced
            $('#total-output-value').text(res.total_kilogram); // Make sure the server response is sending 'total_kilogram'
  
            // Update pagination
            let pagination = '';
            if (res.pagination.total > res.pagination.per_page) {
              for (let i = 1; i <= res.pagination.last_page; i++) {
                pagination += `<button class="page-link" data-page="${i}">${i}</button>`;
              }
            }
            $('.pagination').html(pagination);
  
          } else {
            rows = `<tr><td colspan="8" class="text-center text-muted">No data found</td></tr>`;
            $('#total-output-value').text(0); 
          }
  
          // Populate the table with new rows
          $('.production-table tbody').html(rows);
        },
        error: function () {
          alert('Error fetching data!');
        }
      });
    }
  
    // Filter button click
    $('#filter-btn').on('click', function () {
      fetchData();  
    });
  
    // Pagination button click
    $(document).on('click', '.pagination button', function () {
      let page = $(this).data('page');
      fetchData(page);
    });
  
    // Initial load
    fetchData();  // Load data on page load
  
  });
  </script>
  
@endsection
