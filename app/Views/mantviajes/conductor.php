<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes-packing"></i>
            Conductor
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-md-6 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;NOMBRE</h6>
                <select class="form-control form-control-sm" id="cmbconductor">
                    <?php foreach ($conductor as $conductores): ?>
                        <option value="<?= esc($conductores['idconductor']); ?>">
                            <?= esc($conductores['nombrecompleto']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3 col-md-3 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;DOCUMENTO</h6>
                <input type="text" id="txtdocuconduc" name="txtdocuconduc" class="form-control form-control-sm" disabled>
            </div>
            <div class="col-sm-3 col-md-3 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;LICENCIA</h6>
                <input type="text" id="txtlicenconduc" name="txtlicenconduc" class="form-control form-control-sm" disabled>
            </div>
        </div>
    </div>
</div>