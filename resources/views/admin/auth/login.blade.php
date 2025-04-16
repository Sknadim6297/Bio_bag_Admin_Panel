<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Page</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />


  <style>
    body {
      font-family: 'Roboto Serif', serif;
      background: url('images/360_F_428871097_74QTIx9LRjJSBTnknjFlX2pSTRIL1edy.jpg') no-repeat center center fixed;
      background-size: cover;
      background-repeat: no-repeat;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background-color: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 400px;
      position: relative;
    }

    .logo-heading {
      text-align: center;
      margin-bottom: 25px;
      font-size: 1.8rem;
      font-weight: bold;
    }

    .mb-2 {
      margin-bottom: -4.5rem !important;
      margin-top: -77px;
    }

    .eye-icon {
      width: 24px;
      height: 24px;
    }

    .btn-deep-green {
      background-color: #006400;
      border-color: #006400;
      color: aliceblue;
    }

    .btn-deep-green:hover {
      background-color: #004d00;
      border-color: #004d00;
      color: bisque;
    }
  </style>
</head>

<body>

  <div class="login-box">
    <div class="logo-heading">
      <img src="images/logo-biobag.png" alt="Logo" class="mb-2" style="width: 75%;">
    </div>
    <form id="loginForm" method="POST">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">email</label>
        <input type="email" class="form-control" id="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <input type="password" class="form-control" id="password" required>
          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
            <img src="images/eye-icon-4.png" id="eyeIcon" alt="Show Password" class="eye-icon">
          </button>
        </div>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="rememberMe">
        <label class="form-check-label" for="rememberMe">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-deep-green w-100" id="loginBtn">Login</button>
    </form>
    
  </div>
</body>

</html>

<script>
  $('#loginForm').on('submit', function (e) {
    e.preventDefault();

    let formData = {
      email: $('#email').val(),
      password: $('#password').val(),
      _token: '{{ csrf_token() }}'
    };

    $.ajax({
      url: "{{ route('admin.login.submit') }}",
      method: "POST",
      data: formData,
      success: function (response) {
        if (response.status === true) {
          toastr.success(response.message);
          setTimeout(() => {
            window.location.href = "{{ route('admin.admin.dashboard') }}";
          }, 1000);
        } else {
          toastr.error(response.message);
        }
      },
      error: function (xhr) {
        toastr.error("Invalid credentials or server error.");
        $('#errorMessage').text("Invalid credentials or server error.").show();
      }
    });
  });
</script>
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
  