<?= $this->extend('dashboard/template.php'); ?>

<?= $this->section('titulo'); ?>
Mantenimiento Carga
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
                Carga
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3 col-md-3 mb-4">
                    <h6><i class="fas fa-box"></i>&nbsp;Tipo:</h6>
                    <select class="form-control form-control-sm" id="cmbtipocarga" name="cmbtipocarga">
                        <?php foreach ($tipo as $tipos): ?>
                            <option value="<?= esc($tipos['idtipo_carga']); ?>">
                                <?= esc($tipos['tipo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-3 col-md-3 mb-4">
                    <h6><i class="fas fa-align-left"></i>&nbsp;Descripcion:</h6>
                    <input type="text" id="txtdescripcion" name="txtdescripcion" class="form-control form-control-sm" >
                </div>
                <div class="col-sm-2 col-md-2 mb-4">
                    <h6><i class="fas fa-sync-alt"></i>&nbsp;Estado:</h6>
                    <select class="form-control form-control-sm" id="cmbestadocarga">
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
                <div class="col-sm-2 col-md-2 mb-4">
                    <h6>&nbsp;</h6>
                    <button type="button" class="btn btn-primary btn-sm" id="btnagregar" onclick="agregarCarga()">
                        <i class="fas fa-plus"></i>&nbsp;AGREGAR
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table id="tblcarga" class="table table-bordered table-striped">
                    <thead>
                        <tr style="background-color: #000000; color:#FFFFFF;">
                            <th>ID</th>
                            <th>TIPO CARGA</th>
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
<script>
    var tiposCarga = <?= json_encode($tipo) ?>;
</script>
<script src="<?= base_url('public/dist/js/pages/carga.js') ?>"></script>
<?= $this->endsection('scripts'); ?>