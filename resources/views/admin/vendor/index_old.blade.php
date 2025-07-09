@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
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

.custom-modal-close:hover {
    color: #000;
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

.form-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

/* Table Loading State */
.table tbody tr td {
    vertical-align: middle;
}

/* Search Input Focus */
#vendorSearch:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Action Buttons Hover Effects */
.action-buttons .btn {
    transition: all 0.3s ease;
    border-radius: 6px;
    font-weight: 500;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.action-buttons .btn-primary {
    background: linear-gradient(135deg, #123458 0%, #1e4d7a 100%);
    border-color: #123458;
}

.action-buttons .btn-primary:hover {
    background: linear-gradient(135deg, #0f2943 0%, #1a4166 100%);
    border-color: #0f2943;
}

.action-buttons .btn-danger:hover {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

/* Table Container */
.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(18, 52, 88, 0.15);
    overflow: hidden;
    margin-bottom: 20px;
    border: 1px solid rgba(18, 52, 88, 0.1);
}

.table-container .table-responsive {
    border-radius: 12px 12px 0 0;
    margin-bottom: 0;
}

.table-container .table {
    margin-bottom: 0;
    border-radius: 12px 12px 0 0;
}

.table-container .table thead th {
    border-top: none;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 12px;
    background: linear-gradient(135deg, #123458 0%, #1e4d7a 100%);
    color: #fff;
    border-bottom: 2px solid #123458;
}

.table-container .table tbody tr:last-child td {
    border-bottom: none;
}

.table-container .table tbody tr:hover {
    background-color: rgba(18, 52, 88, 0.05);
    transition: background-color 0.2s ease;
}

/* Table Footer & Pagination */
.table-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 12px 12px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.pagination-info {
    color: #123458;
    font-size: 14px;
    font-weight: 600;
    flex: 1;
}

.showing-text {
    display: inline-block;
    padding: 10px 16px;
    background: linear-gradient(135deg, #123458 0%, #1e4d7a 100%);
    border-radius: 25px;
    font-size: 13px;
    color: #fff;
    box-shadow: 0 3px 6px rgba(18, 52, 88, 0.3);
    font-weight: 500;
    letter-spacing: 0.5px;
}

.pagination-wrapper {
    display: flex;
    align-items: center;
    flex: 0 0 auto;
}

.pagination-wrapper nav {
    margin: 0;
}

/* Force horizontal pagination layout */
.pagination-wrapper .pagination {
    margin: 0;
    gap: 6px;
    display: flex !important;
    flex-direction: row !important;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    list-style: none;
}

.pagination .page-item {
    margin: 0;
    display: inline-flex !important;
    flex-direction: row !important;
}

.pagination .page-link {
    border: 2px solid #123458;
    color: #123458;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.3s ease;
    background: #fff;
    min-width: 40px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(18, 52, 88, 0.1);
    position: relative;
    overflow: hidden;
}

.pagination .page-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #123458 0%, #1e4d7a 100%);
    transition: left 0.3s ease;
    z-index: -1;
}

.pagination .page-link:hover {
    color: #fff;
    border-color: #123458;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(18, 52, 88, 0.4);
}

.pagination .page-link:hover::before {
    left: 0;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #123458 0%, #1e4d7a 100%);
    border-color: #123458;
    color: #fff;
    box-shadow: 0 4px 8px rgba(18, 52, 88, 0.5);
    font-weight: 700;
    transform: scale(1.05);
}

.pagination .page-item.active .page-link::before {
    left: 0;
}

.pagination .page-item.disabled .page-link {
    color: #adb5bd;
    pointer-events: none;
    background: #f8f9fa;
    border-color: #dee2e6;
    opacity: 0.6;
    box-shadow: none;
}

.pagination .page-link:focus {
    box-shadow: 0 0 0 3px rgba(18, 52, 88, 0.25);
    outline: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .action-text {
        display: none;
    }
    
    .custom-modal {
        width: 95%;
        margin: 10px;
    }
    
    .table-footer {
        padding: 15px;
        gap: 12px;
        flex-direction: column;
        text-align: center;
    }
    
    .pagination-info {
        font-size: 13px;
        order: 2;
        flex: none;
    }
    
    .showing-text {
        padding: 8px 14px;
        font-size: 12px;
    }
    
    .pagination-wrapper {
        order: 1;
        justify-content: center;
    }
    
    .pagination-wrapper .pagination {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .pagination .page-link {
        padding: 6px 10px;
        font-size: 13px;
        min-width: 35px;
    }
    
    .pagination .page-item.active .page-link {
        transform: scale(1.02);
    }
}
</style>
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Manage Vendor</h1>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0">Total Vendors: <strong id="totalVendors">{{ $vendors->total() }}</strong></p>
    <div>
      <a href="{{ route('admin.vendors.create') }}">
        <button class="btn btn-success">
          <i class="fas fa-plus me-2"></i>Add Vendor
        </button>
      </a>
      <button type="button" class="btn btn-primary ms-2" id="showImportModal">
        <i class="fas fa-file-import me-2"></i>Import Vendors
      </button>
      <a href="{{ route('admin.vendors.export-template') }}" class="btn btn-outline-primary ms-2">
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
      <label for="vendorSearch" class="me-2 mb-0">Search:</label>
      <input
        type="text"
        id="vendorSearch"
        class="form-control d-inline-block w-50"
        placeholder="Search by name or code"
      />
    </div>
  </div>
</div>

<div class="table-container">
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
        @foreach ($vendors as $index => $vendor)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $vendor->vendor_name }}</td>
            <td>{{ $vendor->vendor_code }}</td>
            <td>{{ $vendor->mobile_number }}</td>
            <td>{{ $vendor->payment_terms }}</td>
            <td>{{ $vendor->address }}</td>
            <td>
              <span class="badge {{ $vendor->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($vendor->status) }}
              </span>
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
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Pagination Section -->
  <div class="table-footer">
    <div class="pagination-info">
      <span class="showing-text">Loading...</span>
    </div>
    <div class="pagination-wrapper">
      <!-- Pagination buttons will be loaded here via AJAX -->
    </div>
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
    let currentSearch = '';
    let currentPerPage = $('#entriesSelect').val() || 10;
    
    // Initialize the page
    performSearch(1);
    
    // Handle search input
    $('#vendorSearch').on('keyup', function () {
      currentSearch = $(this).val();
      performSearch(1); // Reset to first page when searching
    });
    
    // Handle entries per page change
    $('#entriesSelect').on('change', function () {
      currentPerPage = $(this).val();
      performSearch(1); // Reset to first page when changing entries per page
    });
    
    // Handle pagination clicks
    $(document).on('click', '.pagination-wrapper a', function (e) {
      e.preventDefault();
      let url = $(this).attr('href');
      
      if (url) {
        // Extract page number from URL
        let urlParams = new URLSearchParams(url.split('?')[1] || '');
        let page = urlParams.get('page') || 1;
        performSearch(page);
      }
    });
    
    function performSearch(page = 1) {
      $.ajax({
        url: "{{ route('admin.vendor.search') }}",
        type: "GET",
        data: { 
          search: currentSearch,
          per_page: currentPerPage,
          page: page
        },
        beforeSend: function() {
          // Add loading indicator
          $('#vendorTableBody').html('<tr><td colspan="8" class="text-center">Loading...</td></tr>');
        },
        success: function (response) {
          $('#vendorTableBody').html(response.vendors);
          $('.pagination-wrapper').html(response.pagination);
          
          // Update showing entries text
          if (response.showing) {
            $('.showing-text').html(
              'Showing ' + response.showing.from + ' to ' + 
              response.showing.to + ' of ' + 
              response.showing.total + ' entries'
            );
            
            // Update total vendor count
            $('#totalVendors').text(response.showing.total);
          }
          
          // Show "No results" message if empty
          if (response.showing && response.showing.total === 0) {
            $('#vendorTableBody').html('<tr><td colspan="8" class="text-center">No vendors found matching your search criteria.</td></tr>');
            $('.pagination-wrapper').html(''); // Clear pagination when no results
            $('.showing-text').html('No entries to show');
          }
        },
        error: function (xhr) {
          console.error('Search error:', xhr.responseText);
          $('#vendorTableBody').html('<tr><td colspan="8" class="text-center text-danger">Error loading data. Please try again.</td></tr>');
          
          // Show user-friendly error message
          if (xhr.status === 500) {
            alert('Server error occurred. Please try again.');
          } else if (xhr.status === 404) {
            alert('Search endpoint not found. Please contact administrator.');
          } else {
            alert('Error occurred while searching. Please try again.');
          }
        }
      });
    }

    // Handle delete functionality
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to delete this vendor?')) {
            let deleteUrl = $(this).data('url');
            let $row = $(this).closest('tr');
            
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Refresh the search to update pagination and counts
                        performSearch();
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Vendor deleted successfully');
                        } else {
                            alert('Vendor deleted successfully');
                        }
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(response.message || 'Error deleting vendor');
                        } else {
                            alert(response.message || 'Error deleting vendor');
                        }
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Error deleting vendor. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        alert(errorMessage);
                    }
                }
            });
        }
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
            url: "{{ route('admin.vendors.import') }}",
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
                let errorMessage = 'Error importing vendors. Please try again.';
                
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
