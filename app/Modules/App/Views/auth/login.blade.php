<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — {{ $identitas['aplikasi_nm'] }}</title>

  <!-- Bootstrap 5.3 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome 6 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- jQuery Validation -->
  <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
  <!-- Toastr -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <style>
    :root {
      --primary: #2196F3;
      --primary-dark: #1565C0;
      --primary-light: #BBDEFB;
      --primary-xlight: #E3F2FD;
      --accent: #00BCD4;
      --text: #1a2744;
      --muted: #64748B;
      --border: #DBEAFE;
      --font: 'Plus Jakarta Sans', sans-serif;
    }

    body,
    html {
      height: 100%;
      margin: 0;
      font-family: var(--font);
      background-color: #fff;
      overflow: hidden;
    }

    .login-container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    /* ── LEFT SIDE: VISUAL ── */
    .login-visual {
      flex: 1.2;
      position: relative;
      background: url('{{ asset("storage/dist/images/login_bg.png") }}') no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 60px;
      color: #fff;
    }

    .login-visual::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(13, 71, 161, 0.9) 0%, rgba(13, 71, 161, 0.4) 50%, rgba(13, 71, 161, 0.2) 100%);
    }

    .visual-content {
      position: relative;
      z-index: 2;
      max-width: 520px;
      animation: fadeInUp 0.8s ease-out;
    }

    .visual-content h1 {
      font-weight: 800;
      font-size: 3.2rem;
      line-height: 1.1;
      margin-bottom: 24px;
      letter-spacing: -1.5px;
    }

    .visual-content p {
      font-size: 1.15rem;
      opacity: 0.9;
      line-height: 1.6;
      font-weight: 400;
    }

    /* ── RIGHT SIDE: FORM ── */
    .login-form-side {
      flex: 0.8;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      background: #fff;
      position: relative;
    }

    .form-wrapper {
      width: 100%;
      max-width: 400px;
      animation: fadeInUp 0.6s ease-out;
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 40px;
    }

    .logo-icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, #42A5F5, #00BCD4);
      display: grid;
      place-items: center;
      font-size: 22px;
      color: #fff;
      box-shadow: 0 8px 16px rgba(33, 150, 243, 0.2);
    }

    .brand-name {
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--primary-dark);
      letter-spacing: -0.5px;
      line-height: 1;
    }

    .brand-sub {
      font-size: 0.75rem;
      color: var(--muted);
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    h2 {
      font-weight: 800;
      font-size: 1.9rem;
      color: var(--text);
      margin-bottom: 8px;
      letter-spacing: -0.5px;
    }

    .welcome-text {
      color: var(--muted);
      font-size: 0.95rem;
      margin-bottom: 35px;
    }

    /* ── FORM ELEMENTS ── */
    .form-label {
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 6px;
    }

    .input-group-custom {
      position: relative;
      margin-bottom: 16px;
    }

    .input-group-custom i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #cbd5e1;
      font-size: 14px;
      transition: color 0.3s ease;
      z-index: 4;
    }

    .form-control {
      border: 1.5px solid #cbd5e1;
      border-radius: 9px;
      padding: 4px 40px 4px 38px !important;
      font-size: 14px;
      background: #ffffff;
      color: var(--text);
      height: 36px !important;
      box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
      transition: all 0.2s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary);
      background: #fff;
      box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.15);
    }

    .form-control:focus+i {
      color: var(--primary);
    }

    .invalid-feedback {
      display: block;
      color: #dc3545;
      font-size: 12px;
      margin-top: 4px;
    }

    .form-control.is-invalid {
      border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
      border-color: #dc3545;
      box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.15);
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #cbd5e1;
      font-size: 14px;
      transition: color 0.3s ease;
      z-index: 4;
    }

    .password-toggle:hover {
      color: var(--primary);
    }

    .auth-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
      font-size: 13.5px;
    }

    .form-check-input {
      border: 1.5px solid #cbd5e1;
      cursor: pointer;
    }

    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
      box-shadow: 0 2px 4px rgba(33, 150, 243, .2);
    }

    .form-check-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(33, 150, 243, .15);
    }

    .form-check-label {
      font-size: 13.5px;
      color: var(--text);
      cursor: pointer;
      user-select: none;
      padding-top: 2px;
    }

    .forgot-link {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }

    .btn-login {
      width: 100%;
      height: 36px !important;
      background: linear-gradient(135deg, #2196F3, #1565C0);
      border: none;
      border-radius: 8px;
      color: #fff;
      font-weight: 600;
      font-size: 14px;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0 16px !important;
      gap: 8px;
      line-height: normal !important;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(33, 150, 243, 0.3);
      background: linear-gradient(135deg, #42A5F5, #1976D2);
    }

    /* ── FOOTER ── */
    .auth-footer {
      text-align: center;
      margin-top: 40px;
      font-size: 13px;
      color: var(--muted);
    }

    .auth-footer a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 700;
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(24px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 991px) {
      .login-visual {
        display: none;
      }

      .login-form-side {
        flex: 1;
      }
    }
  </style>
</head>

<body>

  <div class="login-container">
    <!-- LEFT SIDE -->
    <section class="login-visual">
      <div class="visual-content">
        <h1>Keuangan Akurat, Bisnis Melesat.</h1>
        <p>Solusi akuntansi terpadu untuk efisiensi pelaporan, manajemen inventaris, dan analisa keuangan yang cerdas. Kendalikan masa depan bisnis Anda hari ini.</p>
      </div>
    </section>

    <!-- RIGHT SIDE -->
    <main class="login-form-side">
      <div class="form-wrapper">
        <!-- BRAND -->
        <div class="brand-logo">
          <div class="logo-icon">
            <i class="fa-solid fa-chart-line"></i>
          </div>
          <div>
            <div class="brand-name">{{ $identitas['aplikasi_merk'] }}</div>
            <div class="brand-sub">{{ $identitas['aplikasi_nm'] }}</div>
          </div>
        </div>

        <!-- HEADING -->
        <h2>Selamat Datang</h2>
        <p class="welcome-text">Masukkan kredensial Anda untuk masuk ke sistem.</p>

        <!-- FORM -->
        <form id="loginForm" action="{{ url('app/auth/login_action') }}" method="POST" autocomplete="on">
          @csrf

          <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group-custom">
              <input type="text" class="form-control" name="u" id="u" placeholder="Masukkan username Anda" required autocomplete="username">
              <i class="fa-solid fa-user"></i>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Kata Sandi</label>
            <div class="input-group-custom">
              <input type="password" class="form-control" name="p" id="p" placeholder="••••••••" required>
              <i class="fa-solid fa-lock"></i>
            </div>
          </div>

          <div class="auth-options">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe">
              <label class="form-check-label" for="rememberMe" style="cursor:pointer">Ingat saya</label>
            </div>
            <a href="#" class="forgot-link">Lupa sandi?</a>
          </div>

          <button type="submit" class="btn-login">
            Masuk ke Dashboard <i class="fa-solid fa-arrow-right"></i>
          </button>
        </form>

        <div class="auth-footer">
          Belum punya akses? <a href="#">Hubungi Admin IT</a>
          <div class="mt-4" style="font-size: 11px; opacity: 0.6;">
            &copy; {{ date('Y') }} {{ $identitas['fasyankes_nm'] }} · {{ $identitas['aplikasi_nm']}} · {{ $identitas['aplikasi_versi']}}
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    $(document).ready(function() {
      // Toastr Configuration
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "4000",
      };

      // jQuery Validation
      $("#loginForm").validate({
        rules: {
          u: {
            required: true
          },
          p: {
            required: true,
            minlength: 6
          }
        },
        messages: {
          u: {
            required: "Username wajib diisi"
          },
          p: {
            required: "Kata sandi wajib diisi",
            minlength: "Kata sandi minimal 6 karakter"
          }
        },
        errorElement: "div",
        errorPlacement: function(error, element) {
          error.addClass("invalid-feedback");
          error.insertAfter(element.parent());
        },
        highlight: function(element) {
          $(element).addClass("is-invalid");
        },
        unhighlight: function(element) {
          $(element).removeClass("is-invalid");
        },
        submitHandler: function(form) {
          const btn = $(form).find('.btn-login');
          btn.prop('disabled', true);
          btn.html('<i class="fa-solid fa-circle-notch fa-spin"></i> Menghubungkan...');
          form.submit();
        }
      });

      // Flash Message Check
      const flashError = "{{ session('flash_error') }}";
      const flashSuccess = "{{ session('flash_success') }}";

      if (flashError) {
        toastr.error(flashError, "Kesalahan!");
      }

      if (flashSuccess) {
        toastr.success(flashSuccess, "Berhasil!");
      }
    })
  </script>

</body>

</html>