<?php

namespace App\Controllers;

use App\Models\VentasModel;
use CodeIgniter\Controller;

class VentasController extends Controller
{
    public function verificarVenta()
    {
        $ventasModel = new VentasModel();
        $numeroGuia = $this->request->getGet('numero_guia');
        $data = $ventasModel->verificarVentaPorGuia($numeroGuia);
        return $this->response->setJSON($data);
    }
}
