@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-consumption.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Consumption</h1>
  <div>
    <a href="{{ route('admin.consumption.create') }}">
      <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Consumption
      </button>
    </a>
    <a href="{{ route('admin.consumption.download-report') }}" class="btn btn-success ml-2" id="download-report-btn">
      <i class="fas fa-file-pdf"></i> Download Report
    </a>
  </div>
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
<script>
$(document).ready(function () {
    let currentPage = 1;

    function fetchData(page = 1) {
        currentPage = page;
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
                if (res.status) {
                    // Update table
                    let rows = '';
                    if (res.data.length > 0) {
                        res.data.forEach((item, index) => {
                            let rowNumber = (currentPage - 1) * 10 + (index + 1);
                            
                            // Format both date and time
                            let dateTime = new Date(item.date);
                            let formattedDate = dateTime.getDate().toString().padStart(2, '0') + '/' +
                                                (dateTime.getMonth() + 1).toString().padStart(2, '0') + '/' +
                                                dateTime.getFullYear().toString().substr(-2);
                            let formattedTime = item.time.substring(0, 5); // Gets HH:mm from time string

                            rows += `
                                <tr>
                                    <td>${rowNumber}</td>
                                    <td>${formattedDate} ${formattedTime}</td>
                                    <td>${item.stock ? item.stock.product_name : 'N/A'}</td>
                                    <td>${parseFloat(item.quantity).toFixed(2)} kg</td>
                                </tr>
                            `;
                        });
                    } else {
                        rows = `<tr><td colspan="4" class="text-center">No data found</td></tr>`;
                    }
                    $('.consumption-table tbody').html(rows);

                    // Update total consumption
                    $('#final-consumption-value').text(res.total_consumption);

                    // Update pagination
                    updatePagination(res.pagination);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Error fetching data. Please try again.');
            }
        });
    }

    function updatePagination(pagination) {
        let paginationHtml = '';
        for (let i = 1; i <= pagination.last_page; i++) {
            let activeClass = i === pagination.current_page ? 'active' : '';
            paginationHtml += `
                <button class="page-btn ${activeClass}" data-page="${i}">
                    ${i}
                </button>
            `;
        }
        $('.pagination').html(paginationHtml);
    }

    function updateDownloadUrl() {
        const from = $('#from-date').val();
        const to = $('#to-date').val();
        const search = $('#search').val();
        
        let url = "{{ route('admin.consumption.download-report') }}?";
        if (from) url += `from_date=${from}&`;
        if (to) url += `to_date=${to}&`;
        if (search) url += `search=${search}&`;
        
        $('#download-report-btn').attr('href', url.slice(0, -1));
    }

    // Event handlers
    $('#filter-btn').on('click', function() {
        currentPage = 1; // Reset to first page when filtering
        fetchData(currentPage);
        updateDownloadUrl();
    });

    $('#search').on('keyup', function(e) {
        if (e.key === 'Enter') {
            currentPage = 1;
            fetchData(currentPage);
            updateDownloadUrl();
        }
    });

    $('#reset-btn').on('click', function() {
        $('#from-date').val('');
        $('#to-date').val('');
        $('#search').val('');
        currentPage = 1;
        fetchData(currentPage);
        updateDownloadUrl();
    });

    $(document).on('click', '.page-btn', function() {
        currentPage = $(this).data('page');
        fetchData(currentPage);
    });

    // Initial load
    fetchData(1);
});
</script>
@endsection
