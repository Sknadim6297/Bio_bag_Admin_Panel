@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Add Vendor</h1>
</div>

<div class="vendor-form-container">
  <form class="vendor-form" id="vendorForm">
    @csrf
    <div class="form-section">
        <h2 class="section-title">Vendor Details</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="vendorName">Vendor Name</label>
                <input type="text" id="vendorName" name="vendor_name" class="form-control" placeholder="Vendor Name" value="{{ old('vendor_name') }}" required />
                @error('vendor_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="mobileNumber">Mobile Number</label>
                <input type="tel" id="mobileNumber" name="mobile_number" class="form-control" placeholder="Mobile Number" pattern="[0-9]{10}" value="{{ old('mobile_number') }}" required />
                <small class="form-text">10 digit mobile number</small>
                @error('mobile_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" class="form-control" rows="2" placeholder="Address" required>{{ old('address') }}</textarea>
                @error('address')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="paymentTerms">Payment Terms</label>
                <input type="text" id="paymentTerms" name="payment_terms" class="form-control" placeholder="Payment Terms" value="{{ old('payment_terms') }}" required />
                @error('payment_terms')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Category of Supply</label>
                <div style="display: flex; gap: 10px;">
                    <select id="category" name="category_of_supply" class="form-control" style="flex: 1;" required>
                        <option value="">Select Category</option>
                        <option value="raw_materials" {{ old('category_of_supply') == 'raw_materials' ? 'selected' : '' }}>Raw Materials</option>
                        <option value="packaging" {{ old('category_of_supply') == 'packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="services" {{ old('category_of_supply') == 'services' ? 'selected' : '' }}>Services</option>
                    </select>
                    <button type="button" class="btn btn-save" onclick="showCategoryInput()">+ Add Category</button>
                </div>
                <div id="newCategoryContainer" style="margin-top: 10px; display: none;">
                    <input type="text" id="newCategoryInput" class="form-control" placeholder="Enter new category" />
                    <button type="button" class="btn btn-save" style="margin-top: 5px;" onclick="addNewCategory()">Add</button>
                </div>
                @error('category_of_supply')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" id="gstin" name="gstin" class="form-control" placeholder="GSTIN" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" value="{{ old('gstin') }}" required />
                <small class="form-text">Format: 22AAAAA0000A1Z5</small>
                @error('gstin')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="panNumber">PAN Number</label>
                <input type="text" id="panNumber" name="pan_number" class="form-control" placeholder="PAN Number" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" value="{{ old('pan_number') }}" required />
                <small class="form-text">Format: AAAAA9999A</small>
                @error('pan_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h2 class="section-title">Bank Details</h2>
        <div class="form-grid">
            <div class="form-group">
                <label for="bankName">Bank Name</label>
                <input type="text" id="bankName" name="bank_name" class="form-control" placeholder="Bank Name" value="{{ old('bank_name') }}" required />
                @error('bank_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="branchName">Branch Name</label>
                <input type="text" id="branchName" name="branch_name" class="form-control" placeholder="Branch Name" value="{{ old('branch_name') }}" required />
                @error('branch_name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="accountNumber">Account Number</label>
                <input type="text" id="accountNumber" name="account_number" class="form-control" placeholder="Account Number" pattern="[0-9]{9,18}" value="{{ old('account_number') }}" required />
                <small class="form-text">9-18 digit account number</small>
                @error('account_number')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="ifscCode">IFSC Code</label>
                <input type="text" id="ifscCode" name="ifsc_code" class="form-control" placeholder="IFSC Code" pattern="^[A-Z]{4}0[A-Z0-9]{6}$" value="{{ old('ifsc_code') }}" required />
                <small class="form-text">Format: ABCD0123456</small>
                @error('ifsc_code')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.vendors.index') }}" style="text-decoration: none;" class="btn btn-cancel">Cancel</a>
        <button type="submit" class="btn btn-save">Save Vendor</button>
    </div>
</form>

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function showCategoryInput() {
  document.getElementById('newCategoryContainer').style.display = 'block';
}

function addNewCategory() {
  const newCategory = document.getElementById('newCategoryInput').value.trim();
  if (newCategory !== '') {
    const select = document.getElementById('category');
    const option = document.createElement('option');
    option.value = newCategory.toLowerCase().replace(/\s+/g, '_');
    option.textContent = newCategory;
    select.appendChild(option);
    select.value = option.value;
    document.getElementById('newCategoryInput').value = '';
    document.getElementById('newCategoryContainer').style.display = 'none';
  } else {
    alert('Please enter a category name.');
  }
}

$(document).ready(function () {
    $("#vendorForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.vendors.store') }}", 
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message); 
                    setTimeout(() => {
            window.location.href = "{{ route('admin.vendors.index') }}";
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

</script>
@endsection
