@extends('layouts.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/add-final-output.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection
@section('content')
<div class="dashboard-header">
  <h1>Edit Final Output</h1>
</div>
<form class="final-output-form" id="finalOutputForm">
  @csrf
  <!-- Date and Time -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="output-date">Date</label>
              <input type="date" id="output-date" name="output_date" value="{{ isset($finalOutput) ? date('Y-m-d', strtotime($finalOutput->final_output_datetime)) : '' }}" required>
          </div>
      </div>
      <div class="form-col">
          <div class="form-group">
              <label for="output-time">Time</label>
              <input type="time" id="output-time" name="output_time" value="{{ isset($finalOutput) ? date('H:i', strtotime($finalOutput->final_output_datetime)) : '' }}" required>
          </div>
      </div>
  </div>

  <!-- Customer Information -->
  <div class="form-group">
    <label for="customer_id">Customer Name</label>
    <select id="customer_id" name="customer_id" class="form-control" required>
        <option value="">Select Customer</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ (isset($finalOutput) && $finalOutput->customer_id == $customer->id) ? 'selected' : '' }}>{{ $customer->customer_name }}</option>
        @endforeach
    </select>
  </div>
  <!-- Product Specifications -->
  <div class="form-row">
      <div class="form-col">
          <div class="form-group">
              <label for="size">Size</label>
              <input type="text" id="size" name="size" value="{{ $finalOutput->size ?? '' }}" placeholder="e.g., 12x18" required>
          </div>
      </div>
      <div class="form-col">
          <div class="form-group">
              <label for="micron">Micron</label>
              <input type="text" id="micron" name="micron" value="{{ $finalOutput->micron ?? '' }}" placeholder="e.g., 30" required>
          </div>
      </div>
  </div>

  <!-- Final Output Quantity -->
  <div class="form-group">
    <label for="quantity">Final Output (kg)</label>
    <input type="number" id="quantity" name="quantity" min="1" value="{{ $finalOutput->quantity ?? '' }}" required>
  </div>

  <!-- Form Actions -->
  <div class="form-actions">
      <a href="{{ route('admin.final-output.index') }}"><button type="button" class="btn btn-secondary">Cancel</button></a>
      <button type="submit" class="btn btn-primary">Save Changes</button>
  </div>
</form>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script>
$(document).ready(function () {
    $("#finalOutputForm").submit(function (e) {
        e.preventDefault();
        const id = {{ $finalOutput->id }};
        const formData = $(this).serialize() + '&_method=PUT';
        $.ajax({
            url: `/admin/final-output/${id}`,
            method: "POST",
            data: formData,
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.final-output.index') }}";
                    }, 1200);
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
</script>
@endsection
