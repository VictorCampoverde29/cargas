<div class="card card-default color-palette-box">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-boxes-packing"></i>
            RUTA
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;ORIGEN</h6>
                <select class="form-control form-control-sm" id="cmborigen">
                    <?php foreach ($destino as $destinos): ?>
                        <option value="<?= esc($destinos['iddestino']); ?>">
                            <?= esc($destinos['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-4 col-md-4 mb-4">
                <h6><i class="fas fa-align-left"></i>&nbsp;DESTINO</h6>
                <select class="form-control form-control-sm" id="cmbdestino">
                    <?php foreach ($destino as $destinos): ?>
                        <option value="<?= esc($destinos['iddestino']); ?>">
                            <?= esc($destinos['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>