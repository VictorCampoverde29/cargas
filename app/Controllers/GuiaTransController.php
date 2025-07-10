<?php

namespace App\Controllers;

use App\Models\GuiaTransModel;
use CodeIgniter\Controller;

class GuiaTransController extends Controller
{
    public function traerGuias()
    {
        $model = new GuiaTransModel();
        $fechaInicio = $this->request->getGet("fechaInicio");
        $fechaFin = $this->request->getGet("fechaFin");
        $idSucursal = $this->request->getGet("codigosucursal");

        $data = $model->traerGuiasXRangoFechaYSucursal($fechaInicio, $fechaFin, $idSucursal);
        return $this->response->setJSON($data);
    }
}
