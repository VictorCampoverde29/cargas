<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Gastos Viajes
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
            <div class="card card-info card-outline">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title m-0">
                            <i class="fas fa-truck"></i>
                            Gastos Viajes
                        </h3>
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">
                            <a href="#" class="dropdown-item" onclick="abrir_Modal_Ruta()">+ NUEVO GASTO VIAJE</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblgastoviajes" class="table table-bordered table-sm table-striped">
                            <thead class="thead-dark text-center">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>VIAJE</th>
                                    <th>UNIDAD</th>
                                    <th>DISTANCIA (KM)</th>
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

<!------------------------------------------- MODAL PARA REGISTRAR GASTO VIAJE --------------------------------------------->
<div class="modal fade" id="mdlgastoviaje" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlgastoviajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file-invoice-dollar"></i> Registro Gasto Viaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtdest1"><i class="fas fa-map-marker-alt"></i> ORIGEN:</label>
                            <input type="text" class="form-control form-control-sm" id="txtdest1" name="txtdest1" placeholder="Partida" autocomplete="off">
                            <input type="hidden" id="hdnIdDesti1" name="hdnIdDesti1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="txtdest2"><i class="fas fa-flag-checkered"></i> DESTINO:</label>
                            <input type="text" class="form-control form-control-sm" id="txtdest2" name="txtdest2" placeholder="Llegada" autocomplete="off">
                            <input type="hidden" id="hdnIdDesti2" name="hdnIdDesti2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbunidad"><i class="fas fa-truck"></i> UNIDAD:</label>
                            <select class="form-control form-control-sm" id="cmbunidad">
                                <?php foreach ($unidad as $unidades): ?>
                                    <option value="<?= esc($unidades['idunidades']); ?>">
                                        <?= esc($unidades['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="txtdistancia"><i class="fas fa-truck"></i> DISTANCIA (KM):</label>
                            <input type="number" class="form-control form-control-sm" id="txtdistancia" name="txtdistancia" step="0.01" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cmbcarreta"><i class="fas fa-truck"></i> CON CARRETA:</label>
                            <select class="form-control form-control-sm" id="cmbcarreta">
                                <option value="NO">NO</option>
                                <option value="SI">SI</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cmbprecio"><i class="fas fa-tag"></i> PRECIO GALON REF.:</label>
                            <div class="input-group input-group-sm">
                                <select class="form-control form-control-sm" id="cmbprecio" style="max-width: 150px;">
                                    <?php foreach ($consumo_combustible as $consumo_combustibles): ?>
                                        <option value="<?= esc($consumo_combustibles['idconsumo_combustible']); ?>">
                                            <?= esc($consumo_combustibles['descripcion']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" class="form-control form-control-sm" id="txtprecioref" name="txtprecioref" autocomplete="off" placeholder="0.00" required="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="txtgalonesref"><i class="fas fa-truck"></i> TOTAL GALONES:</label>
                            <input type="text" class="form-control form-control-sm" id="txtgalonesref" name="txtgalonesref" autocomplete="off">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            
                            <input type="hidden" class="form-control form-control-sm" id="txttotalcomb" name="txttotalcomb" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnregistrarv" name="btnregistrarv" class="btn btn-primary mr-2" onclick="registrarRuta()">
                    <i class="fa-solid fa-floppy-disk"></i> REGISTRAR
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark"></i> CERRAR
                </button>
            </div>
        </div>
    </div>
</div>
<!------------------------------------------------- MODAL DETALLE GASTOS VIAJES --------------------------------------------->
<div class="modal fade" id="mdldetgastviaje" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdldetgastviajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="tituloDetalleGasto"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtidviaje" name="txtidviaje">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="cmbcategoria"><i class="fas fa-tags"></i> CATEGORIA</label>
                            <select class="form-control form-control-sm" id="cmbcategoria">
                                <?php foreach ($categoria as $categorias): ?>
                                    <option value="<?= esc($categorias['idcategoria_viajes']); ?>">
                                        <?= esc($categorias['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="txtglosagasto"><i class="fas fa-align-left"></i> DESCRIPCION:</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtglosagasto" name="txtglosagasto" placeholder="Descripcion" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="txtmonto"><i class="fas fa-money-bill-wave"></i> MONTO:</label>
                            <input type="text" class="form-control form-control-sm" id="txtmonto" name="txtmonto" placeholder="0.00" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="numcantidad"><i class="fas fa-hashtag"></i> CANTIDAD:</label>
                            <input type="number" class="form-control form-control-sm" id="numcantidad" name="numcantidad" step="0.01" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="txttotal"><i class="fas fa-hashtag"></i> TOTAL:</label>
                            <input type="text" class="form-control form-control-sm" id="txttotal" name="txttotal" placeholder="0.00" autocomplete="off" disabled>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label style="visibility:hidden;">Botón</label>
                            <button type="button" id="btndetgastoviaje" name="btndetgastoviaje" class="btn btn-block btn-sm btn-info" onclick="agregarGasto()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-info card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Gasto Combustible</h3>
                            </div>
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="txtunidad"><i class="fas fa-truck"></i> UNIDAD:</label>
                                        <input type="text" class="form-control form-control-sm" id="txtunidad" name="txtunidad" autocomplete="off" disabled>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txttramo"><i class="fas fa-road"></i> DISTANCIA (KM)</label>
                                                <input type="text" class="form-control form-control-sm" id="txttramo" name="txttramo" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="txtgalones"><i class="fas fa-tint"></i> GALONES</label>
                                                <input type="text" class="form-control form-control-sm" id="txtgalones" name="txtgalones" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="txttotalcombustible"><i class="fas fa-road"></i> TOTAL</label>
                                        <input type="text" class="form-control form-control-sm" id="txttotalcombustible" name="txttotalcombustible" autocomplete="off" disabled>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="mt-2 text-center">GASTOS DEL VIAJE</h6>
                        <hr>
                        <div id="accordion"></div>
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
<script src="<?= base_url('public/dist/js/pages/gastoviaje.js?v=' . env('VERSION')) ?>"></script>
<?= $this->endsection('scripts'); ?>