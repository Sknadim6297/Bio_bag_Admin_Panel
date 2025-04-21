@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-stock.css') }}">

@endsection

@section('content')

<div class="dashboard-header">
    <h1>Manage Stock</h1>
</div>

<!-- Filter and Search Section -->
<!-- Export Button -->
<div class="action-bar">
  <a href="{{ route('admin.stock.export') }}" class="btn btn-success">Export Stock to Excel</a>
  <!-- Filter and Search Section -->
  <div class="entries-filter">
      <span>Show</span>
      <select>
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
      </select>
      <span>entries</span>
  </div>
  <div class="search-filter">
      <i class="fas fa-search"></i>
      <input type="text" id="search" placeholder="Search by product name...">
  </div>
</div>


<!-- Stock Table -->
<table class="stock-table">
    <thead>
        <tr>
            <th>SL No</th>
            <th>Product Name</th>
            <th>Stock</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stocks as $index => $stock)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $stock->product_name }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $stock->stock)) }}</td>
            <td>{{ number_format($stock->quantity) }}</td>
            <td>
                <button class="view-btn stock-btn" data-product-id="{{ $stock->id }}" title="View Stock Movement">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">No stock data available.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- Stock Movement Modal -->
<div id="stockMovementModal" class="stock-modal" style="display: none;">
    <div class="stock-modal-content">
        <span id="closeModal" class="close">&times;</span>
        <h2 id="modalProductTitle">Stock Movement Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Total Stock</th>
                </tr>
            </thead>
            <tbody id="stockMovementTableBody">
                <!-- Data dynamically inserted here -->
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).on('click', '.stock-btn', function () {
    var productId = $(this).data('product-id');

    $.ajax({
        url: "/admin/stock/movement/" + productId,
        method: "GET",
        success: function (res) {
            if (res.status) {
                let rows = '';
                let totalBalance = 0;

                // Set the modal title with product name and available stock
                $('#modalProductTitle').text(`Stock Movement: ${res.product_name} (Available: ${res.available_stock})`);

                // Loop through transactions and render them
                res.transactions.forEach(item => {
                    let dateTime = new Date(item.date);
                    let formattedDate = dateTime.toLocaleString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                    let typeColor = item.type === 'Purchase' ? 'green' : 'red';
                    let formattedQty = item.quantity > 0 ? '+' + item.quantity : item.quantity;

                    // Add row to table
                    rows += `
                        <tr>
                            <td>${formattedDate}</td>
                            <td style="color:${typeColor}; font-weight:bold;">${item.type}</td>
                            <td>${formattedQty}</td>
                            <td>${item.balance}</td>
                        </tr>
                    `;
                    totalBalance = item.balance;
                });
                rows += `
                    <tr style="font-weight: bold; background: #f0f0f0;">
                        <td colspan="3" style="text-align: right;">Total Available:</td>
                        <td>${totalBalance}</td>
                    </tr>
                `;

                $('#stockMovementTableBody').html(rows);
                $('#stockMovementModal').fadeIn();
            }
        },
        error: function () {
            alert('Error fetching stock movement data!');
        }
    });
});



$(document).on('click', '#closeModal', function () {
    $('#stockMovementModal').fadeOut();
});

$(document).ready(function () {
    $('#search').on('keyup', function () {
        let query = $(this).val();

        $.ajax({
            url: "{{ route('admin.stock.search') }}",
            method: 'GET',
            data: { search: query },
            success: function (res) {
                let rows = '';

                if (res.status && res.data.length > 0) {
                    res.data.forEach(item => {
                        rows += `
                            <tr>
                                <td>${item.sl}</td>
                                <td>${item.product_name}</td>
                                <td>${item.stock}</td>
                                <td>${item.quantity}</td>
                                <td>
                                    <button class="view-btn stock-btn" data-product-id="${item.id}" title="View Stock Movement">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    rows = `<tr><td colspan="5" class="text-center text-muted">No matching results found.</td></tr>`;
                }

                $('.stock-table tbody').html(rows);
            },
            error: function () {
                alert('Something went wrong while searching.');
            }
        });
    });
});
</script>
@endsection
