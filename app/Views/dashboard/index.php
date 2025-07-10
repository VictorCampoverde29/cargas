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
          <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-success">
              <div class="inner">
                
                <p>Ventas Mes</p>
              </div>
              <div class="icon">
                <i class="fas fa-dollar-sign"></i>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-danger">
              <div class="inner">

                <p>Compras Mes</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-info">
              <div class="inner">
                
                <p>N° Clientes</p>
              </div>
              <div class="icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="small-box bg-primary">
              <div class="inner">
                
                <p>N° Proveedores</p>
              </div>
              <div class="icon">
                <i class="fas fa-truck-moving"></i>
              </div>
            </div>
          </div>


          <!-- ./col -->
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-1"></i>
              Compras y Ventas
            </h3>

          </div><!-- /.card-header -->
          <div class="card-body">
            
        </div>
        <!-- /.card -->



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
                <h7><?php echo date('d/m/Y h:i a'); ?></h7>
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
        <div class="card bg-gradient-warning">
          <div class="card-header border-0">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-1"></i>
              Situación Actual
            </h3>
          </div>
          
        </div>

      </section>
    </div>
    <!-- right col -->
    <div class="row">
      <!-- DEUDA SOLES CONTENEDOR -->
      <div class="col-12 col-sm-6 col-md-3">
        <div class="small-box border border-warning" style="background-color: #fffbe6;">
          <div class="inner">
            <p class="text-warning">Deuda Total por cobrar / SOLES</p>
            
          </div>
          <div class="icon text-warning">
            <i class="fas fa-money-bill-wave"></i>
          </div>
        </div>
        <!-- DEUDA DOLARES CONTENEDOR -->
        <div class="small-box border border-success mt-2" style="background-color: #e6fff2;">
          <div class="inner">
            <p class="text-success">Deuda Total por cobrar / DOLARES</p>
            
          </div>
          <div class="icon text-success">
            <i class="fas fa-dollar-sign"></i>
          </div>
        </div>
      </div>
      <!-- TABLA CLIENTES DEUDA -->
      <div class="col-12 col-md-9">
        <div class="card">
          <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-users"></i> Clientes con Mayor Deuda</h3>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped mb-0">
                <thead class="thead-dark">
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th class="text-end">Deuda Total (S/)</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
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