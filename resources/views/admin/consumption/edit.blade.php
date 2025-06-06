@extends('layouts.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/consumtion.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Edit Consumption</h1>
</div>

<form class="consumption-form" id="consumptionForm">
    <!-- Date and Time -->
    <div class="form-row">
        <div class="form-col">
            <div class="form-group">
                <label for="consumption-date">Date</label>
                <input type="date" id="consumption-date" name="date"
                    value="{{ old('date', \Carbon\Carbon::parse($consumption->date)->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="form-col">
            <div class="form-group">
                <label for="consumption-time">Time</label>
                <input type="time" id="consumption-time" name="time"
                    value="{{ old('time', \Carbon\Carbon::parse($consumption->time)->format('H:i')) }}" required>
            </div>
        </div>
    </div>

    <!-- Product Items Container -->
    <div id="productItemsContainer">
        @foreach ($items as $index => $item)
        <div class="product-item" data-item="{{ $index + 1 }}">
            <button type="button" class="remove-item" style="{{ $index == 0 ? 'display: none;' : '' }}">×</button>
            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label for="product-{{ $index + 1 }}">Product</label>
                        <select id="product-{{ $index + 1 }}" name="products[]" required>
                            <option value="">Select Product</option>
                            @foreach($skus as $sku)
                            <option value="{{ $sku->id }}" {{ $item->sku_id == $sku->id ? 'selected' : '' }}>
                                {{ $sku->product_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label for="quantity-{{ $index + 1 }}">Quantity</label>
                        <div class="quantity-unit-group">
                            <input type="number" id="quantity-{{ $index + 1 }}" name="quantities[]"
                                value="{{ old('quantities.' . $index, $item->quantity) }}" min="1" required>
                            <select id="unit-{{ $index + 1 }}" name="units[]" required>
                                <option value="kg" {{ $item->unit == 'kg' ? 'selected' : '' }}>kg</option>
                                <option value="g" {{ $item->unit == 'g' ? 'selected' : '' }}>g</option>
                                <option value="lbs" {{ $item->unit == 'lbs' ? 'selected' : '' }}>lbs</option>
                                <option value="units" {{ $item->unit == 'units' ? 'selected' : '' }}>units</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Total Consumption -->
    <div class="form-row">
        <div class="form-col">
            <div class="form-group">
                <label for="total-consumption">Total Consumption</label>
                <input type="text" id="total-consumption" name="total_consumption"
                    value="{{ old('total_consumption', $consumption->total ?? 0) }}" readonly>
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
        <a href="{{ route('admin.consumption.index') }}">
            <button type="button" class="btn btn-secondary">Cancel</button>
        </a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>


@endsection

@section('scripts')
<script>
    let itemCount = {{ count($items ?? []) }}; // Dynamically count items passed from the controller

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
                url: "{{ route('admin.consumption.update', $consumption->id) }}",
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    date: formData.date,
                    time: formData.time,
                    total: formData.total,
                    items: formData.items.map(item => ({
                        sku_id: item.product,
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
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });

        // Consumption Form Functionality
        const productContainer = $('#productItemsContainer');
        const addMoreBtn = $('#addMoreBtn');

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
                                    @foreach($skus as $sku)
                                        <option value="{{ $sku->id }}">{{ $sku->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-col">
                            <div class="form-group">
                                <label for="quantity-${itemCount}">Quantity</label>
                                <div class="quantity-unit-group">
                                    <input type="number" id="quantity-${itemCount}" name="quantities[]" min="1" required>
                                    <select id="unit-${itemCount}" name="units[]" required>
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

            // Attach event listeners for new item fields
            $(`#quantity-${itemCount}`).on('input', updateTotalConsumption);
            $(`#unit-${itemCount}`).on('change', updateTotalConsumption);
        });

        // Remove product item
        productContainer.on('click', function (e) {
            if ($(e.target).hasClass('remove-item')) {
                const itemToRemove = $(e.target).closest('.product-item');
                itemToRemove.remove();
                updateTotalConsumption();
            }
        });

        // Update total consumption based on quantities
        function updateTotalConsumption() {
            let total = 0;
            $('.product-item').each(function () {
                const itemId = $(this).data('item');
                const quantity = parseFloat($(`#quantity-${itemId}`).val()) || 0;
                total += quantity;
            });
            $('#total-consumption').val(total.toFixed(2));
        }

        // Initial calculations for the first product item
        $('.product-item').each(function (index, item) {
            const removeBtn = $(item).find('.remove-item');
            removeBtn.css('display', index === 0 ? 'none' : 'flex');
        });
    });
</script>
@endsection