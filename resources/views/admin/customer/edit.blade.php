@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/edit-customer.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Edit Customer</h1>
</div>

<div class="customer-form-container">
  <!-- Customer Form -->
  <form id="customerForm" method="POST" action="{{ route('admin.customer.update', $customer->id) }}" class="customer-form">
    @csrf
    @method('PUT') 
    
    <div class="form-section">
      <h2 class="section-title">Customer Details</h2>
      <div class="form-stacked">
        <!-- Customer Name -->
        <div class="form-group">
          <label for="customerName">Customer Name</label>
          <input type="text" id="customerName" name="customer_name" class="form-control" value="{{ old('customer_name', $customer->customer_name) }}" />
        </div>

        <!-- Customer Code -->
        <div class="form-group">
          <label for="customerCode">Customer Code</label>
          <input type="text" id="customerCode" name="customer_code" class="form-control" value="{{ old('customer_code', $customer->customer_code) }}" readonly />
        </div>

        <!-- Mobile Number -->
        <div class="form-group">
          <label for="mobileNumber">Mobile Number</label>
          <input type="tel" id="mobileNumber" name="mobile_number" class="form-control" value="{{ old('mobile_number', $customer->mobile_number) }}" />
        </div>

        <!-- Address -->
        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
        </div>

        <!-- Payment Terms -->
        <div class="form-group">
          <label for="paymentTerms">Payment Terms</label>
          <input type="text" id="paymentTerms" name="payment_terms" class="form-control" value="{{ old('payment_terms', $customer->payment_terms) }}" />
        </div>

        <!-- GSTIN -->
        <div class="form-group">
          <label for="gstin">GSTIN</label>
          <input type="text" id="gstin" name="gstin" class="form-control" value="{{ old('gstin', $customer->gstin) }}" />
        </div>

        <!-- PAN Number -->
        <div class="form-group">
          <label for="panNumber">PAN Number</label>
          <input type="text" id="panNumber" name="pan_number" class="form-control" value="{{ old('pan_number', $customer->pan_number) }}" />
        </div>
      </div>
      
    </div>
    <div class="form-group">
      <label for="status">Status</label>
      <select id="status" name="status" class="form-control" required>
        <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    <!-- Form Actions -->
    <div class="form-actions">
      <a href="{{ route('admin.customer.index') }}" style="text-decoration: none;" class="btn btn-cancel">Cancel</a>
      <button type="submit" class="btn btn-save">Update Customer</button>
    </div>
    
  </form>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 $('#customerForm').on('submit', function(e) {
      e.preventDefault();

      let form = $(this);
      let formData = form.serialize() + '&_method=PUT'; 

      $.ajax({
        url: form.attr('action'),
        method: "POST",
        data: formData,
        success: function(response) {
          if (response.status) {
            toastr.success(response.message);
            setTimeout(() => {
              window.location.href = "{{ route('admin.customer.index') }}";
            }, 1000);
          } else {
            toastr.error(response.message);
          }
        },
        error: function(xhr) {
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
</script>

@endsection
