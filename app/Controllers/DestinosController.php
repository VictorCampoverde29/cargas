<?php

namespace App\Controllers;

use App\Models\DestinosModel;
use CodeIgniter\Controller;

class DestinosController extends Controller
{
    public function getProvinciaXDep()
    {
        $DestinoModel = new DestinosModel();
        $departamento = $this->request->getGet('dep');
        $departamentos =  $DestinoModel->getProvinciaXDep($departamento);
        return $this->response->setJSON([$departamentos]);
    }

    public function getDistritoXProvDep()
    {
        $DestinoModel = new DestinosModel();
        $provincia = $this->request->getGet('prov');
        $distritos = $DestinoModel->getDistritoXProv($provincia);
        return $this->response->setJSON([$distritos]);
    }
}


?>