<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>G.ASIU | Dashboard</title>

  <!-- Favicon para icono pestaña -->
  <link rel="icon" href="<?= base_url('public/dist/img/favicon-32x32.png') ?>" sizes="32x32" />
  <link rel="icon" href="<?= base_url('public/dist/img/favicon-192x192.png') ?>" sizes="192x192" />
  <link rel="apple-touch-icon" href="<?= base_url('public/dist/img/apple-touch-icon.png') ?>" />
  <meta name="msapplication-TileImage" content="<?= base_url('public/dist/img/ms-tile-144x144.png') ?>" />

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
      0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5);
      }

      70% {
        box-shadow: 0 0 0 12px rgba(220, 53, 69, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
      }
    }

    @keyframes marquee-track {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
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
  </div>
  </div>
  <!-- ./wrapper -->
  <script>
    var baseURL = '<?= base_url(); ?>';
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
</body>

</html>