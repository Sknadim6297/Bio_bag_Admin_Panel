@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/consumtion.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Consumption</h1>
</div>

<!-- Consumption Form -->
<form class="consumption-form" id="consumptionForm">
  <!-- Date and Time -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="consumption-date">Date</label>
              <input type="date" id="consumption-date" name="date" required >
          </div>
      </div>
      <div class="form-col">
          <div class="form-group">
              <label for="consumption-time">Time</label>
              <input type="time" id="consumption-time" required >
          </div>
      </div>
  </div>

  <!-- Product Items Container -->
  <div id="productItemsContainer">
      <!-- First Product Item (default) -->
      <div class="product-item" data-item="1">
          <button type="button" class="remove-item" style="display: none;">×</button>
          <div class="form-row">
              <div class="form-col">
                <div class="form-group">
                  <label for="product-1">Product</label>
                  <select id="product-1" name="products[]" required>
                      <option value="">Select Product</option>
                      @foreach($stocks as $stock)
                          <option value="{{ $stock->id }}">
                              {{ $stock->product_name }} 
                              @if(isset($stock->measurement))
                                  ({{ strtoupper($stock->measurement) }})
                              @endif
                          </option>
                      @endforeach
                  </select>
              </div>
              
              </div>
              <div class="form-col">
                  <div class="form-group">
                      <label for="quantity-1">Quantity</label>
                      <div class="quantity-unit-group">
                          <input type="number" id="quantity-1" min="1" required>
                          <select id="unit-1" name="units[]">
                              <option value="">Select Unit</option>
                              @foreach (['kg', 'g', 'l', 'pcs'] as $unit)
                                  <option value="{{ $unit }}">{{ strtoupper($unit) }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Total Consumption -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="total-consumption">Total Consumption</label>
              <input type="text" id="total-consumption" name="total_consumption" readonly>
          </div>
      </div>
  </div>

  <!-- Add More Button -->
  <div class="form-group">
      <button type="button" id="addMoreBtn" class="btn btn-secondary">
          <i class="fas fa-plus"></i> Add More Item
      </button>
  </div>
  <!-- Form Actions -->
  <div class="form-actions">
      <a href="{{ route('admin.consumption.index') }}"><button type="button"
              class="btn btn-secondary">Cancel</button></a>
      <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Store product units information
        const productUnits = {};
        
        // Load product units data
        @foreach($stocks as $stock)
            productUnits[{{ $stock->id }}] = "{{ $stock->measurement }}"; // Make sure this field exists
        @endforeach
        
        // Function to enforce unit matching
        function enforceUnitMatching(productSelect) {
            const itemId = $(productSelect).attr('id').split('-')[1];
            const selectedProduct = $(productSelect).val();
            const unitSelect = $(`#unit-${itemId}`);
            
            if (selectedProduct && productUnits[selectedProduct]) {
                const requiredUnit = productUnits[selectedProduct];
                
                // Disable ALL options first
                unitSelect.find('option').prop('disabled', true);
                
                // Enable ONLY the matching option
                unitSelect.find(`option[value="${requiredUnit}"]`).prop('disabled', false);
                
                // Set the value to the required unit
                unitSelect.val(requiredUnit);
                
                // Style the select to show it's restricted
                unitSelect.css({
                    'background-color': '#f0f0f0',
                    'cursor': 'pointer'
                });
            } else {
                // Re-enable all options if no product selected
                unitSelect.find('option').prop('disabled', false);
                unitSelect.css({
                    'background-color': '',
                    'cursor': ''
                });
            }
        }
        
        // Form submission
        $('#consumptionForm').on('submit', function (e) {
            e.preventDefault();
            
            // Enable disabled selects temporarily to include in form data
            $('select[disabled]').prop('disabled', false);

            const formData = {
                date: $('#consumption-date').val(),
                time: $('#consumption-time').val(),
                items: [],
                total: $('#total-consumption').val()
            };

            $('.product-item').each(function () {
                const itemId = $(this).data('item');
                const product = $(`#product-${itemId}`).val();
                const quantity = $(`#quantity-${itemId}`).val();
                const unit = $(`#unit-${itemId}`).val();

                if (product && quantity && unit) {
                    formData.items.push({ product, quantity, unit });
                }
            });

            $.ajax({
                url: "{{ route('admin.consumption.store') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    date: formData.date,
                    time: formData.time,
                    total: formData.total,
                    items: formData.items.map(item => ({
                        stock_id: item.product,
                        quantity: item.quantity,
                        unit: item.unit
                    }))
                },
                success: function (response) {
                    if (response.status) {
                        // Show success notification if available
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message);
                        } else {
                            alert(response.message);
                        }
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.consumption.index') }}";
                        }, 2000);
                    } else {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(response.message);
                        } else {
                            alert(response.message);
                        }
                        
                        // Re-disable unit selects
                        reapplyUnitRestrictions();
                    }
                },
                error: function (xhr) {
                    let errorMessage = 'Something went wrong. Please try again.';
                    
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
                    
                    // Re-disable unit selects
                    reapplyUnitRestrictions();
                }
            });
        });
        
        // Function to reapply unit restrictions after form submission
        function reapplyUnitRestrictions() {
            $('.product-item').each(function() {
                const itemId = $(this).data('item');
                const productSelect = $(`#product-${itemId}`);
                
                if (productSelect.val()) {
                    enforceUnitMatching(productSelect);
                }
            });
        }

        // Consumption Form Functionality
        const productContainer = $('#productItemsContainer');
        const addMoreBtn = $('#addMoreBtn');
        let itemCount = 1;

        // Set default date and time
        const now = new Date();
        $('#consumption-date').val(now.toISOString().split('T')[0]);
        $('#consumption-time').val(now.toTimeString().substring(0, 5));

        // Apply unit enforcing to the initial product select
        $(document).on('change', 'select[id^="product-"]', function() {
            enforceUnitMatching(this);
        });

        // Add more product items
        addMoreBtn.on('click', function () {
            itemCount++;
            const newItem = $(`
                <div class="product-item" data-item="${itemCount}">
                    <button type="button" class="remove-item">×</button>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="product-${itemCount}">Product</label>
                                <select id="product-${itemCount}" name="products[]" required>
                                    <option value="">Select Product</option>
                                    @foreach($stocks as $stock)
                                        <option value="{{ $stock->id }}">
                                            {{ $stock->product_name }} 
                                            @if(isset($stock->measurement))
                                                ({{ strtoupper($stock->measurement) }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="quantity-${itemCount}">Quantity</label>
                                <div class="quantity-unit-group">
                                    <input type="number" id="quantity-${itemCount}" name="quantities[]" min="0.01" step="0.01" required>
                                    <select id="unit-${itemCount}" name="units[]" class="form-select">
                                        <option value="">Select Unit</option>
                                        @foreach (['kg', 'g', 'l', 'pcs'] as $unit)
                                            <option value="{{ $unit }}">{{ strtoupper($unit) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            productContainer.append(newItem);

            $(`#quantity-${itemCount}`).on('input', updateTotalConsumption);
            $(`#unit-${itemCount}`).on('change', updateTotalConsumption);

            $('.product-item').each((index, item) => {
                const removeBtn = $(item).find('.remove-item');
                removeBtn.css('display', index === 0 ? 'none' : 'flex');
            });
        });

        productContainer.on('click', function (e) {
            if ($(e.target).hasClass('remove-item')) {
                const itemToRemove = $(e.target).closest('.product-item');
                if ($('.product-item').length > 1) {
                    itemToRemove.remove();
                    if ($('.product-item').length === 1) {
                        $('.product-item .remove-item').css('display', 'none');
                    }

                    updateTotalConsumption();
                }
            }
        });

        function updateTotalConsumption() {
            let total = 0;
            $('.product-item').each(function () {
                const itemId = $(this).data('item');
                const quantity = parseFloat($(`#quantity-${itemId}`).val()) || 0;
                total += quantity;
            });
            $('#total-consumption').val(total.toFixed(2));
        }

        // Add event listeners to initial quantity fields
        $('#quantity-1').on('input', updateTotalConsumption);
        $('#unit-1').on('change', updateTotalConsumption);
    });
</script>
@endsection