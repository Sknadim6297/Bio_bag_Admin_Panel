@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<style>

    .action-btns {
        display: flex;
        gap: 5px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
    }
    
    .table th, .table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }
    
    .table thead th {
        color: white;
        font-weight: bold;
        text-align: left;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }
     
    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
    
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    
    .modal-dialog {
        position: relative;
        width: auto;
        margin: 1.75rem auto;
        max-width: 500px;
    }
    
    .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        background-color: #fff;
        border-radius: 0.3rem;
        outline: 0;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-title {
        margin: 0;
        font-size: 1.25rem;
    }
    
    .close {
        background: transparent;
        border: 0;
        font-size: 1.5rem;
        cursor: pointer;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 1rem;
        border-top: 1px solid #dee2e6;
        gap: 0.5rem;
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Search bar styles */
    .search-container {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .search-input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .search-btn {
        padding: 10px 15px;
        background-color: var(--deep-blue);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .reset-btn {
        padding: 10px 15px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .reset-btn:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Inventory Management</h1>
    <div class="header-actions">
        <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Inventory
        </a>
    </div>
</div>

<!-- HSN Search Bar -->
<div class="search-container">
    <input type="text" id="hsnSearch" class="search-input" placeholder="Search by HSN number..." value="{{ request('hsn') }}">
    <button id="searchBtn" class="search-btn"><i class="fas fa-search"></i> Search</button>
    <button id="resetBtn" class="reset-btn"><i class="fas fa-undo"></i> Reset</button>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Micron</th>
                <th>Size</th>
                <th>Quantity (kg)</th>
                <th>HSN</th>
                <th>Description of Goods</th>
                <th>Price/kg</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="inventoryTableBody">
            @forelse($inventories as $inventory)
            <tr>
                <td>{{ $inventory->id }}</td>
                <td>{{ $inventory->customer->customer_name ?? 'N/A' }}</td>
                <td>{{ $inventory->micron ?? 'N/A' }}</td>
                <td>{{ $inventory->size ?? 'N/A' }}</td>
                <td>{{ number_format($inventory->quantity, 2) }}</td>
                <td>{{ $inventory->hsn ?? 'N/A' }}</td>
                <td>{{ $inventory->description ?? 'N/A' }}</td>
                <td>{{ number_format($inventory->price_per_kg, 2) }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.inventory.edit', $inventory->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                            data-id="{{ $inventory->id }}" 
                            data-customer="{{ $inventory->customer->customer_name ?? 'Unknown' }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">No inventory records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Custom modal without Bootstrap -->
<div id="deleteModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this inventory record for <span id="deleteItemName"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelDelete">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const modal = document.getElementById('deleteModal');
        
        // Handle search button click
        $('#searchBtn').on('click', function() {
            performSearch();
        });
        
        // Handle enter key press in search input
        $('#hsnSearch').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                performSearch();
            }
        });
        
        // Handle reset button click
        $('#resetBtn').on('click', function() {
            $('#hsnSearch').val('');
            performSearch();
        });
        
        // Function to perform search
        function performSearch() {
            const hsnValue = $('#hsnSearch').val().trim();
            
            $.ajax({
                url: "{{ route('admin.inventory.index') }}",
                type: "GET",
                data: { hsn: hsnValue },
                success: function(response) {
                    // Extract just the tbody content from the response
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = response;
                    const newTbody = tempDiv.querySelector('#inventoryTableBody').innerHTML;
                    
                    // Replace the current tbody with the new one
                    $('#inventoryTableBody').html(newTbody);
                    
                    // Reattach event handlers to new elements
                    attachDeleteHandlers();
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        if (hsnValue) {
                            toastr.success('Search results for HSN: ' + hsnValue);
                        } else {
                            toastr.info('Showing all inventory records');
                        }
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Error searching inventory records');
                    } else {
                        alert('Error searching inventory records');
                    }
                }
            });
        }
        
        // Function to attach delete handlers to buttons
        function attachDeleteHandlers() {
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const customerName = $(this).data('customer');
                
                $('#deleteItemName').text(customerName);
                $('#deleteForm').attr('action', `{{ url('admin/inventory') }}/${id}`);
                
                // Show modal without Bootstrap
                modal.style.display = 'block';
            });
        }
        
        // Initial attachment of delete handlers
        attachDeleteHandlers();
        
        // Close modal when clicking the close button
        $('#closeModal, #cancelDelete').on('click', function() {
            modal.style.display = 'none';
        });
        
        // Close modal when clicking outside of it
        $(window).on('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
        
        // Handle delete form submission
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const url = form.attr('action');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.status) {
                        modal.style.display = 'none';
                        
                        // Display success message (assuming you have a notification system)
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        
                        // Reload page after success
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    // Display error message
                    if (typeof toastr !== 'undefined') {
                        toastr.error('An error occurred while deleting the record.');
                    } else {
                        alert('An error occurred while deleting the record.');
                    }
                }
            });
        });
    });
</script>
@endsection