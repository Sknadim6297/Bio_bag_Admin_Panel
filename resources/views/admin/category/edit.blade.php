@extends('layouts.layout')

@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-vendor.css') }}">
@endsection

@section('content')
<div class="dashboard-header">
  <h1>Edit Category</h1>
</div>

<div class="vendor-form-container">
  <form class="vendor-form" id="categoryForm" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $category->id }}" />

    <div class="form-section">
      <h2 class="section-title">Category Details</h2>

      <div class="form-grid">
        <div class="form-group">
          <label for="categoryName">Category Name</label>
          <input type="text" id="categoryName" name="category_name" class="form-control" placeholder="Category Name" value="{{ $category->category_name }}" required />
        </div>

        <div class="form-group">
          <label for="categoryCode">Category Code</label>
          <input type="text" id="categoryCode" name="category_code" class="form-control" placeholder="CAT00005" value="{{ $category->category_code }}" readonly />
        </div>

        <div class="form-group">
          <label for="description">Category Description</label>
          <textarea id="description" name="description" class="form-control" rows="2" placeholder="Description" required>{{ $category->description }}</textarea>
        </div>
      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control" required>
          <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
      </div> <!-- end of form-grid -->
      <div class="form-actions" style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
        <a href="{{ route('admin.category.index') }}" class="btn btn-cancel">Cancel</a>
        <button type="submit" class="btn btn-save">Update Category</button>
      </div>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    $('#categoryForm').on('submit', function (e) {
      e.preventDefault();

      let form = $(this);
      let formData = form.serialize() + '&_method=PUT';

      $.ajax({
        url: "{{ route('admin.category.update', $category->id) }}",
        method: "POST",
        data: formData,
        success: function (response) {
          if (response.success) {
            toastr.success(response.message);
            setTimeout(() => {
              window.location.href = "{{ route('admin.category.index') }}";
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
