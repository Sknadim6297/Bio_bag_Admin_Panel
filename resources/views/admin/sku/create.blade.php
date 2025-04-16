@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-product.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
    <h1>Add New Product</h1>
</div>
<form id="skuForm" method="POST">
    @csrf

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="category" class="form-label">Category Name</label>
            <select class="form-select" id="category" name="category_id">
                <option selected disabled>Choose One</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="datetime" class="form-label">Date & Time</label>
            <input type="datetime-local" class="form-control" id="datetime" name="date_time" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="product_name" placeholder="Enter product name">
        </div>
        <div class="col-md-6">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="measurement" class="form-label">Measurement</label>
            <select class="form-select" id="measurement" name="measurement">
                <option selected disabled>Choose Measurement</option>
                <option value="kg">Kilograms (kg)</option>
                <option value="g">Grams (g)</option>
                <option value="l">Liters (L)</option>
                <option value="pcs">Pieces</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="msq" class="form-label">MSQ (Minimum Stock Quantity)</label>
            <input type="number" class="form-control" id="msq" name="msq" placeholder="Enter MSQ">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter price">
        </div>
        <div class="col-md-4">
            <label for="gst" class="form-label">GST(%)</label>
            <input type="number" class="form-control" id="gst" name="gst" placeholder="Enter GST percentage">
        </div>
        <div class="col-md-4">
            <label for="freight" class="form-label">Freight</label>
            <input type="number" step="0.01" class="form-control" id="freight" name="freight" placeholder="Enter freight cost">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <label for="totalPrice" class="form-label">Total Price</label>
            <input type="text" class="form-control" id="totalPrice" name="total_price" placeholder="Calculated total" readonly>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.sku.index') }}"><button type="button" class="btn btn-cancel">Cancel</button></a>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {

    $('#price, #gst, #freight').on('input', function () {
        let price = parseFloat($('#price').val()) || 0;
        let gst = parseFloat($('#gst').val()) || 0;
        let freight = parseFloat($('#freight').val()) || 0;

        let gstAmount = price * (gst / 100);
        let total = price + gstAmount + freight;
        $('#totalPrice').val(total.toFixed(2));
    });
    

    $("#skuForm").submit(function (e) { 
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.sku.store') }}", 
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message); 
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.sku.index') }}";
                    }, 1000);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        if (errors.hasOwnProperty(field)) {
                            toastr.error(errors[field][0]); 
                        }
                    }
                } else {
                    toastr.error('Something went wrong. Please try again.'); 
                }
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', function () {
    var now = new Date();

    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0'); 
    var day = String(now.getDate()).padStart(2, '0');
    var hours = String(now.getHours()).padStart(2, '0');
    var minutes = String(now.getMinutes()).padStart(2, '0');

    var localDatetime = `${year}-${month}-${day}T${hours}:${minutes}`;
    document.getElementById('datetime').value = localDatetime;
});



</script>
@endsection
