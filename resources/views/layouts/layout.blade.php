<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin-Dashboard</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />
    <!-- Bootstrap Icons -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Roboto Serif Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto+Serif:wght@400;500;700&display=swap"
      rel="stylesheet"
    />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
     <!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')
  </head>

  <body>
    @include('admin.sections.header')

    <div class="container">
      @include('admin.sections.sidebar')

      <div class="main-content">
        <div class="content-wrapper">
          @yield('content')
        </div>
        
        <div class="footer">
          <p>2025 &copy; Bio-Bag</p>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const menuItems = document.querySelectorAll(".menu-item");

        menuItems.forEach((item) => {
          const link = item.querySelector(".has-submenu");
          const submenu = item.querySelector(".submenu");

          link.addEventListener("click", function (e) {
            e.preventDefault();

            // Close all other open submenus
            menuItems.forEach((otherItem) => {
              if (otherItem !== item) {
                otherItem.querySelector(".submenu").classList.remove("active");
                otherItem.querySelector(".has-submenu").classList.remove("active");
              }
            });

            // Toggle current submenu
            submenu.classList.toggle("active");
            link.classList.toggle("active");
          });
        });
      });
    </script>

    <script>
      @if(session('success'))
        toastr.success("{{ session('success') }}");
      @endif
    
      @if(session('error'))
        toastr.error("{{ session('error') }}");
      @endif
    
      @if(session('info'))
        toastr.info("{{ session('info') }}");
      @endif
    
      @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
      @endif
    </script>

    <script>
     $(document).ready(function () {
    $('body').on('click', '.delete-item', function (event) {
        event.preventDefault();
        let deleteUrl = $(this).data('url');

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#dc3545",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'DELETE',
                    url: deleteUrl,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            Swal.fire('Deleted!', data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to delete.', 'error');
                        }
                    },
                    error: function (xhr) {
                        Swal.fire('Oops!', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });
});

    </script>
    @yield('scripts')
  </body>
</html>
