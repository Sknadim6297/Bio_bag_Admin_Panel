@extends('layouts.layout')

  @section('styles')
    <link rel="stylesheet" href="{{ asset('admin/css/manage-purchase.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
   
  @endsection

  @section('content')
  <div class="dashboard-header">
    <h1>Manage Purchase Bill</h1>
    <button class="btn btn-primary" onclick="window.location.href='{{ route('admin.grn.create') }}'">
      <i class="fas fa-plus"></i> Add Purchase Bill
    </button>
  </div>

  <!-- Action Bar with Filters -->
  <div class="action-bar">
  <div class="action-group">
    <div class="entries-filter">
      <span>Show</span>
      <select id="entries-select">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
      <span>entries</span>
    </div>
    <div class="date-filter">
      <span>From:</span>
      <input type="date" id="from-date" value="2025-01-01">
      <span>To:</span>
      <input type="date" id="to-date">
    </div>
  </div>
  <div class="search-filter">
    <i class="fas fa-search"></i>
    <input type="text" id="search-input" placeholder="Search...">
  </div>
</div>


  <!-- Purchase Order Table -->
  <div style="overflow-x: auto;">
    <table class="purchase-table" id="purchase-table">
      <thead>
        <tr>
          <th>Sl. No.</th>
          <th>Date</th>
          <th>Purchase Order#</th>
          <th>Vendor</th>
          <th>Deliver To</th>
          <th>Total Price</th>
          <th>CGST (%)</th>
          <th>SGST (%)</th>
          <th>IGST (%)</th>
          <th>CESS (%)</th>
          <th>Payment Terms</th>
          <th>Expected Delivery</th>
          <th>Terms & Condition</th>
          <th>Customer Notes</th>
          <th>Reference#</th>
          <th>View</th>
          <th>Download</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($purchaseOrders as $key => $po)
        <tr data-date="{{ $po->po_date }}" data-po="{{ $po->po_number }}">
          <td>{{ $key + 1 }}</td>
          <td>{{ $po->po_date }}</td>
          <td>{{ $po->po_number }}</td>
          <td>{{ $po->vendor->vendor_name ?? '-' }}</td>
          <td>{{ $po->deliver_to_location }}</td>
          <td>{{ number_format($po->total) }}</td>
          <td>{{ $po->cgst ?? 0 }}</td>
          <td>{{ $po->sgst ?? 0 }}</td>
          <td>{{ $po->igst ?? 0 }}</td>
          <td>{{ $po->cess ?? 0 }}</td>
          <td>{{ $po->payment_terms }}</td>
          <td>{{ $po->expected_delivery }}</td>
          <td>{{ $po->terms }}</td>
          <td>{{ $po->notes }}</td>
          <td>{{ $po->reference }}</td>
            <td>
              <button class="action-btn view-btn" data-po='@json($po)' title="View Details">
                <i class="fas fa-eye"></i>
              </button>
            </td>
          <td>
            <button class="action-btn download-btn" data-id="{{ $po->id }}" title="Download Report">
              <i class="fas fa-download"></i>
            </button>
          </td>
          <td>
            <a href="{{ route('admin.grn.edit', $po->id) }}" class="action-btn edit-btn" title="Edit">
              <i class="fas fa-edit"></i>
            </a>
            <button class="action-btn delete-btn" data-id="{{ $po->id }}" title="Delete">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
  </table>
  </div>
  <div id="invoiceModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-btn" id="closeModalBtn">&times;</span>
        <h2>Purchase Invoice</h2>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Date</th>
                    <th>Purchase Order#</th>
                    <th>Vendor</th>
                    <th>Deliver To</th>
                    <th>Total Price</th>
                    <th>CGST (%)</th>
                    <th>SGST (%)</th>
                    <th>IGST (%)</th>
                    <th>CESS (%)</th>
                    <th>Payment Terms</th>
                </tr>
            </thead>
            <tbody>
              
            </tbody>
        </table>
        <hr>
        <h3>Product Details</h3>
        <table class="invoice-table">
            <thead>
            
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
  </div>
  <!-- Pagination -->
  <div class="pagination">
    <button class="pagination-btn" id="prev-page">&laquo;</button>
    <button class="pagination-btn active">1</button>
    <button class="pagination-btn">2</button>
    <button class="pagination-btn">3</button>
    <button class="pagination-btn" id="next-page">&raquo;</button>
  </div>
  @endsection

  @section('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const po = JSON.parse(this.getAttribute('data-po'));
    
            // Update main table (PO info)
            let headerRow = `
                <tr>
                    <td>1</td>
                    <td>${po.po_date}</td>
                    <td>${po.po_number}</td>
                    <td>${po.vendor?.vendor_name || '-'}</td>
                    <td>${po.deliver_to_location}</td>
                    <td>₹${parseFloat(po.total).toLocaleString()}</td>
                    <td>${po.cgst ?? 0}</td>
                    <td>${po.sgst ?? 0}</td>
                    <td>${po.igst ?? 0}</td>
                    <td>${po.cess ?? 0}</td>
                    <td>${po.payment_terms}</td>
                </tr>
            `;
            document.querySelector('#invoiceModal .invoice-table tbody').innerHTML = headerRow;
    
            // Update product items table
            let productRows = '';
            po.items.forEach((item, index) => {
                let total = item.quantity * item.unit_price;
                productRows += `
                    <tr>
                        <td>${item.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>${item.measurement}</td>
                        <td>₹${item.unit_price}</td>
                        <td>₹${total}</td>
                    </tr>
                `;
            });
            document.querySelectorAll('#invoiceModal .invoice-table')[1].querySelector('tbody').innerHTML = productRows;
    
            // Show modal
            document.getElementById("invoiceModal").style.display = "block";
        });
    });
    
    document.getElementById("closeModalBtn").addEventListener("click", function () {
        document.getElementById("invoiceModal").style.display = "none";
    });
    
    window.addEventListener("click", function (event) {
        if (event.target == document.getElementById("invoiceModal")) {
            document.getElementById("invoiceModal").style.display = "none";
        }
    });
    </script>
    <script>
    document.querySelectorAll('.download-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const poId = this.getAttribute('data-id');
            const button = this;
            
            // Show loading state
            button.classList.add('loading');
            const originalContent = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

            // Trigger download
            window.location.href = `/admin/grn/${poId}/download`;

            // Reset button after a delay
            setTimeout(() => {
                button.classList.remove('loading');
                button.innerHTML = originalContent;
            }, 2000);
        });
    });
    </script>
    <script>
    $(document).on('click', '.delete-btn', function() {
      var id = $(this).data('id');
      if(confirm('Are you sure you want to delete this purchase order?')) {
        $.ajax({
          url: '/admin/grn/' + id,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if(response.success) {
              // Remove the row from the table
              $('button.delete-btn[data-id="' + id + '"]').closest('tr').remove();
              alert('Purchase Order deleted successfully!');
            } else {
              alert('Error: ' + (response.message || 'Could not delete.'));
            }
          },
          error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Could not delete.'));
          }
        });
      }
    });
    </script>
  @endsection

  @section('styles')
  <style>
    .action-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      margin: 0 4px;
      font-size: 18px;
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      cursor: pointer;
      box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    }
    .view-btn {
      background: #2563eb;
      color: #fff;
    }
    .view-btn:hover {
      background: #1741a0;
      color: #fff;
    }
    .edit-btn {
      background: #fbbf24;
      color: #fff;
    }
    .edit-btn:hover {
      background: #b9810c;
      color: #fff;
    }
    .delete-btn {
      background: #ef4444;
      color: #fff;
    }
    .delete-btn:hover {
      background: #991b1b;
      color: #fff;
    }
    .download-btn {
      background: #64748b;
      color: #fff;
    }
    .download-btn:hover {
      background: #334155;
      color: #fff;
    }
  </style>
  @endsection
