<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Registrar Viaje
<?= $this->endsection() ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-boxes-packing"></i>
                        Nuevo Viaje
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 col-md-3 mb-4">
                            <h6><i class="fas fa-box"></i>&nbsp;N° Viaje:</h6>
                            <select class="form-control form-control-sm" id="cmbnviaje" name="cmbnviaje">

                            </select>
                        </div>
                        <div class="col-sm-3 col-md-3 mb-4">
                            <h6><i class="fas fa-align-left"></i>&nbsp;Glosa:</h6>
                            <input type="text" id="txtdescripcion" name="txtdescripcion" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-3 col-md-3 mb-4">
                            <h6><i class="fas fa-align-left"></i>&nbsp;F. Inicio:</h6>
                            <input type="date" id="dtfinicio" name="dtfinicio" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" min="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-sm-3 col-md-3 mb-4">
                            <h6><i class="fas fa-align-left"></i>&nbsp;F. Fin:</h6>
                            <input type="date" id="dtffin" name="dtffin" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" min="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->include('mantviajes/vehiculos.php') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->include('mantviajes/conductor.php') ?>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $this->include('mantviajes/destinoorigen.php') ?>
                            </div>
                            <div class="col-md-6">
                                <?= $this->include('mantviajes/destinollegada.php') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-star">
                        <div class="col-md-3 text-star">
                            <button class="btn btn-sm btn-success btn-block" onclick="registrarViaje()">
                                <i class="fas fa-arrow-up-right-from-square"></i>&nbsp;REGISTRAR VIAJE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endsection(); ?>

<?= $this->section('scripts'); ?>
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
<script src="<?= base_url('public/dist/js/pages/viajes.js') ?>"></script>
<?= $this->endsection('scripts'); ?>