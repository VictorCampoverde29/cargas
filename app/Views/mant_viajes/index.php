<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Mantenimiento Viajes
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
                        <i class="fa fa-road"></i>
                        Viajes Registrados
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
                            <thead class="thead-dark text-center">
                                <tr>
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
<!------------------------------------------- MODAL PARA REGISTRAR VIAJE --------------------------------------------->
<div class="modal fade" id="mdlviaje" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlviajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">REGISTRO DE VIAJE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtdescripcion"><i class="fa fa-sticky-note"></i> GLOSA:</label>
                            <input type="text" id="txtdescripcion" name="txtdescripcion" class="form-control form-control-sm" placeholder="Glosa" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dtfinicio"><i class="fa fa-calendar-plus"></i> F. INICIO:</label>
                            <input type="date" id="dtfinicio" name="dtfinicio" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dtffin"><i class="fa fa-calendar-check"></i> F. FIN:</label>
                            <input type="date" id="dtffin" name="dtffin" class="form-control form-control-sm" min="<?= date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbconductor"><i class="fa fa-id-badge"></i> CONDUCTOR:</label>
                            <select class="form-control form-control-sm" id="cmbconductor">
                                <?php foreach ($conductor as $conductores): ?>
                                    <option value="<?= esc($conductores['idconductor']); ?>">
                                        <?= esc($conductores['nombrecompleto']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtorigen"><i class="fa fa-map-marker-alt"></i> ORIGEN:</label>
                            <input type="text" id="txtorigen" name="txtorigen" class="form-control form-control-sm" placeholder="Origen" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtdestino"><i class="fa fa-map-marker"></i> DESTINO:</label>
                            <input type="text" id="txtdestino" name="txtdestino" class="form-control form-control-sm" placeholder="Destino" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbvehiculo"><i class="fa fa-truck"></i> VEHICULO:</label>
                            <select class="form-control form-control-sm" id="cmbvehiculo">
                                <?php foreach ($vehiculo as $vehiculos): ?>
                                    <option value="<?= esc($vehiculos['idunidades']); ?>">
                                        <?= esc($vehiculos['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnregistrarv" name="btnregistrarv" class="btn btn-success mr-2" onclick="registrarViaje()">
                    <i class="fa-solid fa-floppy-disk"></i> REGISTRAR
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> CERRAR
                </button>
            </div>
        </div>
    </div>
</div>
<!------------------------------------------------- MODAL SERVICIOS --------------------------------------------->
<div class="modal fade" id="mdlservicios" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlserviciosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Servicios de Viaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtidviaje" name="txtidviaje">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="txtviajeprin"><i class="fa fa-file-alt"></i> NUMERO DE GUIA</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="txtviajeprin" name="txtviajeprin" placeholder="Guia" autocomplete="off">
                                <div class="input-group-append">
                                    <button class="btn btn-info" type="button" id="btneleremi" name="btneleremi" onclick="abrirModalGuia()"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtflete"><i class="fa fa-money-bill-wave"></i> FLETE</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtflete" name="txtflete" placeholder="Flete" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="txtglosaserv"><i class="fa fa-sticky-note"></i> GLOSA</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtglosaserv" name="txtglosaserv" placeholder="Glosa" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtemisor"><i class="fa fa-paper-plane"></i> EMISOR</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtemisor" name="txtemisor" placeholder="Emisor" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtreceptor"><i class="fa fa-inbox"></i> RECEPTOR</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtreceptor" name="txtreceptor" placeholder="Receptor" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtorigserv"><i class="fa fa-map-marker-alt"></i> ORIGEN</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtorigserv" name="txtorigserv" placeholder="Origen" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="txtllegserv"><i class="fa fa-map-marker"></i> LLEGADA</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtllegserv" name="txtllegserv" placeholder="Llegada" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbtipocarga"><i class="fa fa-box"></i> T. CARGA</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm" id="cmbtipocarga">
                    
                                    <option value="OTRO">OTRO</option>
                                </select>
                                <input type="text" min="1" class="form-control form-control-sm" id="txtcargaserv" name="txtcargaserv" placeholder="CARGA" style="display: none;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cmbestadoserv"><i class="fa fa-info-circle"></i> ESTADO</label>
                            <select class="form-control form-control-sm" id="cmbestadoserv" name="cmbestadoserv">
                                <option value="EN CAMINO">EN CAMINO</option>
                                <option value="ENTREGADO">ENTREGADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="dtfserv"><i class="fa fa-calendar-day"></i> F. SERVICIO</label>
                            <div class="input-group">
                                <input type="date" class="form-control form-control-sm" id="dtfserv" name="dtfserv">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-sm btn-info btn-block" onclick="registrarServicio()">
                                + AGREGAR SERVICIO
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title" id="lblnombredire"><i class="fa fa-list"></i> Servicios registrados por Viaje</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tblservicios" class="table table-bordered table-striped table-sm">
                                        <thead class="thead-dark text-center">
                                            <tr>
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
<!----------------------------------------- MODAL BUSCAR GUIA ----------------------------------------------------->
<div class="modal fade" id="mdlbuscarguia" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlbuscarguiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Buscar Guía</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtidguia" name="txtidguia">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtfiniguia"><i class="fa fa-calendar-plus"></i> F. INICIO</label>
                            <div class="input-group">
                                <input type="date" class="form-control form-control-sm" id="dtfiniguia" name="dtfiniguia">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtffinguia"><i class="fa fa-calendar-check"></i> F. FIN</label>
                            <div class="input-group">
                                <input type="date" class="form-control mb-3 form-control-sm" id="dtffinguia" name="dtffinguia">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbsucursalguia"><i class="fa fa-store"></i> SURCURSAL</label>
                            <select class="form-control form-control-sm" id="cmbsucursalguia" name="cmbsucursalguia">
                                <?php foreach ($sucursal as $sucursales): ?>
                                    <option value="<?= esc($sucursales['idsucursal']); ?>">
                                        <?= esc($sucursales['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button class="btn btn-sm btn-primary btn-block" onclick="traerGuias()">
                                <i class="fas fa-arrow-up-right-from-square"></i> BUSCAR GUIAS
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fa fa-file-alt"></i> Guias de Transportista</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tblguias" class="table table-bordered table-striped table-sm">
                                        <thead class="thead-dark text-center">
                                            <tr>
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
<!----------------------------------------- MODAL PDF ----------------------------------------------------->
<div class="modal fade" id="modalpdf" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    &nbsp;<i class="fas fa-file-pdf"></i> VISOR DE DOCUMENTO PDF
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0">
                <div class="embed-responsive" style="height: 80vh;">
                    <iframe id="iframepdf" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
           <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> CERRAR
                </button>
            </div>
        </form>
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
<?= $this->endsection('scripts'); ?>