@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-product.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Edit SKU/Product</h1>
</div>

<div class="vendor-form-container">
  <form id="skuForm" method="POST" action="{{ route('admin.sku.update', $sku->id) }}">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="category" class="form-label">Category Name</label>
            <select class="form-select" id="category" name="category_id">
                <option disabled>Choose One</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $sku->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="datetime" class="form-label">Date & Time</label>
            <input type="datetime-local" class="form-control" id="datetime" name="date_time" 
                value="{{ \Carbon\Carbon::parse($sku->updated_at)->format('Y-m-d\TH:i') }}" readonly>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="sku_code" class="form-label">SKU Code</label>
            <input type="text" class="form-control" id="sku_code" name="sku_code" value="{{ $sku->sku_code }}" readonly>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" value="{{ $sku->product_name }}">
        </div>
        <div class="col-md-6">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $sku->quantity }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="measurement" class="form-label">Measurement</label>
            <select class="form-select" id="measurement" name="measurement">
                <option disabled>Choose Measurement</option>
                <option value="kg" {{ $sku->measurement == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                <option value="g" {{ $sku->measurement == 'g' ? 'selected' : '' }}>Grams (g)</option>
                <option value="l" {{ $sku->measurement == 'l' ? 'selected' : '' }}>Liters (L)</option>
                <option value="pcs" {{ $sku->measurement == 'pcs' ? 'selected' : '' }}>Pieces</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="msq" class="form-label">MSQ (Minimum Stock Quantity)</label>
            <input type="number" class="form-control" id="msq" name="msq" value="{{ $sku->msq }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $sku->price }}">
        </div>
        <div class="col-md-4">
            <label for="gst" class="form-label">GST(%)</label>
            <input type="number" class="form-control" id="gst" name="gst" value="{{ $sku->gst }}">
        </div>
        <div class="col-md-4">
            <label for="freight" class="form-label">Freight</label>
            <input type="number" step="0.01" class="form-control" id="freight" name="freight" value="{{ $sku->freight }}">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label for="totalPrice" class="form-label">Total Price</label>
            <input type="text" class="form-control" id="totalPrice" name="total_price" value="{{ $sku->total_price }}" readonly>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.sku.index') }}" class="btn btn-cancel">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#skuForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize() + '&_method=PUT';

        $.ajax({
            url: "{{ route('admin.sku.update', $sku->id) }}",
            method: "POST",
            data: formData,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.sku.index') }}";
                    }, 1000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, error) {
                        toastr.error(error[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>
@endsection
