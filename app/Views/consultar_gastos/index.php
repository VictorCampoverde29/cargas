<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Consultar Gastos Viajes
<?= $this->endsection() ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="fas fa-search-dollar"></i>
                            Gastos Registrados
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="filtrorigen"> ORIGEN</label>
                                <select class="form-control form-control-sm" id="filtrorigen" name="filtrorigen">
                                    <<?php foreach ($destino as $destinos): ?>
                                        <option value="<?= esc($destinos['iddestino']); ?>">
                                        <?= esc($destinos['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="filtrodestino"> DESTINO</label>
                                <select class="form-control form-control-sm" id="filtrodestino" name="filtrodestino">
                                    <<?php foreach ($destino as $destinos): ?>
                                        <option value="<?= esc($destinos['iddestino']); ?>">
                                        <?= esc($destinos['nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="filtrounidad"> UNIDAD</label>
                                <select class="form-control form-control-sm" id="filtrounidad" name="filtrounidad">
                                    <<?php foreach ($unidad as $unidades): ?>
                                        <option value="<?= esc($unidades['idunidades']); ?>">
                                        <?= esc($unidades['descripcion']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-sm w-100" id="btnFormaPago">
                                <i class="fas fa-file-invoice"></i>&nbsp; Consultar Gasto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="fas fa-search-dollar"></i>
                            Detalle Gasto
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    
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
<script src="<?= base_url('public/dist/js/pages/mant_viajes.js?v=' . env('VERSION')) ?>"></script>
<script src="<?= base_url('public/dist/js/pages/servicios.js?v=' . env('VERSION')) ?>"></script>
<script src="<?= base_url('public/dist/js/pages/viajes_conductor.js?v=' . env('VERSION')) ?>"></script>
<?= $this->endsection('scripts'); ?>