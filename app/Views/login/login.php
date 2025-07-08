<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CARGAS | Login</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('public/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('public/dist/css/adminlte.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/sweetalert2/sweetalert2.min.css') ?>">
  <link rel="icon" type="image/png" href="<?= base_url('public/dist/img/favicon.png') ?>" />
</head>


<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
        <div class="login-logo">
          <img src="<?= base_url('public/dist/img/logofoxnegro.png') ?>" width="200" alt="Logo GASIU">
        </div>

        <!-- Input usuario -->
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Usuario" id="txtusuario" name="txtusuario" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <!-- Input contraseña con ojito -->
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" id="txtpassword" name="txtpassword">
          <div class="input-group-append">
            <div class="input-group-text" style="cursor: pointer;" id="togglePassword">
              <i class="fas fa-lock" id="icon-lock"></i>
              <i class="fas fa-eye d-none" id="icon-eye"></i>
            </div>
          </div>
        </div>

        <!-- Botón de login -->
        <div class="social-auth-links text-center mb-4">
          <button class="btn btn-block btn-primary" onclick="loguear()">
            <i class="fa-solid fa-circle-check"></i> INGRESAR AL SISTEMA
          </button>
        </div>

        <!-- Recordar contraseña -->
        <div style="display: flex; align-items: center; justify-content: flex-start; margin-bottom: 0.5rem; margin-top: -1rem;">
          <input type="checkbox" id="recordarPass" name="recordarPass" style="margin-right: 8px; margin-left: 12px;">
          <label for="recordarPass" style="margin: 0; font-size: 1rem; color: #1976d2; cursor: pointer;">Recordar contraseña</label>
        </div>

        <p class="text-center">- VERSIÓN <?= getenv('VERSION') ?> -</p>
      </div>
    </div>
  </div>

  <!-- JS scripts -->
  <script src="<?= base_url('public/plugins/jquery/jquery.min.js'); ?>"></script>
  <script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <script src="<?= base_url('public/dist/js/adminlte.min.js'); ?>"></script>
  <script src="<?= base_url('public/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
  <script src="<?= base_url('public/dist/js/pages/login.js') ?>"></script>

  <script>
    var URLPY = '<?= base_url(); ?>';


    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('txtpassword');
      const toggle = document.getElementById('togglePassword');
      const iconEye = document.getElementById('icon-eye');
      const iconLock = document.getElementById('icon-lock');

      toggle.addEventListener('click', function() {
        if (input.value.length === 0) return;

        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";
        iconEye.classList.toggle('fa-eye');
        iconEye.classList.toggle('fa-eye-slash');
      });

      input.addEventListener('input', function() {
        if (input.value.length > 0) {
          iconLock.classList.add('d-none');
          iconEye.classList.remove('d-none');
          iconEye.classList.remove('fa-eye-slash');
          iconEye.classList.add('fa-eye');
        } else {
          iconEye.classList.add('d-none');
          iconLock.classList.remove('d-none');
          input.type = "password";
        }
      });
    });
  </script>





</body>

</html>