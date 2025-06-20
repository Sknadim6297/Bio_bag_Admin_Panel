<div class="top-bar">
  <!-- Left Section with Toggle Button -->
  <div class="left">
    <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle Sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>

  <!-- Right Section -->
  <div class="right">
    <span class="welcome">
      <i class="bi bi-person-circle"></i> Welcome, {{ Auth::user()->name }}
    </span>
    <a href="javascript:void(0);" id="logoutBtn" class="icon-link" title="Logout">
      <i class="bi bi-box-arrow-right"></i>
    </a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function () {
  $('#logoutBtn').on('click', function (e) {
    e.preventDefault(); 

    $.ajax({
      url: '{{ route("admin.admin.logout") }}', 
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}' 
      },
      success: function (response) {
        if (response.status === true) {
          toastr.success(response.message); 
          setTimeout(() => {
            window.location.href = '{{ route("admin.login") }}'; 
          }, 1000);
        }
      },
      error: function () {
        toastr.error('Logout failed.'); 
      }
    });
  });
});

  </script>