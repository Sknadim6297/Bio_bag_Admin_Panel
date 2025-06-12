@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-production.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<style>
     
    /* Modern Dashboard Styling */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .dashboard-header h1 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #344767;
        margin: 0;
    }
    
    .dashboard-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        padding: 0.55rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    }
    /* Filter Container */
    .filter-container {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px;
        align-items: end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .filter-group label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .filter-container select,
    .filter-container input {
        height: 42px;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .filter-container select:focus,
    .filter-container input:focus {
        border-color: #4a6fdc;
        box-shadow: 0 0 0 3px rgba(74, 111, 220, 0.15);
        outline: none;
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }
    
    /* Summary Box */
    .summary-box {
      background: var(--accent-blue);
        color: white;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .summary-box h5 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .summary-box #total-output-value {
        font-weight: 700;
        font-size: 1.5rem;
    }
    
    /* Table Styling */
    .production-table-container {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .production-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .production-table th {
        background-color: #f8f9fa;
        color: #344767;
        font-weight: 600;
        text-align: left;
        padding: 15px 12px;
        font-size: 0.85rem;
        border-bottom: 2px solid #e9ecef;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .production-table td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
        color: #495057;
        font-size: 0.9rem;
    }
    
    .production-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .production-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Pagination */
    .pagination-container {
        padding: 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 5px;
    }
    
    .page-link {
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        color: #4a6fdc;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .page-link:hover {
        background-color: #4a6fdc;
        color: white;
        border-color: #4a6fdc;
    }
    
    .page-link.active {
        background-color: #4a6fdc;
        color: white;
        border-color: #4a6fdc;
    }
    
    /* Responsive adaptations */
    @media (max-width: 992px) {
        .filter-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .filter-container {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .dashboard-actions {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .filter-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Manage Production</h1>
    <div class="dashboard-actions">
        <a href="{{ route('admin.production.create') }}">
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Production
            </button>
        </a>
        <a href="{{ route('admin.production.download-report') }}" class="btn btn-success" id="download-report-btn">
            <i class="fas fa-file-pdf"></i> Download Report
        </a>
    </div>
</div>

<div class="filter-container">
    <div class="filter-group">
        <label for="from-date">
            <i class="far fa-calendar-alt"></i> From Date
        </label>
        <input type="date" id="from-date" class="form-control">
    </div>

    <div class="filter-group">
        <label for="to-date">
            <i class="far fa-calendar-alt"></i> To Date
        </label>
        <input type="date" id="to-date" class="form-control">
    </div>

    <div class="filter-group">
        <label for="search">
            <i class="fas fa-search"></i> Search Customer
        </label>
        <input type="text" id="search" placeholder="Enter customer name..." class="form-control">
    </div>

    <div class="filter-group">
        <label for="customer-id">
            <i class="fas fa-user"></i> Customer
        </label>
        <select id="customer-id" class="form-control">
            <option value="">-- Select Customer --</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="micron">
            <i class="fas fa-ruler-vertical"></i> Micron
        </label>
        <select id="micron" class="form-control">
            <option value="">-- Select Micron --</option>
            @foreach ($micronList as $micron)
                <option value="{{ $micron }}">{{ $micron }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="size">
            <i class="fas fa-expand-arrows-alt"></i> Size
        </label>
        <select id="size" class="form-control">
            <option value="">-- Select Size --</option>
            @foreach ($sizeList as $size)
                <option value="{{ $size }}">{{ $size }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="rolls_done">
            <i class="fas fa-scroll"></i> Rolls Done
        </label>
        <select id="rolls_done" class="form-control">
            <option value="">-- Select Rolls --</option>
            @foreach ($rollsDoneList as $rollsDone)
                <option value="{{ $rollsDone }}">{{ $rollsDone }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="machine_number">
            <i class="fas fa-cogs"></i> Machine No
        </label>
        <select id="machine_number" class="form-control">
            <option value="">-- Select Machine --</option>
            @foreach ($machineNumberList as $machineNumber)
                <option value="{{ $machineNumber }}">{{ $machineNumber }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-group">
        <label for="kilograms_produced">
            <i class="fas fa-weight"></i> Kgs Produced
        </label>
        <select id="kilograms_produced" class="form-control">
            <option value="">-- Select Kgs --</option>
            @foreach ($kilogramsProducedList as $kilogramsProduced)
                <option value="{{ $kilogramsProduced }}">{{ $kilogramsProduced }}</option>
            @endforeach
        </select>
    </div>

    <div class="filter-actions">
        <button id="filter-btn" class="btn btn-primary">
            <i class="fas fa-filter"></i> Apply Filters
        </button>
        <button id="reset-btn" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Reset
        </button>
    </div>
</div>

<div id="total-kilogram-produced" class="summary-box">
    <h5><i class="fas fa-chart-line"></i> Total Production Output</h5>
    <div><span id="total-output-value">0</span> kg</div>
</div>

<!-- Production Table -->
<div class="production-table-container">
    <table class="production-table">
        <thead>
            <tr>
                <th width="5%">Sl No</th>
                <th width="15%">Datetime</th>
                <th width="20%">Customer Name</th>
                <th width="10%">Rolls Done</th>
                <th width="10%">Size</th>
                <th width="10%">Kgs Produced</th>
                <th width="10%">Machine No</th>
                <th width="10%">Micron</th>
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
    </table>
    <div class="pagination-container">
        <div class="pagination">
            {{ $productions->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function updateDownloadUrl() {
      const from = $('#from-date').val();
      const to = $('#to-date').val();
      const search = $('#search').val();
      const customer_id = $('#customer-id').val();
      const micron = $('#micron').val();
      const size = $('#size').val();
      const machine_number = $('#machine_number').val();
      const rolls_done = $('#rolls_done').val();
      const kilograms_produced = $('#kilograms_produced').val();
      
      let url = "{{ route('admin.production.download-report') }}?";
      if (from) url += `from_date=${from}&`;
      if (to) url += `to_date=${to}&`;
      if (search) url += `search=${search}&`;
      if (customer_id) url += `customer_id=${customer_id}&`;
      if (micron) url += `micron=${micron}&`;
      if (size) url += `size=${size}&`;
      if (machine_number) url += `machine_number=${machine_number}&`;
      if (rolls_done) url += `rolls_done=${rolls_done}&`;
      if (kilograms_produced) url += `kilograms_produced=${kilograms_produced}&`;
      
      $('#download-report-btn').attr('href', url);
  }

  $(document).ready(function() {
      // Function to fetch data
      function fetchData(page = 1) {
          // Show loading indicator
          $('.production-table tbody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading data...</td></tr>');
          
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
              success: function(res) {
                  let rows = '';
                  if (res.status && res.data.length > 0) {
                      res.data.forEach((item, index) => {
                          // Format the date properly
                          const formattedDate = formatDateTime(item.production_datetime);
                          
                          // Zebra striping for better readability
                          const rowClass = index % 2 === 0 ? 'even-row' : 'odd-row';
                          
                          rows += `
                              <tr class="${rowClass}">
                                  <td>${index + 1}</td>
                                  <td>${formattedDate}</td>
                                  <td>${item.customer ? item.customer.customer_name : 'N/A'}</td>
                                  <td>${item.rolls_done}</td>
                                  <td>${item.size}</td>
                                  <td>${item.kilograms_produced}</td>
                                  <td>${item.machine_number}</td>
                                  <td>${item.micron}</td>
                              </tr>
                          `;
                      });
                      
                      // Update total production with animation
                      animateValue('total-output-value', parseInt($('#total-output-value').text()), res.total_kilogram, 1000);
                      
                      // Create pagination with proper styling
                      let pagination = '';
                      if (res.pagination.total > res.pagination.per_page) {
                          // Previous button
                          if (res.pagination.current_page > 1) {
                              pagination += `<button class="page-link" data-page="${res.pagination.current_page - 1}"><i class="fas fa-chevron-left"></i></button>`;
                          }
                          
                          // Page numbers
                          for (let i = 1; i <= res.pagination.last_page; i++) {
                              const activeClass = i === res.pagination.current_page ? 'active' : '';
                              pagination += `<button class="page-link ${activeClass}" data-page="${i}">${i}</button>`;
                          }
                          
                          // Next button
                          if (res.pagination.current_page < res.pagination.last_page) {
                              pagination += `<button class="page-link" data-page="${res.pagination.current_page + 1}"><i class="fas fa-chevron-right"></i></button>`;
                          }
                      }
                      
                      $('.pagination').html(pagination);
                  } else {
                      rows = `<tr><td colspan="8" class="text-center text-muted">
                          <div style="padding: 30px;">
                              <i class="fas fa-search fa-3x mb-3"></i>
                              <p>No production data found matching your filters.</p>
                              <button id="clear-filters" class="btn btn-sm btn-outline-primary">Clear Filters</button>
                          </div>
                      </td></tr>`;
                      
                      $('#total-output-value').text(0);
                      $('.pagination').html('');
                  }
                  
                  $('.production-table tbody').html(rows);
                  
                  // Add click handler for the clear filters button
                  $('#clear-filters').on('click', function() {
                      $('#reset-btn').click();
                  });
              },
              error: function() {
                  $('.production-table tbody').html(`
                      <tr><td colspan="8" class="text-center text-danger">
                          <i class="fas fa-exclamation-triangle"></i> Error fetching data. Please try again.
                      </td></tr>
                  `);
              }
          });
      }

      // Helper function to animate value changes
      function animateValue(id, start, end, duration) {
          // Make sure we have integers to work with
          start = Math.floor(parseInt(start) || 0);
          end = Math.floor(parseInt(end) || 0);
          
          const range = end - start;
          const minTimer = 50; // minimum timer interval in ms
          let stepTime = Math.abs(Math.floor(duration / range));
          
          // Ensure that stepTime is at least minTimer
          stepTime = Math.max(stepTime, minTimer);
          
          const startTime = new Date().getTime();
          const endTime = startTime + duration;
          let timer;
          
          function run() {
              const now = new Date().getTime();
              const remaining = Math.max((endTime - now) / duration, 0);
              const value = Math.round(end - (remaining * range));
              document.getElementById(id).innerText = value;
              if (value == end) {
                  clearInterval(timer);
              }
          }
          
          timer = setInterval(run, stepTime);
          run();
      }

      // Event handlers
      $('#search').on('input', function() {
          updateDownloadUrl();
      });

      $('#from-date, #to-date').on('change', function() {
          updateDownloadUrl();
      });
      
      $('#filter-btn').on('click', function() {
          fetchData();
          updateDownloadUrl();
      });
      
      $('#reset-btn').on('click', function() {
          $('#from-date').val('');
          $('#to-date').val('');
          $('#search').val('');
          $('#customer-id').val('');
          $('#micron').val('');
          $('#size').val('');
          $('#rolls_done').val('');
          $('#machine_number').val('');
          $('#kilograms_produced').val('');
          fetchData();
          updateDownloadUrl();
      });

      $(document).on('click', '.pagination button', function() {
          let page = $(this).data('page');
          fetchData(page);
          
          // Scroll back to top of table
          $('html, body').animate({
              scrollTop: $('.production-table-container').offset().top - 100
          }, 300);
      });
  
      // Initial load
      fetchData(); 
      updateDownloadUrl();
  });

  // Helper function to format the date
  function formatDateTime(dateTimeStr) {
      // Check if the string is in ISO format (contains 'T' and possibly 'Z')
      if (dateTimeStr && (dateTimeStr.includes('T') || dateTimeStr.includes('Z'))) {
          // Parse the ISO datetime string
          const date = new Date(dateTimeStr);
          
          // Format as YYYY-MM-DD HH:MM
          const year = date.getFullYear();
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const day = String(date.getDate()).padStart(2, '0');
          const hours = String(date.getHours()).padStart(2, '0');
          const minutes = String(date.getMinutes()).padStart(2, '0');
          
          return `${year}-${month}-${day} ${hours}:${minutes}`;
      }
      
      // If it's already formatted or not a valid datetime, return as is
      return dateTimeStr;
  }
</script>
@endsection
