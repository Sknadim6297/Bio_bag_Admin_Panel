@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<style>
    .form-group.required label:after {
        content: " *";
        color: red;
    }
    .inventory-form {
        max-width: 800px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Edit Inventory Item</h1>
    <div class="header-actions">
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4>Inventory Details</h4>
    </div>
    <div class="card-body">
        <form id="inventoryForm" class="inventory-form">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required">
                        <label for="customer_id">Select Customer</label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $inventory->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->customer_name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="customer_id_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="micron">Micron</label>
                        <input type="text" id="micron" name="micron" class="form-control" value="{{ $inventory->micron }}">
                        <div class="invalid-feedback" id="micron_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="size">Size</label>
                        <input type="text" id="size" name="size" class="form-control" value="{{ $inventory->size }}">
                        <div class="invalid-feedback" id="size_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quantity">Quantity (kg)</label>
                        <input type="number" step="0.01" id="quantity" name="quantity" class="form-control" value="{{ $inventory->quantity }}">
                        <div class="invalid-feedback" id="quantity_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="hsn">HSN Code</label>
                        <input type="text" id="hsn" name="hsn" class="form-control" value="{{ $inventory->hsn }}">
                        <div class="invalid-feedback" id="hsn_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group required">
                        <label for="price_per_kg">Price per kg</label>
                        <input type="number" step="0.01" id="price_per_kg" name="price_per_kg" class="form-control" required value="{{ $inventory->price_per_kg }}">
                        <div class="invalid-feedback" id="price_per_kg_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" class="form-control" value="{{ $inventory->description }}">
                        <div class="invalid-feedback" id="description_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-group mt-4 text-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Inventory
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // When customer is selected, fetch final output data
        $('#customer_id').on('change', function() {
            const customerId = $(this).val();
            if (!customerId) {
                clearFields();
                return;
            }
            
            fetchFinalOutputData(customerId);
        });
        
        // Handle form submission
        $('#inventoryForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset any previous error messages
            $('.is-invalid').removeClass('is-invalid');
            
            // Gather form data
            const formData = $(this).serialize();
            
            // Submit form using AJAX
            $.ajax({
                url: "{{ route('admin.inventory.update', $inventory->id) }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.inventory.index') }}";
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        
                        // Display validation errors
                        $.each(errors, function(field, messages) {
                            const inputField = $(`#${field}`);
                            const errorDisplay = $(`#${field}_error`);
                            
                            inputField.addClass('is-invalid');
                            errorDisplay.text(messages[0]);
                        });
                        
                        toastr.error('Please check the form for errors.');
                    } else {
                        toastr.error('An error occurred while updating the inventory.');
                    }
                }
            });
        });
        
        // Function to fetch final output data
        function fetchFinalOutputData(customerId) {
            $.ajax({
                url: `{{ url('admin/fetch-final-output') }}/${customerId}`,
                type: "GET",
                beforeSend: function() {
                    // Show loading indicator
                    $('#micron, #size, #quantity').val('Loading...');
                },
                success: function(response) {
                    if (response.status) {
                        // Fill the form fields with fetched data
                        $('#micron').val(response.data.micron);
                        $('#size').val(response.data.size);
                        $('#quantity').val(response.data.quantity);
                    } else {
                        clearFields();
                        toastr.warning(response.message);
                    }
                },
                error: function() {
                    clearFields();
                    toastr.error('Failed to fetch final output data.');
                }
            });
        }
        
        // Function to clear auto-populated fields
        function clearFields() {
            $('#micron, #size, #quantity').val('');
        }
    });
</script>
@endsection