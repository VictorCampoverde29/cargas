<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes-packing"></i>
            Origen
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;DEPARTAMENTO</h6>
                <select class="form-control form-control-sm" id="cmbdeporigen">
                    <?php foreach ($departamento as $departamentos): ?>
                        <option value="<?= esc($departamentos['departamento']); ?>">
                            <?= esc($departamentos['departamento']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;PROVINCIA</h6>
                <select class="form-control form-control-sm" id="cmbprovorigen"></select>
            </div>
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;DISTRITO</h6>
                <select class="form-control form-control-sm" id="cmbdistorigen"></select>
            </div>
        </div>
    </div>
</div>