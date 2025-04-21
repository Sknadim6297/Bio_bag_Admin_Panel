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
    <input type="text" id="search" placeholder="Search consumption...">
  </div>
  <div class="date-filter">
    <label>From:</label>
    <input type="date" id="from-date">
    <label>To:</label>
    <input type="date" id="to-date">
    <button class="btn btn-secondary" id="filter-btn">
      <i class="fas fa-filter"></i>
    </button>
       <button class="btn btn-secondary" id="reset-btn">
      <i class="fas fa-undo"></i>
    </button>
  </div>
</div>

<div id="final-consumption" class="summary-box mt-3">
  <h5>Total Consumption: <span id="final-consumption-value">0</span> kg</h5>
</div>

<!-- Consumption Table -->
<table class="table table-bordered consumption-table">
  <thead>
    <tr>
      <th>SL No</th>
      <th>Date & Time</th>
      <th>Product</th>
      <th>Quantity</th>
    </tr>
  </thead>
  <tbody>
    <!-- Rows will be populated dynamically by JS -->
  </tbody>
</table>

{{-- Pagination --}}
<div class="mt-3">
  <div class="pagination">
    <!-- Pagination buttons will be populated dynamically by JS -->
  </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

  function fetchData(page = 1) {
    let from = $('#from-date').val();
    let to = $('#to-date').val();
    let search = $('#search').val();

    $.ajax({
      url: "{{ route('admin.consumption.index') }}",
      method: 'GET',
      data: {
        page: page,
        from_date: from,
        to_date: to,
        search: search
      },
      success: function (res) {
        console.log(res);
        
        let rows = '';
        if (res.status && res.data.length > 0) {
          res.data.forEach((item, index) => {
            rows += `
              <tr>
                <td>${index + 1}</td>
                <td>${item.date} ${item.time}</td>
                <td>${item.stock.product_name ?? 'N/A'}</td>
                <td>${item.quantity} ${item.unit ?? ''}</td>
              </tr>
            `;
          });

          $('#final-consumption-value').text(res.total_consumption);

          let pagination = '';
          for (let i = 1; i <= res.pagination.last_page; i++) {
            pagination += `<button class="page-btn" data-page="${i}">${i}</button>`;
          }
          $('.pagination').html(pagination);
        } else {
          rows = `<tr><td colspan="4" class="text-center text-muted">No data found</td></tr>`;
          $('#final-consumption-value').text(0);
        }
        $('.consumption-table tbody').html(rows);
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
  


  $(document).on('click', '.pagination button', function () {
    let page = $(this).data('page');
    fetchData(page);
  });

  

  fetchData(); 

});
</script>
@endsection
