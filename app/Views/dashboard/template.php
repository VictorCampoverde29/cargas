<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>G.ASIU | Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('public/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="<?= base_url('public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/jqvmap/jqvmap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/daterangepicker/daterangepicker.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/summernote/summernote-bs4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('public/plugins/sweetalert2/sweetalert2.min.css'); ?>">
  <?= $this->renderSection('styles'); ?>
  <style>
    .warning-banner {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f8d7da;
      border-radius: .25rem;
      margin-bottom: 1rem;
      animation: pulse-warning 2.5s infinite;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
      height: 54px;
      display: flex;
      align-items: center;
    }
    .marquee-track {
      display: inline-block;
      white-space: nowrap;
      will-change: transform;
      animation: marquee-track 30s linear infinite;
    }
    .marquee-message {
      display: inline-block;
      padding: 0 40px;
      font-size: 1rem;
      line-height: 54px;
      vertical-align: middle;
    }
    .marquee-message .fas {
      margin-right: 8px;
    }
    .marquee-message a {
      color: #721c24;
      font-weight: bold;
      text-decoration: underline;
    }
    @keyframes pulse-warning {
      0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5); }
      70% { box-shadow: 0 0 0 12px rgba(220, 53, 69, 0); }
      100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    @keyframes marquee-track {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    .warning-banner:hover .marquee-track {
      animation-play-state: paused;
    }
  </style>
  <link rel="icon" type="image/png" href="<?= base_url('public/dist/img/favicon.png') ?>" />
</head>

<body class="layout-navbar-fixed sidebar-collapse layout-fixed text-sm">
  <div class="wrapper">

    <!-- 
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?= base_url('public/dist/img/logo/logogasiu.png') ?>" alt="AdminLTELogo" height="80" width="130">
  </div> -->



    <!-- /.navbar -->
    <?= $this->include('Views/dashboard/nav') ?>
    <?= $this->include('Views/dashboard/aside') ?>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $this->renderSection('titulo'); ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item">
                  <h6><i class="fa fa-building"></i> <?= esc(session()->get('n_sucursal') ?? '----') ?> / <i class="fa fa-home"></i> <?= esc(session()->get('nombrealmacen') ?? '----') ?> </h6>
                </li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div id="main-content-wrapper">
        <?= $this->renderSection('content'); ?>
      </div>

      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
   <?= $this->include('Views/dashboard/footer') ?>

  <div class="modal fade" id="modal-cambio" tabindex="-1" role="dialog" aria-labelledby="tituloModalCambio" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-primary">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="tituloModalCambio"><i class="fas fa-warehouse"></i> Elegir Almacén</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <label for="cmbempresa"><i class="fas fa-building"></i> Empresa</label>
          <select class="form-control form-control-sm" id="cmbempresa" name="cmbempresa"></select>
        </div>
      </div>

      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
        <button type="button" class="btn btn-primary" onclick="cambio_almacen()">
          <i class="fas fa-retweet"></i> Cambiar Almacén
        </button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal-clave" tabindex="-1" role="dialog" aria-labelledby="tituloModalClave" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-info">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="tituloModalClave"><i class="fas fa-key"></i> Cambio de Clave</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p class="text-muted mb-3 text-center">Ingrese su nueva contraseña para actualizarla.</p>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Nueva Clave" id="txtclave" name="txtclave">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times-circle"></i> Cancelar
        </button>
        <button type="submit" class="btn btn-info" onclick="actualizar_password()">
          <i class="fas fa-user-lock"></i> Cambiar Clave
        </button>
      </div>
    </div>
  </div>
</div>

  </div>
  <!-- ./wrapper -->
  <script>
    var baseURL = '<?= base_url(); ?>';
  </script>
  <script>
    var codalmacenses = "<?= session()->get('codigoalmacen') ?? 'NL' ?>";
  </script>
  <!-- jQuery -->
  <script src="<?= base_url('public/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('public/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
  <script src="<?= base_url('public/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
  <script src="<?= base_url('public/dist/js/adminlte.js') ?>"></script>
  <script src="<?= base_url('public/dist/js/pages/generales.js') ?>"></script>
  <?= $this->renderSection('scripts'); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var codalmacenses = "<?= session()->get('codigoalmacen') ?? 'NL' ?>";
      if (codalmacenses === 'NL' || codalmacenses === '') {
        const mainContentWrapper = document.getElementById('main-content-wrapper');
        if (mainContentWrapper) {
          mainContentWrapper.style.opacity = '0.6';
          mainContentWrapper.style.pointerEvents = 'none';
        }
      }
    });
  </script>

</body>

</html>