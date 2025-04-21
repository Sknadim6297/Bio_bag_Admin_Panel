@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-production.css') }}">
@endsection

@section('content')

<div class="dashboard-header">
    <h1>Add Production Record</h1>
  </div>

  <form id="productionForm" class="production-form">
    @csrf
    <div class="form-group">
      <label for="datetime">Date & Time</label>
      <input type="datetime-local" name="production_datetime" id="datetime" class="form-control" required>
  </div>
  
    <div class="form-group">
      <label for="customer_id">Customer Name</label>
        <select name="customer_id" id="customer_id" class="form-control" required>
            <option value="">Select Customer</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
            @endforeach
        </select>
    </div>
  
    <div class="form-row">
      <div class="form-col">
        <div class="form-group">
          <label for="rolls_done">Rolls Done</label>
          <input type="number" name="rolls_done" id="rolls_done" class="form-control" required>
        </div>
      </div>
      <div class="form-col">
        <div class="form-group">
          <label for="size">Size</label>
          <input type="text" name="size" id="size" class="form-control" required>
        </div>
      </div>
    </div>
  
    <div class="form-row">
      <div class="form-col">
        <div class="form-group">
          <label for="kilograms_produced">Kilogram Produced</label>
          <input type="number" name="kilograms_produced" id="kilograms_produced" class="form-control" step="0.01" required>
        </div>
      </div>
      <div class="form-col">
        <div class="form-group">
          <label for="machine_number">Machine Number</label>
          <input type="text" name="machine_number" id="machine_number" class="form-control" required>
        </div>
      </div>
    </div>
  
    <div class="form-group">
      <label for="micron">Micron</label>
      <input type="number" name="micron" id="micron" class="form-control" required>
    </div>
  
    <div class="form-group">
      <label for="notes">Additional Notes</label>
      <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i> Save Production Record
      </button>
  </form>
  
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#productionForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.production.store') }}", 
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message); 
                    setTimeout(() => {
            window.location.href = "{{ route('admin.production.index') }}";
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
document.addEventListener('DOMContentLoaded', function () {
    const datetimeField = document.getElementById('datetime');
    const now = new Date();

    // Extract local date and time parts
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0'); 
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

    const localDatetime = `${year}-${month}-${day}T${hours}:${minutes}`;
    datetimeField.value = localDatetime;
});


</script>
@endsection
