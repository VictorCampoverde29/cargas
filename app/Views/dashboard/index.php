<?= $this->extend('dashboard/template.php'); ?>
<?= $this->section('titulo'); ?>
Bienvenid@
<?= $this->endsection() ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endsection() ?>

<?= $this->section('content'); ?>
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->

    <!-- Main row -->
    <div class="row">
      <!-- Left col -->
      <section class="col-lg-8 connectedSortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-4">
            <a href="<?= base_url('dashboard/mant_viajes') ?>" style="text-decoration: none; color: inherit;">
              <div class="small-box bg-primary">
                <div class="inner">
                  <p style="font-size: 20px; font-weight: bold;">NUEVO VIAJE</p>
                </div>
                <div class="icon">
                  <i class="fas fa-route"></i>
                </div>
                <div class="small-box-footer">
                  Ir <i class="fas fa-arrow-circle-right"></i>
                </div>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <a href="<?= base_url('dashboard/mant_destino') ?>" style="text-decoration: none; color: inherit;">
              <div class="small-box bg-warning">
                <div class="inner">
                  <p style="font-size: 20px; font-weight: bold;">NUEVO DESTINO</p>
                </div>
                <div class="icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="small-box-footer">
                  Ir <i class="fas fa-arrow-circle-right"></i>
                </div>
              </div>
            </a>
          </div>
          <div class="col-12 col-sm-6 col-md-4">
            <a href="<?= base_url('dashboard/mant_carga') ?>" style="text-decoration: none; color: inherit;">
              <div class="small-box bg-success">
                <div class="inner">
                  <p style="font-size: 20px; font-weight: bold;">NUEVA CARGA</p>
                </div>
                <div class="icon">
                  <i class="fas fa-boxes"></i>
                </div>
                <div class="small-box-footer">
                  Ir <i class="fas fa-arrow-circle-right"></i>
                </div>
              </div>
            </a>
          </div>
          <!-- ./col -->
        </div> <div class="card">
            <div class="card-header bg-gradient-secondary">
              <h3 class="card-title">
                <i class="fa fa-bus"></i>
                Ultimos Viajes
              </h3>
            </div><!-- /.card-header -->
            <div class="card-body">
              <table id="tblultimosviajes" class="table table-bordered table-striped">
                <thead class="thead-dark text-center">
                  <tr>
                    <th>FECHA</th>
                    <th>ORIGEN</th>
                    <th>DESTINO</th>
                    <th>CONDUCTOR</th>
                    <th>ESTADO</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
      </section>
      <section class="col-lg-4 connectedSortable">
        <div class="card card-primary card-outline shadow-none">
          <div class="card-header border-0">
            <h3 class="card-title">
              <i class="fas fa-computer"></i>
              Inicio de Sesión
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body pt-0">
            <!--The calendar -->
            <div class="row">
              <div class="col-3">
                <h6><b>FECHA:</b></h6>
              </div>
              <div class="col-9">
                <h7><?php 
                  date_default_timezone_set('America/Lima');
                  echo date('d/m/Y h:i a'); 
                ?></h7>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <h6><b>NOMBRE:</b></h6>
              </div>
              <div class="col-9">
                <h7><?= esc(session()->get('ca_nombrepersonal')); ?></h7>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <h6><b>PERFIL:</b></h6>
              </div>
              <div class="col-9">
                <h7><?= esc(session()->get('ca_nombreusuariocorto')); ?></h7>
              </div>

            </div>
            <div class="row"></div>
          </div>
          <!-- /.card-body -->
        </div>
        <div class="card">
          <div class="card-header bg-gradient-info">
            <h3 class="card-title text-white">
              <i class="fas fa-chart-pie mr-2"></i>
              Situación Actual
            </h3>
          </div>
          <div class="card-body" style="height: 320px;">
            <div class="chart-responsive" style="position: relative; height: 210px; width: 210px; margin: 0 auto;">
              <canvas id="pieChartViajes"></canvas>
            </div>
          </div>
        </div>
      </section>
    </div>
    <!-- right col -->

    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->

</section>
<!-- Fin tabla clientes deudores -->

<?= $this->endsection() ?>

<?php $this->section('scripts') ?>
<script src="<?= base_url('public/plugins//moment/moment.min.js') ?>"></script>
<script src="<?= base_url('public/plugins//daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?= base_url('public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/chart.js/Chart.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('public/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('public/dist/js/pages/dashboard.js') ?>"></script>

<?php $this->endSection() ?>