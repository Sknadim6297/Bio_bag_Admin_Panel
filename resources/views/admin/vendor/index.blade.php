@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
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

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-text {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
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

.btn {
    padding: 8px 16px;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
}

.btn-outline-primary {
    background-color: transparent;
    border-color: #0d6efd;
    color: #0d6efd;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    color: white;
}

.import-export-buttons {
    margin-bottom: 15px;
}

.import-export-buttons button,
.import-export-buttons a {
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
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
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Vendor</h1>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0">Total Vendors: <strong>{{ $totalVendors }}</strong></p>

    <a href="{{route('admin.vendors.create')}}"><button class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Add Vendor
      </button></a>
  </div>
  <!-- Replace Bootstrap Import/Export Buttons -->
<div class="import-export-buttons mb-3">
  <button type="button" class="btn btn-primary" id="showImportModal">
      <i class="fas fa-file-import"></i> Import Vendors
  </button>
  <a href="{{ route('admin.vendors.export-template') }}" class="btn btn-outline-primary">
      <i class="fas fa-download"></i> Download Template
  </a>
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
      <label for="vendorSearch" class="me-2 mb-0">Search:</label>
      <input type="text" id="vendorSearch" class="form-control d-inline-block w-50"
  placeholder="Search by name or code" />

    </div>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>Sl No</th>
        <th>Vendor Name</th>
        <th>Vendor Code</th>
        <th>Mobile Number</th>
        <th>Payment Term</th>
        <th>Address</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="vendorTableBody">
      @forelse($vendors as $index => $vendor)
      <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $vendor->vendor_name }}</td>
          <td>{{ $vendor->vendor_code }}</td>
          <td>{{ $vendor->mobile_number }}</td>
          <td>{{ $vendor->payment_terms }}</td>
          <td>{{ $vendor->address }}</td>
          <td>
              @if($vendor->status == 'active')
                  <span class="badge bg-success">Active</span>
              @else
                  <span class="badge bg-secondary">Inactive</span>
              @endif
          </td>
          <td>
              <div class="action-buttons">
                  <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                      <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="{{ route('admin.vendors.destroy', $vendor->id) }}" title="Delete">
                    <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                </a>
              </div>
          </td>
      </tr>
      @empty
      <tr><td colspan="8" class="text-center">No vendors found.</td></tr>
      @endforelse
  </tbody>
  
  </table>
</div>

<!-- Pagination Controls -->
<div class="pagination-controls d-flex justify-content-between align-items-center mt-3">
  <div class="showing-entries">
    Showing <span>1</span> to <span>2</span> of <span>4</span> entries
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



<!-- Custom Modal (No Bootstrap Required) -->
<div class="custom-modal-backdrop" id="modalBackdrop"></div>
<div class="custom-modal" id="importModal">
    <div class="custom-modal-header">
        <h5 class="custom-modal-title">Import Vendors</h5>
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
                    <li>vendor_name</li>
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
    $('#vendorSearch').on('keyup', function () {
  let searchTerm = $(this).val();
  $.ajax({
    url: "{{ route('admin.vendor.search') }}", 
    method: 'GET',
    data: {
      search: searchTerm
    },
    success: function (response) {
      let rows = '';

      if (response.data && response.data.length > 0) {
        response.data.forEach(function (vendor, index) {
          rows += `
            <tr>
              <td>${index + 1}</td>
              <td>${vendor.vendor_name}</td>
              <td>${vendor.vendor_code}</td>
              <td>${vendor.mobile_number}</td>
              <td>${vendor.payment_terms}</td>
              <td>${vendor.address}</td>
              <td>
                <span class="badge ${vendor.status == 'active' ? 'bg-success' : 'bg-secondary'}">
                  ${vendor.status.charAt(0).toUpperCase() + vendor.status.slice(1)}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="${vendor.edit_url}" class="btn btn-sm btn-primary me-1" title="Edit">
                    <i class="fas fa-edit"></i><span class="action-text">Edit</span>
                  </a>
                  <a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="${vendor.delete_url}" title="Delete">
                    <i class="fas fa-trash-alt"></i><span class="action-text">Delete</span>
                  </a>
                </div>
              </td>
            </tr>
          `;
        });
      } else {
        rows = `<tr><td colspan="8" class="text-center text-muted">No data found</td></tr>`;
      }

      $('#vendorTableBody').html(rows);
    },
    error: function(xhr, status, error) {
      console.log("AJAX Error:", xhr.responseText);
    }
  });
});

  });
</script>

<script>
    $(document).ready(function() {
        // Initialize toastr options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        // Custom Modal Functions
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
                url: '{{ route("admin.vendors.import") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success) {
                        // Show success toastr message
                        toastr.success(response.message);
                        hideImportModal();
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        // Show error toastr message
                        toastr.error(response.message);
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            // Show validation error toastr
                            toastr.error(errors[field][0]);
                            break;
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        // Show error message from server
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        // Show generic error
                        toastr.error('Error importing vendors. Please try again.');
                    }
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // Add a global AJAX error handler
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 500) {
                toastr.error('Server error occurred. Please try again later.');
            } else if (xhr.status === 404) {
                toastr.error('The requested resource could not be found.');
            } else if (xhr.status === 403) {
                toastr.error('You do not have permission to perform this action.');
            }
        });

        // Check if there's a flash message from the server
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    });
</script>
@endsection
