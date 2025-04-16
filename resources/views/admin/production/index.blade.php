@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-production.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Production</h1>
</div>

<!-- Add Production Button -->
<div class="action-bar">
  <a href="{{ route('admin.production.create') }}" class="btn-add">
    <i class="fas fa-plus"></i> Add Production
  </a>

  <!-- Filters -->
  <div class="filter-container">
    <!-- Date Filter -->
    <div class="date-filter">
      <span>From:</span>
      <input type="date" id="from-date">
      <span>To:</span>
      <input type="date" id="to-date">
    </div>

    <!-- Search Filter -->
    <div class="search-filter">
      <i class="fas fa-search"></i>
      <input type="text" id="search" placeholder="Search production...">
    </div>
    
  </div>
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
    function fetchData() {
        let from = $('#from-date').val();
        let to = $('#to-date').val();
        let search = $('#search').val();

        $.ajax({
            url: "{{ route('admin.production.filter') }}",
            method: 'GET',
            data: {
                from_date: from,
                to_date: to,
                search: search
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
                } else {
                    rows = `<tr><td colspan="8" class="text-center text-muted">No data found</td></tr>`;
                }

                $('.production-table tbody').html(rows);
            },
            error: function () {
                alert('Error fetching data!');
            }
        });
    }

    $('#from-date, #to-date').on('change', fetchData);
    $('#search').on('keyup', fetchData);
});

</script>

@endsection
