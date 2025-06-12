@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-vendor.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Add Category</h1>
</div>

<div class="vendor-form-container">
    <form class="vendor-form" id="categoryForm">
        @csrf
        <div class="form-section">
            <h2 class="section-title">Category Details</h2>
            
            <!-- Grid for input fields -->
            <div class="form-grid">
                <div class="form-group">
                    <label for="categoryName">Category Name</label>
                    <input type="text" id="categoryName" name="category_name" class="form-control" placeholder="Category Name" value="{{ old('category_name') }}" required />
                    @error('category_name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea id="description" name="description" class="form-control" rows="2" placeholder="Description" required>{{ old('description') }}</textarea>
                    @error('description')
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
            </div> <!-- end of form-grid -->
    
            <!-- Actions outside the grid for proper alignment -->
            <div class="form-actions" style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <a href="{{ route('admin.vendors.index') }}" style="text-decoration: none;" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-save">Save Vendor</button>
            </div>
        </div>
    </form>
    

</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#categoryForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('admin.category.store') }}", 
            method: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message); 
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.category.index') }}";
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

</script>
@endsection
