<?php

namespace App\Controllers;

use App\Models\GuiaTransModel;
use CodeIgniter\Controller;

class GuiaTransController extends Controller
{
    public function traerGuias()
    {
        $guiasModel = new GuiaTransModel();
        $fechaInicio = $this->request->getGet('fechaInicio');
        $fechaFin = $this->request->getGet('fechaFin');
        $codsucursal = $this->request->getGet('codigosucursal');
        $data = $guiasModel->traerGuiasXRangoFechaYSucursal($fechaInicio, $fechaFin, $codsucursal);
        return $this->response->setJSON($data);
    }
}

?>