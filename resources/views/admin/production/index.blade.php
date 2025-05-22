@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-production.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Production</h1>
  <div>
    <a href="{{ route('admin.production.create') }}">
      <button class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Production
      </button>
    </a>
    <a href="{{ route('admin.production.download-report') }}" class="btn btn-success ml-2" id="download-report-btn">
      <i class="fas fa-file-pdf"></i> Download Report
    </a>
  </div>
</div>

<div class="action-bar">  
  <!-- Filters -->
  <div class="filter-container">

    <div class="filter-group">
      <label for="from-date">From:</label>
      <input type="date" id="from-date">
    </div>

    <div class="filter-group">
      <label for="to-date">To:</label>
      <input type="date" id="to-date">
    </div>

    <div class="filter-group">
      <label for="search">Search:</label>
      <input type="text" id="search" placeholder="Search by Customer Name">
    </div>

    <div class="filter-group">
      <label for="customer-id">Customer:</label>
      <select id="customer-id">
        <option value="">-- Select Customer --</option>
        @foreach ($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label for="micron">Micron:</label>
      <select id="micron">
        <option value="">-- Select Micron --</option>
        @foreach ($micronList as $micron)
            <option value="{{ $micron }}">{{ $micron }}</option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label for="size">Size:</label>
      <select id="size">
        <option value="">-- Select Size --</option>
        @foreach ($sizeList as $size)
            <option value="{{ $size }}">{{ $size }}</option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label for="rolls_done">Rolls Done:</label>
      <select id="rolls_done">
        <option value="">-- Select Rolls Done --</option>
        @foreach ($rollsDoneList as $rollsDone)
            <option value="{{ $rollsDone }}">{{ $rollsDone }}</option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label for="machine_number">Machine No:</label>
      <select id="machine_number">
        <option value="">-- Select Machine No --</option>
        @foreach ($machineNumberList as $machineNumber)
            <option value="{{ $machineNumber }}">{{ $machineNumber }}</option>
        @endforeach
      </select>
    </div>

    <div class="filter-group">
      <label for="kilograms_produced">Kgs Produced:</label>
      <select id="kilograms_produced">
        <option value="">-- Select Kgs Produced --</option>
        @foreach ($kilogramsProducedList as $kilogramsProduced)
            <option value="{{ $kilogramsProduced }}">{{ $kilogramsProduced }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label>&nbsp;</label> 
      <button id="filter-btn" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
    </div>
  </div>
  
</div>

</div>
<div id="total-kilogram-produced" class="summary-box mt-3">
  <h5 style="margin-top: 20px;">Total Kilogram Produced: <span id="total-output-value">0</span> kg</h5>
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
        {{ $productions->links() }}
      </td>
    </tr>
  </tfoot>
</table>
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
            
            $('#total-output-value').text(res.total_kilogram); 

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
          $('.production-table tbody').html(rows);
        },
        error: function () {
          alert('Error fetching data!');
        }
      });
    }

    $('#search').on('input', function() {
      updateDownloadUrl();
    });

    $('#from-date, #to-date').on('change', function() {
      updateDownloadUrl();
    });
    
    $('#filter-btn').on('click', function () {
      fetchData();
      updateDownloadUrl();
    });
    
    $('#reset-btn').on('click', function () {
      $('#from-date').val('');
      $('#to-date').val('');
      $('#search').val('');
      fetchData();
      updateDownloadUrl();
    });

    $(document).on('click', '.pagination button', function () {
      let page = $(this).data('page');
      fetchData(page);
    });
  
    fetchData(); 
    updateDownloadUrl();
  
  });
  </script>
  
@endsection
