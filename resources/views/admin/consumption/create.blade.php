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
                          <option value="{{ $stock->id }}">{{ $stock->product_name }}</option>
                      @endforeach
                  </select>
              </div>
              
              </div>
              <div class="form-col">
                  <div class="form-group">
                      <label for="quantity-1">Quantity</label>
                      <div class="quantity-unit-group">
                          <input type="number" id="quantity-1" min="1" required>
                          <select id="unit-1">
                              <option value="kg">kg</option>
                              <option value="g">g</option>
                              <option value="lbs">lbs</option>
                              <option value="units">units</option>
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
        // Form submission
        $('#consumptionForm').on('submit', function (e) {
            e.preventDefault();

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
            toastr.success(response.message);
            setTimeout(() => {
                window.location.href = "{{ route('admin.consumption.index') }}";
            }, 2000);
        } else {
            toastr.error(response.message);
        }
    },

    error: function (xhr) {
    if (xhr.status === 422) {
        let errors = xhr.responseJSON.errors;
        for (let field in errors) {
            toastr.error(errors[field][0]);
        }
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
      
        toastr.error(xhr.responseJSON.message);
    } else {
        toastr.error('Something went wrong. Please try again.');
    }
}

    });
        });

    // Consumption Form Functionality
    const productContainer = $('#productItemsContainer');
    const addMoreBtn = $('#addMoreBtn');
    let itemCount = 1;

    // Set default date and time
    const now = new Date();
    $('#consumption-date').val(now.toISOString().split('T')[0]);
    $('#consumption-time').val(now.toTimeString().substring(0, 5));

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
                            <select id="product-${itemCount}" required>
                                <option value="">Select Product</option>
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->id }}">{{ $stock->product_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="quantity-${itemCount}">Quantity</label>
                            <div class="quantity-unit-group">
                                <input type="number" id="quantity-${itemCount}" min="0.01" step="0.01" required>
                                <select id="unit-${itemCount}">
                                    <option value="kg">kg</option>
                                    <option value="g">g</option>
                                    <option value="lbs">lbs</option>
                                    <option value="units">units</option>
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
