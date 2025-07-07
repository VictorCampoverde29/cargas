<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes-packing"></i>
            Vehiculo
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;VEHICULO</h6>
                <select class="form-control form-control-sm" id="cmbvehiculo">
                    <?php foreach ($vehiculo as $vehiculos): ?>
                        <option value="<?= esc($vehiculos['idunidades']); ?>">
                            <?= esc($vehiculos['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;PLACA</h6>
                <input type="text" id="txtplaca" name="txtplaca" class="form-control form-control-sm" disabled>
            </div>
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;CERTIFICADO HAB.</h6>
                <input type="text" id="txtcerthabi" name="txtcerthabi" class="form-control form-control-sm" disabled>
            </div>
        </div>
    </div>
</div>