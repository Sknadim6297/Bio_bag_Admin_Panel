@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-final-output.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Add Final Output</h1>
</div>

<!-- Final Output Form -->
<form class="final-output-form" id="finalOutputForm">
  @csrf
  <!-- Date and Time -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="output-date">Date</label>
              <input type="date" id="output-date" required>
          </div>
      </div>
      <div class="form-col">
          <div class="form-group">
              <label for="output-time">Time</label>
              <input type="time" id="output-time" required>
          </div>
      </div>
  </div>

  <!-- Customer Information -->
  <div class="form-group">
    <label for="customer_id">Customer Name</label>
    <select id="customer_id" name="customer_id" class="form-control" required>
        <option value="">Select Customer</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
        @endforeach
    </select>
</div>
  <!-- Product Specifications -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="size">Size</label>
              <input type="text" id="size" name="size" placeholder="e.g., 12x18" required>
          </div>
      </div>
      <div class="form-col">
          <div class="form-group">
              <label for="micron">Micron</label>
              <input type="text" id="micron" name="micron" placeholder="e.g., 30" required>
          </div>
      </div>
  </div>

  <!-- Final Output Quantity -->
<div class="form-group">
  <label for="quantity">Final Output (kg)</label>
  <input type="number" id="quantity" name="quantity" min="1" required>
</div>


  <!-- Form Actions -->
  <div class="form-actions">
      <a href="final-output.html"><button type="button" class="btn btn-secondary">Cancel</button></a>
      <button type="submit" class="btn btn-primary">Save Final Output</button>
  </div>
</form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#finalOutputForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.final-output.store') }}", 
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message); 
                    setTimeout(() => {
            window.location.href = "{{ route('admin.final-output.index') }}";
          }, 2000);
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

    document.addEventListener("DOMContentLoaded", function () {
        const now = new Date();

        // Format Date - yyyy-mm-dd
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        document.getElementById("output-date").value = `${year}-${month}-${day}`;

        // Format Time - HH:MM (24hr format)
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById("output-time").value = `${hours}:${minutes}`;
    });
</script>
@endsection
