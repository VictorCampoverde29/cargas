<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Mantenimiento Condiciones Viajes
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
                <i class="fas fa-tasks"></i>
                Condiciones Registradas
            </h3>
            <div class="card-tools">
                <div class="btn-group">
                    <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-wrench"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="#" class="dropdown-item" onclick="abrirModalCondicion()">+ AGREGAR NUEVA CONDICION</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tblcondicionesviaje" class="table table-bordered table-striped table-sm">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>DESCRIPCION</th>
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
<!----------------------------------------------- MODAL CONDICIONES VIAJE ------------------------------------------------->
<div class="modal fade" id="mdlcondiciones" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="lbltitulocondicion"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="txtidcondicion" name="txtidcondicion" value="0">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="txtdescripcioncondi"><i class="fas fa-align-left"></i> DESCRIPCION</label>
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" id="txtdescripcioncondi" name="txtdescripcioncondi" placeholder="Descripcion" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cmbestadocondi"><i class="fas fa-sync-alt"></i> ESTADO</label>
                            <select class="form-control form-control-sm" id="cmbestadocondi">
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" id="btnregistrarcondi" name="btnregistrarcondi" class="btn btn-success mr-2" onclick="agregarDestino()">
                    <i class="fa-solid fa-floppy-disk"></i> REGISTRAR
                </button>
                <button type="button" id="btneditarcondi" name="btneditarcondi" class="btn btn-warning mr-2" onclick="editarDestino()">
                    <i class="fas fa-pencil-alt"></i> EDITAR
                </button>
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
<script src="<?= base_url('public/dist/js/pages/condicionviaje.js?v=' . env('VERSION')) ?>"></script>
<?= $this->endsection('scripts'); ?>