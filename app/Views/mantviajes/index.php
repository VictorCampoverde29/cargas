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
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-wrench"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a href="#" class="dropdown-item" onclick="abrirModalViaje()">+ AGREGAR NUEVO VIAJE</a>
                            </div>
                        </div>
                    </div>
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
    </div>
</div>
<div class="modal fade" id="mdlviaje" tabindex="-1" aria-labelledby="mdlviajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill"></i>&nbsp;REGISTRAR VIAJE
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4 col-md-4 mb-4">
                        <label for="txtdescripcion"><i class="fas fa-align-left"></i>&nbsp;GLOSA:</label>
                        <input type="text" id="txtdescripcion" name="txtdescripcion" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-2 col-md-2 mb-4">
                        <label for="dtfinicio"><i class="fas fa-align-left"></i>&nbsp;F. INICIO:</label>
                        <input type="date" id="dtfinicio" name="dtfinicio" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" min="<?= date('Y-m-d'); ?>">
                    </div>
                    <div class="col-sm-2 col-md-2 mb-4">
                        <label for="dtffin"><i class="fas fa-align-left"></i>&nbsp;F. FIN:</label>
                        <input type="date" id="dtffin" name="dtffin" class="form-control form-control-sm" value="<?= date('Y-m-d'); ?>" min="<?= date('Y-m-d'); ?>">
                    </div>
                    <div class="col-sm-4 col-md-4">
                        <label for="cmbconductor"><i class="fas fa-align-left"></i>&nbsp;CONDUCTOR:</label>
                        <select class="form-control form-control-sm" id="cmbconductor">
                            <?php foreach ($conductor as $conductores): ?>
                                <option value="<?= esc($conductores['idconductor']); ?>">
                                    <?= esc($conductores['nombrecompleto']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-5 col-md-5">
                        <label for="cmborigen"><i class="fas fa-align-left"></i>&nbsp;ORIGEN:</label>
                        <select class="form-control form-control-sm" id="cmborigen">
                            <?php foreach ($destino as $destinos): ?>
                                <option value="<?= esc($destinos['iddestino']); ?>">
                                    <?= esc($destinos['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-5 col-md-5">
                        <label for="cmbdestino"><i class="fas fa-align-left"></i>&nbsp;DESTINO:</label>
                        <select class="form-control form-control-sm" id="cmbdestino">
                            <?php foreach ($destino as $destinos): ?>
                                <option value="<?= esc($destinos['iddestino']); ?>">
                                    <?= esc($destinos['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-2 col-md-2">
                        <label for="cmbvehiculo"><i class="fas fa-align-left"></i>&nbsp;VEHICULO:</label>
                        <select class="form-control form-control-sm" id="cmbvehiculo">
                            <?php foreach ($vehiculo as $vehiculos): ?>
                                <option value="<?= esc($vehiculos['idunidades']); ?>">
                                    <?= esc($vehiculos['descripcion']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 ms-auto">
                        <label>&nbsp;</label>
                        <button class="btn btn-sm btn-success w-100" onclick="registrarViaje()">
                            <i class="fas fa-arrow-up-right-from-square"></i>&nbsp;REGISTRAR VIAJE
                        </button>
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
<!---->
<div class="modal fade" id="mdlservicios" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdlserviciosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">SERVICIOS DE VIAJE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtidviaje" name="txtidviaje">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="txtviajeprin"><i class="fas fa-boxes-stacked"></i>&nbsp;BUSCAR GUIA</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtviajeprin" name="txtviajeprin" placeholder="VIAJE">
                            <button class="btn btn-primary btn-sm" type="button" id="btneleremi" name="btneleremi" onclick="abrirModalGuia()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="dtfserv" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;F. SERVICIO</label>
                        <div class="input-group">
                            <input type="date" class="form-control mb-3 form-control-sm" id="dtfserv" name="dtfserv">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="txtflete" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;FLETE</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtflete" name="txtflete" placeholder="FLETE">
                        </div>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="txtglosaserv" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;GLOSA</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3 form-control-sm" id="txtglosaserv" name="txtglosaserv" placeholder="GLOSA">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="txtemisor" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;EMISOR</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtemisor" name="txtemisor">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtreceptor" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;RECEPTOR</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtreceptor" name="txtreceptor">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="txtremi" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;ORIGEN</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtorigserv" name="txtorigserv">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="txtdesti" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;LLEGADA</label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtllegserv" name="txtllegserv">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="cmbtipocarga" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;T. CARGA</label>
                        <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm" id="cmbtipocarga">
                                <?php foreach ($tipo as $tipos): ?>
                                    <option value="<?= esc($tipos['idcarga']); ?>">
                                        <?= esc($tipos['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="OTRO">OTRO</option>
                            </select>
                            <input type="text" min="1" class="form-control form-control-sm" id="txtcargaserv" name="txtcargaserv" placeholder="CARGA" disabled="">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cmbestadoserv" class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;ESTADO</label>
                        <select class="form-control form-control-sm" id="cmbestadoserv" name="cmbestadoserv">
                            <option value="EN CAMINO">EN CAMINO</option>
                            <option value="ENTREGADO">ENTREGADO</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-star">
                        <label>&nbsp;</label>
                        <button class="btn btn-sm btn-success btn-block" onclick="registrarServicio()">
                            <i class="fas fa-arrow-up-right-from-square"></i>&nbsp;REGISTRAR SERVICIO
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="tblservicios" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="background-color: #000000; color:#FFFFFF;">
                                        <th>ID SERVICIO</th>
                                        <th>N° GUIA</th>
                                        <th>F. SERVICIO</th>
                                        <th>FLETE</th>
                                        <th>GLOSA</th>
                                        <th>EMISOR</th>
                                        <th>RECEPTOR</th>
                                        <th>ORIGEN</th>
                                        <th>LLEGADA</th>
                                        <th>TIPO CARGA</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> CERRAR
                </button>
            </div>
        </div>
    </div>
</div>
<!-- FIN DIV-->
<div class="modal fade" id="mdlbuscarguia" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mdlbuscarguiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill"></i>&nbsp;BUSCAR GUIA
                </h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtidguia" name="txtidguia">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;F. INICIO</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="dtfiniguia" name="dtfiniguia" value="<?= date('Y-m-01') ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;F. FIN</label>
                        <div class="input-group">
                            <input type="date" class="form-control mb-3 form-control-sm" id="dtffinguia" name="dtffinguia" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label class="fw-bold"><i class="fas fa-boxes-stacked"></i>&nbsp;SURCURSAL</label>
                        <select class="form-control form-control-sm" id="cmbsucursalguia" name="cmbsucursalguia">
                            <?php foreach ($sucursal as $sucursales): ?>
                                <option value="<?= esc($sucursales['idsucursal']); ?>">
                                    <?= esc($sucursales['descripcion']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 text-star">
                        <label>&nbsp;</label>
                        <button class="btn btn-sm btn-success btn-block" onclick="traerGuias()">
                            <i class="fas fa-arrow-up-right-from-square"></i>&nbsp;CONSULTAR GUIAS
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tblguias" class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="background-color: #000000; color:#FFFFFF;">
                                                <th>N° GUIA</th>
                                                <th>F. SERVICIO</th>
                                                <th>ORIGEN</th>
                                                <th>LLEGADA</th>
                                                <th>FLETE</th>
                                                <th>EMISOR</th>
                                                <th>RECEPTOR</th>
                                                <th>GLOSA</th>
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
<script src="<?= base_url('public/dist/js/pages/viajes.js') ?>"></script>
<script src="<?= base_url('public/dist/js/pages/servicios.js') ?>"></script>
<?= $this->endsection('scripts'); ?>