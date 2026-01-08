<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Gastos Viajes</title>
    <link rel="icon" href="<?= base_url('public/dist/img/favicon-32x32.png') ?>" sizes="32x32" />
    <link rel="icon" href="<?= base_url('public/dist/img/favicon-192x192.png') ?>" sizes="192x192" />
    <link rel="apple-touch-icon" href="<?= base_url('public/dist/img/apple-touch-icon.png') ?>" />
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
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
</head>

<body>
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <div class="d-flex justify-content-center align-items-center">
                            <h3 class="card-title m-0">
                                <i class="fas fa-search-dollar"></i>
                                GASTOS REGISTRADOS
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <input type="text" id="txtidviajes" name="txtidviajes">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="filtrorigen"> ORIGEN</label>
                                    <select class="form-control form-control-sm" id="cmbfiltrorigen" name="cmbfiltrorigen">
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
                                    <select class="form-control form-control-sm" id="cmbfiltrodestino" name="cmbfiltrodestino">
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
                                    <select class="form-control form-control-sm" id="cmbfiltrounidad" name="cmbfiltrounidad">
                                        <<?php foreach ($unidad as $unidades): ?>
                                            <option value="<?= esc($unidades['idunidades']); ?>">
                                            <?= esc($unidades['descripcion']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="btnFormaPago" onclick="obtenerGastosViajes()">
                                    <i class="fas fa-file-invoice"></i>&nbsp; Consultar Gasto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-info card-outline d-none">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title m-0">
                                <i class="fas fa-search-dollar"></i>
                                Detalle Gasto
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="txtviaje"> VIAJE</label>
                                    <input type="text" class="form-control form-control-sm" id="txtviaje" name="txtviaje" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="txtuni"> UNIDAD</label>
                                    <input type="text" class="form-control form-control-sm" id="txtuni" name="txtuni" disabled>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="txtdist"> DISTANCIA</label>
                                    <input type="text" class="form-control form-control-sm" id="txtdist" name="txtdist" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div id="accordion"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="text" class="form-control form-control-sm" id="txtdist" name="txtdist" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var baseURL = '<?= base_url(); ?>';
    </script>
    <script src="<?= base_url('public/plugins/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
    <script src="<?= base_url('public/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
    <script src="<?= base_url('public/dist/js/adminlte.js') ?>"></script>
    <script src="<?= base_url('public/dist/js/pages/generales.js?v=' . env('VERSION')) ?>"></script>
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
    <script src="<?= base_url('public/dist/js/pages/gastoviaje.js?v=' . env('VERSION')) ?>"></script>
</body>

</html>