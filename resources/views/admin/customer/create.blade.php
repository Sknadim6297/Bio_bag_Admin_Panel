@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-customer.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Add Customer</h1>
</div>

<div class="customer-form-container">
  <form class="customer-form" id="customerForm" action="{{ route('admin.customer.store') }}" method="POST">
    @csrf

    <div class="form-section">
      <h2 class="section-title">Customer Details</h2>
      <div class="form-grid">
        <div class="form-group">
          <label for="customerName">Customer Name</label>
          <input type="text" id="customerName" name="customer_name" class="form-control" placeholder="Customer Name" required />
        </div>
        <div class="form-group">
          <label for="mobileNumber">Mobile Number</label>
          <input type="tel" id="mobileNumber" name="mobile_number" class="form-control" placeholder="Mobile Number" pattern="[0-9]{10}" required />
          <small class="form-text">10 digit mobile number</small>
        </div>
        <div class="form-group">
          <label for="address">Address</label>
          <textarea id="address" name="address" class="form-control" rows="2" placeholder="Address" required></textarea>
        </div>
        <div class="form-group">
          <label for="paymentTerms">Payment Terms</label>
          <input type="text" id="paymentTerms" name="payment_terms" class="form-control" placeholder="Payment Terms" required />
        </div>
        <div class="form-group">
          <label for="gstin">GSTIN</label>
          <input type="text" id="gstin" name="gstin" class="form-control" placeholder="GSTIN" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" required />
          <small class="form-text">Format: 22AAAAA0000A1Z5</small>
        </div>
        <div class="form-group">
          <label for="panNumber">PAN Number</label>
          <input type="text" id="panNumber" name="pan_number" class="form-control" placeholder="PAN Number" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" required />
          <small class="form-text">Format: AAAAA9999A</small>
        </div>
      </div>
    </div>

    <div class="form-section">
      <h2 class="section-title">Bank Details</h2>
      <div class="form-grid">
        <div class="form-group">
          <label for="bankName">Bank Name</label>
          <input type="text" id="bankName" name="bank_name" class="form-control" placeholder="Bank Name" required />
        </div>
        <div class="form-group">
          <label for="branchName">Branch Name</label>
          <input type="text" id="branchName" name="branch_name" class="form-control" placeholder="Branch Name" required />
        </div>
        <div class="form-group">
          <label for="accountNumber">Account Number</label>
          <input type="text" id="accountNumber" name="account_number" class="form-control" placeholder="Account Number" pattern="[0-9]{9,18}" required />
          <small class="form-text">9-18 digit account number</small>
        </div>
        <div class="form-group">
          <label for="ifscCode">IFSC Code</label>
          <input type="text" id="ifscCode" name="ifsc_code" class="form-control" placeholder="IFSC Code" pattern="^[A-Z]{4}0[A-Z0-9]{6}$" required />
          <small class="form-text">Format: ABCD0123456</small>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" class="form-control" required>
            <option value="">Select Status</option>
            <option value="active" {{ old('status', request('status')) == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', request('status')) == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('admin.customer.index') }}" class="btn btn-cancel">Cancel</a>
      <button type="submit" class="btn btn-save">Save Customer</button>
    </div>
  </form>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {
    $("#customerForm").on("submit", function (event) {
      event.preventDefault();
      var formData = $(this).serialize();

      $.ajax({
        url: "{{ route('admin.customer.store') }}",
        method: "POST",
        data: formData,
        success: function (response) {
          if (response.status) {
            toastr.success(response.message);
            setTimeout(() => {
              window.location.href = "{{ route('admin.customer.index') }}";
            }, 1000);
          } else {
            toastr.error(response.message);
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
