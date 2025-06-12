@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/manage-vendor.css') }}">
<style>
    /* Container for the entire form */
    .invoice-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    
    /* Card styling */
    .card {
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        background-color: #fff;
    }
    
    .card-header {
        padding: 15px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        display: flex;
        align-items: center;
    }
    
    .card-header h4 {
        margin: 0;
        font-size: 18px;
        color: #333;
        font-weight: 600;
    }
    
    .card-header i {
        margin-right: 10px;
        color: #4a6fdc;
    }
    
    .card-body {
        padding: 20px;
    }
    
    /* Form group and controls */
    .form-group {
        margin-bottom: 18px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 14px;
        color: #444;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    
    .form-control:focus {
        outline: 0;
        border-color: #4a6fdc;
        box-shadow: 0 0 0 0.2rem rgba(74, 111, 220, 0.25);
    }
    
    .form-control.is-invalid {
        border-color: #dc3545;
    }
    
    .form-control[readonly] {
        background-color: #f9f9f9;
        cursor: not-allowed;
    }
    
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }
    
    /* Improved grid system */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .col-md-12, .col-md-6, .col-md-4, .col-md-3 {
        padding-left: 10px;
        padding-right: 10px;
        box-sizing: border-box;
    }
    
    .col-md-12 {
        width: 100%;
    }
    
    .col-md-6 {
        width: 50%;
    }
    
    .col-md-4 {
        width: 33.333333%;
    }
    
    .col-md-3 {
        width: 25%;
    }
    
    /* Button styling */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.5rem 1rem;
        font-size: 14px;
        line-height: 1.5;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        gap: 6px;
    }
    
    .btn-primary {
        color: #fff;
        background-color: #4a6fdc;
        border-color: #4a6fdc;
    }
    
    .btn-primary:hover {
        background-color: #375bc8;
        border-color: #3356b9;
    }
    
    .btn-secondary {
        color: #fff;
        background-color: var(--deep-blue);
        border-color: #6c757d;
    }
    
    /* Section spacing and misc */
    .mt-3 {
        margin-top: 15px;
    }
    
    .submit-section {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    /* Section dividers */
    .section-divider {
        height: 1px;
        background-color: #eee;
        margin: 15px 0;
    }
    
    /* Responsive fixes */
    @media (max-width: 768px) {
        .col-md-6, .col-md-4, .col-md-3 {
            width: 100%;
            margin-bottom: 15px;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Create New Invoice</h1>
    <div class="header-actions">
        <a href="{{ route('admin.invoice.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
    </div>
</div>

<form id="invoiceForm">
    @csrf
    
    <!-- Customer Selection Section -->
    <div class="card">
        <div class="card-header">
            <h4>Customer Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer_id">Select Customer</label>
                        <select id="customer_id" name="customer_id" class="form-control" required>
                            <option value="">-- Select Customer --</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="customer_id_error"></div>
                    </div>
                </div>
            </div>
            
            <div id="customerDetails" style="display: none;">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <input type="text" id="customer_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>GST Number</label>
                            <input type="text" id="customer_gst" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Address</label>
                            <textarea id="customer_address" class="form-control" readonly rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Product Selection Section -->
    <div id="productSelectionCard" class="card" style="display: none;">
        <div class="card-header">
            <h4>Product Selection</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="micron">Select Micron</label>
                        <select id="micron_dropdown" name="micron_dropdown" class="form-control" required>
                            <option value="">-- Select Micron --</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="micron_dropdown_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="size_dropdown">Select Size</label>
                        <select id="size_dropdown" name="size_dropdown" class="form-control" required disabled>
                            <option value="">-- Select Size --</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="size_dropdown_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inventory_item">Available Products</label>
                        <select id="inventory_item" name="inventory_item" class="form-control" required disabled>
                            <option value="">-- Select Product --</option>
                            <!-- Will be populated via AJAX -->
                        </select>
                        <div class="invalid-feedback" id="inventory_item_error"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Selected Item Details Section -->
    <div id="selectedItemCard" class="card" style="display: none;">
        <div class="card-header">
            <h4>Product Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="hsn">HSN</label>
                        <input type="text" id="hsn" name="hsn" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="available_quantity">Available Quantity (kg)</label>
                        <input type="text" id="available_quantity" class="form-control" readonly>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="quantity">Quantity to Invoice (kg)</label>
                        <input type="number" step="0.01" id="quantity" name="quantity" class="form-control" required>
                        <div class="invalid-feedback" id="quantity_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="price_per_kg">Price per kg</label>
                        <input type="number" step="0.01" id="price_per_kg" name="price_per_kg" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="total_price">Total Price</label>
                        <input type="number" step="0.01" id="total_price" name="total_price" class="form-control" readonly>
                    </div>
                </div>
            </div>
            
            <!-- Hidden fields -->
            <input type="hidden" id="micron" name="micron">
            <input type="hidden" id="size" name="size">
        </div>
    </div>
    
    <!-- Tax Information Section -->
    <div id="taxCard" class="card" style="display: none;">
        <div class="card-header">
            <h4>Tax Information</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="cgst">CGST (%)</label>
                        <input type="number" step="0.01" id="cgst" name="cgst" class="form-control" value="0">
                        <div class="invalid-feedback" id="cgst_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sgst">SGST (%)</label>
                        <input type="number" step="0.01" id="sgst" name="sgst" class="form-control" value="0">
                        <div class="invalid-feedback" id="sgst_error"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="igst">IGST (%)</label>
                        <input type="number" step="0.01" id="igst" name="igst" class="form-control" value="0">
                        <div class="invalid-feedback" id="igst_error"></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tax_amount">Tax Amount</label>
                        <input type="number" step="0.01" id="tax_amount" name="tax_amount" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="final_price">Final Price (incl. tax)</label>
                        <input type="number" step="0.01" id="final_price" name="final_price" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Submit Section -->
    <div id="submitSection" class="submit-section" style="display: none;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Create Invoice
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let selectedInventory = null;
        
        // Load customers on page load
        loadCustomers();
        
        // Load customers function
        function loadCustomers() {
            $.ajax({
                url: "{{ route('admin.get-customers') }}",
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        let options = '<option value="">-- Select Customer --</option>';
                        response.data.forEach(function(customer) {
                            options += `<option value="${customer.id}">${customer.customer_name}</option>`;
                        });
                        $('#customer_id').html(options);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading customers:', xhr);
                }
            });
        }
        
        // Customer selection change handler
        $('#customer_id').on('change', function() {
            const customerId = $(this).val();
            
            if (!customerId) {
                $('#customerDetails').hide();
                $('#productSelectionCard, #selectedItemCard, #taxCard, #submitSection').hide();
                return;
            }
            
            // Get customer details
            $.ajax({
                url: "{{ route('admin.get-customer-details') }}",
                type: "GET",
                data: { customer_id: customerId },
                success: function(response) {
                    if (response.status) {
                        const customer = response.data;
                        
                        // Populate customer details
                        $('#customer_name').val(customer.customer_name || '');
                        $('#customer_address').val(customer.address || '');
                        $('#customer_gst').val(customer.gstin || 'N/A');
                        
                        // Show customer details and product selection sections
                        $('#customerDetails').show();
                        $('#productSelectionCard').show();
                        
                        // Load micron values for this customer
                        loadMicronValues(customerId);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading customer details:', xhr);
                }
            });
        });
        
        // Load micron values for selected customer
        function loadMicronValues(customerId) {
            $.ajax({
                url: "{{ route('admin.get-micron-values') }}",
                type: "GET",
                data: { customer_id: customerId },
                success: function(response) {
                    if (response.status) {
                        let options = '<option value="">-- Select Micron --</option>';
                        response.data.forEach(function(micron) {
                            options += `<option value="${micron}">${micron}</option>`;
                        });
                        $('#micron_dropdown').html(options);
                    } else {
                        $('#micron_dropdown').html('<option value="">No micron values available</option>');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading micron values:', xhr);
                }
            });
        }
        
        // Micron selection change handler
        $('#micron_dropdown').on('change', function() {
            const customerId = $('#customer_id').val();
            const micron = $(this).val();
            
            // Reset subsequent dropdowns
            $('#size_dropdown').html('<option value="">-- Select Size --</option>').prop('disabled', true);
            $('#inventory_item').html('<option value="">-- Select Product --</option>').prop('disabled', true);
            $('#selectedItemCard, #taxCard, #submitSection').hide();
            
            if (!micron) {
                return;
            }
            
            // Load sizes for selected customer and micron
            $.ajax({
                url: "{{ route('admin.get-sizes-by-micron') }}",
                type: "GET",
                data: { 
                    customer_id: customerId,
                    micron: micron
                },
                success: function(response) {
                    if (response.status) {
                        let options = '<option value="">-- Select Size --</option>';
                        response.data.forEach(function(size) {
                            options += `<option value="${size}">${size}</option>`;
                        });
                        $('#size_dropdown').html(options).prop('disabled', false);
                    } else {
                        $('#size_dropdown').html('<option value="">No sizes available</option>').prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading sizes:', xhr);
                }
            });
        });
        
        // Size selection change handler
        $('#size_dropdown').on('change', function() {
            const customerId = $('#customer_id').val();
            const micron = $('#micron_dropdown').val();
            const size = $(this).val();
            
            // Reset product dropdown
            $('#inventory_item').html('<option value="">-- Select Product --</option>').prop('disabled', true);
            $('#selectedItemCard, #taxCard, #submitSection').hide();
            
            if (!size) {
                return;
            }
            
            // Load inventory items for selected customer, micron and size
            $.ajax({
                url: "{{ route('admin.get-inventory-items') }}",
                type: "GET",
                data: {
                    customer_id: customerId,
                    micron: micron,
                    size: size
                },
                success: function(response) {
                    if (response.status && response.data.length > 0) {
                        let options = '<option value="">-- Select Product --</option>';
                        response.data.forEach(function(item) {
                            const sourceLabel = item.is_semi_inventory ? ' [Semi Inventory]' : '';
                            options += `<option value="${item.id}" data-item='${JSON.stringify(item)}'>
                                ${item.description || 'N/A'} - ${parseFloat(item.quantity).toFixed(2)}kg available${sourceLabel}
                            </option>`;
                        });
                        $('#inventory_item').html(options).prop('disabled', false);
                    } else {
                        $('#inventory_item').html('<option value="">No products available</option>').prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    console.error('Error loading inventory items:', xhr);
                }
            });
        });
        
        // Inventory item selection handler
        $('#inventory_item').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            if (!selectedOption.val()) {
                $('#selectedItemCard, #taxCard, #submitSection').hide();
                return;
            }
            
            try {
                const itemData = selectedOption.data('item');
                selectInventoryItem(itemData);
            } catch (e) {
                console.error('Error parsing item data:', e);
            }
        });
        
        // Select inventory item
        function selectInventoryItem(item) {
            selectedInventory = item;
            
            // Populate form fields
            $('#hsn').val(item.hsn || '');
            $('#description').val(item.description || '');
            $('#micron').val(item.micron || '');
            $('#size').val(item.size || '');
            $('#available_quantity').val(parseFloat(item.quantity).toFixed(2));
            $('#price_per_kg').val(parseFloat(item.price_per_kg).toFixed(2));
            
            // Add hidden field for product_id if it's a semi inventory item
            if (item.is_semi_inventory) {
                if ($('#product_id').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'product_id',
                        name: 'product_id',
                        value: item.product_id
                    }).appendTo('#invoiceForm');
                } else {
                    $('#product_id').val(item.product_id);
                }
                
                // Add a hidden field to indicate this is from semi inventory
                if ($('#is_semi_inventory').length === 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'is_semi_inventory',
                        name: 'is_semi_inventory',
                        value: '1'
                    }).appendTo('#invoiceForm');
                }
            } else {
                $('#product_id, #is_semi_inventory').remove();
            }
            $('#selectedItemCard, #taxCard, #submitSection').show();

            resetTaxFields();
            $('#quantity').val('').focus();
            calculateTotals();
        }

        function resetTaxFields() {
            $('#cgst, #sgst, #igst').val(0).prop('disabled', false);
            $('#cgst_error, #sgst_error, #igst_error').hide();
        }
        
        // Calculate totals based on quantity and tax
        function calculateTotals() {
            const quantity = parseFloat($('#quantity').val()) || 0;
            const pricePerKg = parseFloat($('#price_per_kg').val()) || 0;
            const cgst = parseFloat($('#cgst').val()) || 0;
            const sgst = parseFloat($('#sgst').val()) || 0;
            const igst = parseFloat($('#igst').val()) || 0;
            
            // Calculate total price
            const totalPrice = quantity * pricePerKg;
            $('#total_price').val(totalPrice.toFixed(2));
            
            // Calculate tax amount
            const taxPercentage = cgst + sgst + igst;
            const taxAmount = (totalPrice * taxPercentage) / 100;
            $('#tax_amount').val(taxAmount.toFixed(2));
            
            // Calculate final price
            const finalPrice = totalPrice + taxAmount;
            $('#final_price').val(finalPrice.toFixed(2));
        }
        
        // Handle tax input logic - NEW FUNCTION
        function handleTaxInputs(sourceField) {
            const cgstVal = parseFloat($('#cgst').val()) || 0;
            const sgstVal = parseFloat($('#sgst').val()) || 0;
            const igstVal = parseFloat($('#igst').val()) || 0;
            
            // If IGST has value, disable CGST and SGST
            if ((sourceField === 'igst' || sourceField === 'final') && igstVal > 0) {
                $('#cgst, #sgst').val(0).prop('disabled', true);
                $('#cgst_error, #sgst_error').hide();
            } 
            // If CGST or SGST has value, disable IGST
            else if ((sourceField === 'cgst' || sourceField === 'sgst' || sourceField === 'final') && (cgstVal > 0 || sgstVal > 0)) {
                $('#igst').val(0).prop('disabled', true);
                $('#igst_error').hide();
            } 
            // If all values are 0, enable all fields
            else if (cgstVal === 0 && sgstVal === 0 && igstVal === 0) {
                $('#cgst, #sgst, #igst').prop('disabled', false);
            }
            
            // Recalculate totals
            calculateTotals();
        }
        
        // CGST input handler
        $('#cgst').on('input', function() {
            handleTaxInputs('cgst');
            validateTaxField('cgst');
        });
        
        // SGST input handler
        $('#sgst').on('input', function() {
            handleTaxInputs('sgst');
            validateTaxField('sgst');
        });
        
        // IGST input handler
        $('#igst').on('input', function() {
            handleTaxInputs('igst');
            validateTaxField('igst');
        });
        
        // Validate quantity against available stock
        function validateQuantity() {
            const quantity = parseFloat($('#quantity').val()) || 0;
            const availableQuantity = parseFloat($('#available_quantity').val()) || 0;
            
            if (quantity <= 0) {
                $('#quantity_error').text('Quantity must be greater than zero').show();
                return false;
            }
            
            if (quantity > availableQuantity) {
                $('#quantity_error').text('Quantity exceeds available stock').show();
                return false;
            }
            
            $('#quantity_error').hide();
            return true;
        }
        
        // Quantity input handler
        $('#quantity').on('input', function() {
            calculateTotals();
            validateQuantity();
        });
        
        // Validate tax field
        function validateTaxField(field) {
            const value = parseFloat($(`#${field}`).val()) || 0;
            if (value < 0 || value > 100) {
                $(`#${field}_error`).text('Value must be between 0 and 100').show();
                return false;
            } else {
                $(`#${field}_error`).hide();
                return true;
            }
        }
        
        // Form submission - update this function
        $('#invoiceForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate quantity
            if (!validateQuantity()) {
                return false;
            }
            
            // Validate tax fields
            let isValid = true;
            ['cgst', 'sgst', 'igst'].forEach(function(field) {
                if (!$(`#${field}`).prop('disabled') && !validateTaxField(field)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                return false;
            }
            
            // Enable disabled fields temporarily for submission
            // This ensures all fields are included in the form data
            $('#cgst, #sgst, #igst').prop('disabled', false);
            
            // Create form data manually to ensure all values are included
            const formData = $(this).serialize();
            
            // Re-disable fields as needed
            handleTaxInputs('final');
            
            // Submit form
            $.ajax({
                url: "{{ route('admin.invoice.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.status) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        
                        // Redirect to index page after success
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.invoice.index') }}";
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        
                        // Display validation errors
                        $.each(errors, function(field, messages) {
                            $(`#${field}_error`).text(messages[0]).show();
                        });
                        
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Please check the form for errors.');
                        } else {
                            alert('Please check the form for errors.');
                        }
                    } else {
                        console.error('Error response:', xhr.responseText);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('An error occurred while creating the invoice.');
                        } else {
                            alert('An error occurred while creating the invoice.');
                        }
                    }
                }
            });
        });
    });
</script>
@endsection