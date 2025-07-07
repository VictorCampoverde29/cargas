<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Registrar Servicios
<?= $this->endsection() ?>

<?= $this->section('styles'); ?>
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('public/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endsection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card card-default color-palette-box">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-boxes-packing"></i>
                Servicios
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblviajes" class="table table-bordered table-striped">
                    <thead>
                        <tr style="background-color: #000000; color:#FFFFFF;">
                            <th>ID</th>
                            <th>CONDUCTOR</th>
                            <th>UNIDAD</th>
                            <th>F. INICIO</th>
                            <th>F. FIN</th>
                            <th>OBSERVACIONES</th>
                            <th>ORIGEN</th>
                            <th>LLEGADA</th>
                            <th>ESTADO</th>
                            <th>ACCION</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdlservicios" tabindex="-1" aria-labelledby="mdlserviciosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill"></i>&nbsp;SERVICIOS DE VIAJE
                </h5>
            </div>
            <div class="modal-body">
                <input type="text" id="txtidviaje" name="txtidviaje">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;VIAJE</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtviajeprin" name="txtviajeprin" placeholder="VIAJE" disabled>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;T. CARGA</label>
                        <select class="form-control form-control-sm" id="cmbtipocarga" name="cmbtipocarga">
                            <?php foreach ($tipo as $tipos): ?>
                                <option value="<?= esc($tipos['idtipo_carga']); ?>">
                                    <?= esc($tipos['tipo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;N° GUIA</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtnguiaserv" name="txtnguiaserv" placeholder="N° GUIA">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;F. SERVICIO</label>
                        <div class="input-group">
                            <input type="date" class="form-control mb-3 form-control-sm" id="dtfserv" name="dtfserv">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;ORIGEN</label>
                        <select class="form-control form-control-sm" id="cmborigenserv" name="cmborigenserv">
                            <?php foreach ($destino as $destinos): ?>
                                <option value="<?= esc($destinos['iddestino']); ?>">
                                    <?= esc($destinos['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;DESTINO</label>
                        <select class="form-control form-control-sm" id="cmbllegadaserv" name="cmbllegadaserv">
                            <?php foreach ($destino as $destinos): ?>
                                <option value="<?= esc($destinos['iddestino']); ?>">
                                    <?= esc($destinos['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;FLETE</label>
                        <select class="form-control form-control-sm" id="cmbfleteserv" name="cmbfleteserv">
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;DESTINATARIO</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtdestiserv" name="txtdestiserv" placeholder="DESTINATARIO">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;ESTADO</label>
                        <select class="form-control form-control-sm" id="cmbfleteserv" name="cmbfleteserv">
                            <option value="PENDIENTE">PENDIENTE</option>
                            <option value="EN CAMINO">EN CAMINO</option>
                            <option value="ENTREGADO">ENTREGADO</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="mb-3 fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;GLOSA</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtglosaserv" name="txtglosaserv" placeholder="GLOSA">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tblchasis" class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="background-color: #000000; color:#FFFFFF;">
                                                <th>TIPO CARGA</th>
                                                <th>N° GUIA</th>
                                                <th>F. SERVICIO</th>
                                                <th>ORIGEN</th>
                                                <th>LLEGADA</th>
                                                <th>FLETE</th>
                                                <th>RECEPTOR</th>
                                                <th>GLOSA</th>
                                                <th>ESTADO</th>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> CERRAR
                </button>
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
<script src="<?= base_url('public/dist/js/pages/servicios.js') ?>"></script>
<?= $this->endsection('scripts'); ?>