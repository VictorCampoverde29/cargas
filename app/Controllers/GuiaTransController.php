<?php

namespace App\Controllers;

use App\Models\GuiaTransModel;
use CodeIgniter\Controller;

class GuiaTransController extends Controller
{
    public function traerGuias()
    {
        $fechaInicio = $this->request->getGet("fechaInicio");
        $fechaFin = $this->request->getGet("fechaFin");
        $idSucursal = $this->request->getGet("codigosucursal");

        $model = new GuiaTransModel();
        $data = $model->traerGuiasXRangoFechaYSucursal($fechaInicio, $fechaFin, $idSucursal);
        return $this->response->setJSON($data);
    }
}
