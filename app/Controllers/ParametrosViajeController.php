<?php
Namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ParametrosViaje;

class ParametrosViajeController extends Controller
{
    public function obtenerParametrosViaje()
    {
        $idunidades = $this->request->getPost('unidad');
        $destino_origen = $this->request->getPost('origen');
        $destino_destino = $this->request->getPost('destino');
        $idcondiciones_parametros_gastoviaje = $this->request->getPost('condicion');
        $carreta = $this->request->getPost('carreta');

        $parametrosViajeModel = new ParametrosViaje();
        $parametros = $parametrosViajeModel->obtenerParametrosViaje(
            $idunidades,
            $destino_origen,
            $destino_destino,
            $idcondiciones_parametros_gastoviaje,
            $carreta
        );

        return $this->response->setJSON($parametros);
        
    }
}