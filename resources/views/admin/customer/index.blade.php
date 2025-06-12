@extends('layouts.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/manage-customer.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
  <div class="dashboard-header">
    <h1>Manage Customer</h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <p class="mb-0">Total Customer: <strong>{{ $customers->count() }}</strong></p>
      <div>
        <a href="{{ route('admin.customer.create') }}">
          <button class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add Customer
          </button>
        </a>
        <button type="button" class="btn btn-primary ms-2" id="showImportModal">
          <i class="fas fa-file-import me-2"></i>Import Customers
        </button>
        <a href="{{ route('admin.customer.export-template') }}" class="btn btn-outline-primary ms-2">
          <i class="fas fa-download me-2"></i>Download Template
        </a>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6 d-flex align-items-center">
        <label for="entriesSelect" class="me-2 mb-0">Show</label>
        <select id="entriesSelect" class="form-select w-auto me-2">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
        </select>
        <span>entries</span>
      </div>

      <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <label for="customerSearch" class="me-2 mb-0">Search:</label>
        <input
          type="text"
          id="customerSearch"
          class="form-control d-inline-block w-50"
          placeholder="Search by name or code"
        />
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Sl No</th>
          <th>Customer Name</th>
          <th>Customer Code</th>
          <th>Mobile Number</th>
          <th>Payment Term</th>
          <th>Address</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="customerTableBody">
        @foreach ($customers as $index => $customer)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $customer->customer_name }}</td>
            <td>{{ $customer->customer_code }}</td>
            <td>{{ $customer->mobile_number }}</td>
            <td>{{ $customer->payment_terms }}</td>
            <td>{{ $customer->address }}</td>
            <td>
              <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($customer->status) }}
              </span>
            </td>
            <td>
              <div class="action-buttons">
                <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                  <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                </a>
                <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.customer.destroy', $customer->id) }}" title="Delete">
                  <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                </a>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="pagination">
    {{ $customers->links() }}
  </div>

  <div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
    <div class="showing-entries">
      Showing <span>{{ $customers->firstItem() }}</span> to <span>{{ $customers->lastItem() }}</span> of <span>{{ $customers->total() }}</span> entries
    </div>
    <div class="pagination-buttons">
      <button class="btn btn-outline-secondary btn-sm disabled" disabled>
        <i class="fas fa-angle-left"></i> Prev
      </button>
      <button class="btn btn-outline-primary btn-sm active">1</button>
      <button class="btn btn-outline-primary btn-sm">2</button>
      <button class="btn btn-outline-secondary btn-sm">
        Next <i class="fas fa-angle-right"></i>
      </button>
    </div>
  </div>

  <!-- Custom Import Modal -->
  <div class="custom-modal-backdrop" id="modalBackdrop"></div>
  <div class="custom-modal" id="importModal">
      <div class="custom-modal-header">
          <h5 class="custom-modal-title">Import Customers</h5>
          <button type="button" class="custom-modal-close" id="closeModal">&times;</button>
      </div>
      <form id="importForm" enctype="multipart/form-data">
          @csrf
          <div class="custom-modal-body">
              <div>
                  <label for="importFile" class="form-label">Select Excel File</label>
                  <input class="form-input" type="file" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                  <div class="form-text">
                      Supported formats: .xlsx, .xls, .csv
                  </div>
              </div>
              <div class="alert-info">
                  <h6><i class="fas fa-info-circle"></i> Required Fields:</h6>
                  <ul>
                      <li>customer_name</li>
                      <li>mobile_number</li>
                      <li>address</li>
                  </ul>
              </div>
          </div>
          <div class="custom-modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelImport">Cancel</button>
              <button type="submit" class="btn btn-primary" id="submitImport">Import</button>
          </div>
      </form>
  </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
  
    $('#customerSearch').on('keyup', function () {
      let searchTerm = $(this).val();

      $.ajax({
        url: "{{ route('admin.customer.search') }}",
        type: "GET",
        data: { search: searchTerm },
        success: function (response) {
          $('#customerTableBody').html(response.customers);
          $('.pagination').html(response.pagination);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
        }
      });
    });
    $(document).on('click', '.pagination a', function (e) {
      e.preventDefault();
      let url = $(this).attr('href');

      $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
          $('#customerTableBody').html(response.customers);
          $('.pagination').html(response.pagination);
        }
      });
    });

    // Modal handling
    const showImportModal = () => {
        $('#modalBackdrop').css('display', 'block');
        $('#importModal').css('display', 'block');
    };

    const hideImportModal = () => {
        $('#modalBackdrop').css('display', 'none');
        $('#importModal').css('display', 'none');
    };

    $('#showImportModal').click(function() {
        showImportModal();
    });

    $('#closeModal, #cancelImport, #modalBackdrop').click(function() {
        hideImportModal();
    });

    // Prevent modal close when clicking inside the modal
    $('#importModal').click(function(e) {
        e.stopPropagation();
    });

    // Form submission
    $('#importForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $('#submitImport');
        const originalText = submitBtn.html();
        
        // Disable button and show loading
        submitBtn.html('<span class="spinner"></span> Importing...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('admin.customer.import') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    // Show success toastr message (if available)
                    if (typeof toastr !== 'undefined') {
                        toastr.success(response.message);
                    } else {
                        alert(response.message);
                    }
                    
                    hideImportModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message);
                    } else {
                        alert(response.message);
                    }
                    
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error importing customers. Please try again.';
                
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        errorMessage = errors[field][0];
                        break;
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMessage);
                } else {
                    alert(errorMessage);
                }
                
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
  });
</script>
@endsection

<style>
/* Custom Modal Styles */
.custom-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 1000;
}

.custom-modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    width: 500px;
    max-width: 95%;
    z-index: 1001;
    display: none;
}

.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.custom-modal-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.custom-modal-close {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #666;
}

.custom-modal-body {
    padding: 20px;
}

.custom-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    margin-bottom: 8px;
}

.alert-info {
    background-color: #e8f4fd;
    border: 1px solid #c2e0ff;
    border-radius: 4px;
    padding: 12px;
    margin: 15px 0;
}

.alert-info h6 {
    margin-top: 0;
    margin-bottom: 8px;
    color: #0c5898;
    font-size: 14px;
}

.alert-info ul {
    margin: 0;
    padding-left: 20px;
}

/* Spinner for loading state */
.spinner {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-right: 6px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
