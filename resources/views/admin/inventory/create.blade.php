@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<style>
    /* Enhanced Form Styling */
    .form-group.required label:after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
    }
    
    .inventory-form {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        border: none;
    }
    
    .card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .card-header {
        background-color: #4a6fdc;
        color: white;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 15px 20px;
        font-weight: 600;
        position: relative;
    }
    
    .card-header h4 {
        margin: 0;
        font-size: 20px;
    }
    
    .card-header:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(to right, #4a6fdc, #5d80ed);
    }
    
    .card-body {
        padding: 25px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 15px;
    }
    
    .form-control {
        height: 45px;
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        font-size: 15px;
    }
    
    .form-control:focus {
        border-color: #4a6fdc;
        box-shadow: 0 0 0 0.25rem rgba(74, 111, 220, 0.25);
    }
    
    .form-control:disabled, .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 0.7;
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        padding-right: calc(1.5em + 0.75rem);
    }
    
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
    
    .is-invalid ~ .invalid-feedback {
        display: block;
    }
    
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e1e1e1;
    }
    
    .dashboard-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .btn {
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-primary {
        background-color: #4a6fdc;
        border-color: #4a6fdc;
        color: white;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #375bc8;
        border-color: #375bc8;
        box-shadow: 0 0 0 0.25rem rgba(74, 111, 220, 0.25);
        transform: translateY(-1px);
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover, .btn-secondary:focus {
        background-color: #5a6268;
        border-color: #5a6268;
        box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.25);
        transform: translateY(-1px);
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    
    .col-md-4, .col-md-6 {
        padding: 0 10px;
        box-sizing: border-box;
    }
    
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    /* Input field icon and effects */
    .form-group {
        position: relative;
    }
    
    .form-group i.field-icon {
        position: absolute;
        top: 43px;
        left: 15px;
        color: #6c757d;
    }
    
    .form-group.with-icon .form-control {
        padding-left: 40px;
    }
    
    .form-control::placeholder {
        color: #adb5bd;
        opacity: 1;
    }
    
    /* Nice animation effect */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .card {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    /* Customer select field styling */
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 40px;
    }
    
    /* Status colors for fields */
    .read-only-field {
        background-color: #f8f9fa;
        border-left: 4px solid #17a2b8;
    }
    
    .required-field {
        border-left: 4px solid #dc3545;
    }
    
    /* Form submit button section */
    .form-submit-section {
        padding-top: 20px;
        margin-top: 30px;
        border-top: 1px solid #e1e1e1;
        text-align: center;
    }
    
    /* Mobile responsive fixes */
    @media (max-width: 768px) {
        .col-md-4, .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .header-actions {
            width: 100%;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Add New Inventory Item</h1>
    <div class="header-actions">
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-boxes"></i> Inventory Details</h4>
    </div>
    <div class="card-body">
        <form id="inventoryForm" class="inventory-form">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required">
                        <label for="customer_id">
                            <i class="fas fa-user"></i> Select Customer
                        </label>
                        <select name="customer_id" id="customer_id" class="form-control" required>
                            <option value="">-- Select Customer --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="customer_id_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="micron">
                            <i class="fas fa-ruler-vertical"></i> Micron
                        </label>
                        <input type="text" id="micron" name="micron" class="form-control read-only-field" readonly placeholder="Auto-filled from production data">
                        <div class="invalid-feedback" id="micron_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="size">
                            <i class="fas fa-ruler-combined"></i> Size
                        </label>
                        <input type="text" id="size" name="size" class="form-control read-only-field" readonly placeholder="Auto-filled from production data">
                        <div class="invalid-feedback" id="size_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quantity">
                            <i class="fas fa-weight"></i> Quantity (kg)
                        </label>
                        <input type="number" step="0.01" id="quantity" name="quantity" class="form-control read-only-field" readonly placeholder="Auto-filled from production data">
                        <div class="invalid-feedback" id="quantity_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="hsn">
                            <i class="fas fa-hashtag"></i> HSN Code
                        </label>
                        <input type="text" id="hsn" name="hsn" class="form-control" placeholder="Enter HSN code">
                        <div class="invalid-feedback" id="hsn_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group required">
                        <label for="price_per_kg">
                            <i class="fas fa-rupee-sign"></i> Price per kg
                        </label>
                        <input type="number" step="0.01" id="price_per_kg" name="price_per_kg" class="form-control required-field" required placeholder="Enter price per kg">
                        <div class="invalid-feedback" id="price_per_kg_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">
                            <i class="fas fa-align-left"></i> Description of goods
                        </label>
                        <input type="text" id="description" name="description" class="form-control" placeholder="Brief product description">
                        <div class="invalid-feedback" id="description_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-submit-section">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Inventory
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
        
        // Add nice visual feedback when fields are filled
        $('input, select').on('focus', function() {
            $(this).closest('.form-group').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.form-group').removeClass('focused');
            
            // Add a "filled" class if the field has a value
            if ($(this).val()) {
                $(this).addClass('filled');
            } else {
                $(this).removeClass('filled');
            }
        });
        
        // Handle form submission
        $('#inventoryForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset any previous error messages
            $('.is-invalid').removeClass('is-invalid');
            
            // Disable submit button and show processing state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-sync fa-spin"></i> Processing...');
            
            // Gather form data
            const formData = $(this).serialize();
            
            // Submit form using AJAX
            $.ajax({
                url: "{{ route('admin.inventory.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.status) {
                        // Show success toast
                        toastr.success(response.message);
                        
                        // Add a success effect to the form
                        $('.card').addClass('save-success');
                        
                        // Change button to success state
                        submitBtn.html('<i class="fas fa-check"></i> Success!').removeClass('btn-primary').addClass('btn-success');
                        
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.inventory.index') }}";
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        
                        // Display validation errors
                        $.each(errors, function(field, messages) {
                            const inputField = $(`#${field}`);
                            const errorDisplay = $(`#${field}_error`);
                            
                            inputField.addClass('is-invalid');
                            errorDisplay.text(messages[0]);
                            
                            // Shake effect for fields with errors
                            inputField.closest('.form-group').addClass('error-shake');
                            setTimeout(() => {
                                inputField.closest('.form-group').removeClass('error-shake');
                            }, 600);
                        });
                        
                        // Scroll to first error
                        const firstError = $('.is-invalid').first();
                        if (firstError.length) {
                            $('html, body').animate({
                                scrollTop: firstError.offset().top - 100
                            }, 500);
                        }
                        
                        toastr.error('Please check the form for errors.');
                    } else {
                        toastr.error('An error occurred while saving the inventory.');
                    }
                }
            });
        });
        
        // Function to fetch final output data
        function fetchFinalOutputData(customerId) {
            // Add spinning indicator to select box
            $('#customer_id').addClass('processing');
            
            // Update field placeholders to show loading state
            $('#micron, #size, #quantity').attr('placeholder', 'Loading...').addClass('loading-field');
            
            $.ajax({
                url: `{{ url('admin/fetch-final-output') }}/${customerId}`,
                type: "GET",
                beforeSend: function() {
                    // Instead of full screen loading, just show inline indicators
                    $('#micron, #size, #quantity').val('').attr('placeholder', 'Loading data...');
                },
                success: function(response) {
                    // Remove processing state
                    $('#customer_id').removeClass('processing');
                    $('#micron, #size, #quantity').removeClass('loading-field');
                    
                    if (response.status) {
                        // Fill the form fields with fetched data
                        $('#micron').val(response.data.micron);
                        $('#size').val(response.data.size);
                        $('#quantity').val(response.data.quantity);
                        
                        // Add a subtle highlight effect to show data was loaded
                        $('#micron, #size, #quantity').addClass('highlight-success');
                        setTimeout(() => {
                            $('#micron, #size, #quantity').removeClass('highlight-success');
                        }, 1000);
                        
                        // Focus on price field for better UX
                        $('#price_per_kg').focus();
                    } else {
                        clearFields();
                        toastr.warning(response.message || 'No data found for this customer.');
                    }
                },
                error: function() {
                    // Remove processing state
                    $('#customer_id').removeClass('processing');
                    $('#micron, #size, #quantity').removeClass('loading-field');
                    
                    clearFields();
                    toastr.error('Failed to fetch final output data.');
                }
            });
        }
        
        // Function to clear auto-populated fields
        function clearFields() {
            $('#micron, #size, #quantity').val('').attr('placeholder', 'Auto-filled from production data');
        }
        
        // Add keyboard shortcuts
        $(document).keydown(function(e) {
            // Save on Ctrl+S
            if ((e.ctrlKey || e.metaKey) && e.which === 83) {
                e.preventDefault();
                $('#inventoryForm').submit();
                return false;
            }
        });
    });
</script>
@endsection