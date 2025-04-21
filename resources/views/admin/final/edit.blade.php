@extends('layouts.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/add-vendor.css') }}">
@endsection
@section('content')
      

  @endsection

  @section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 $(document).ready(function() {
    $('#vendorForm').on('submit', function(e) {
      e.preventDefault();

      let form = $(this);
      let formData = form.serialize() + '&_method=PUT'; 

      $.ajax({
        url: "{{ route('admin.vendors.update', $vendor->id) }}",
        method: "POST",
        data: formData,
        success: function(response) {
          if (response.success) {
            toastr.success(response.message);
            setTimeout(() => {
              window.location.href = "{{ route('admin.vendors.index') }}";
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
</body>
</html>