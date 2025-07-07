<?php

namespace App\Controllers;

use App\Models\VehiculosModel;
use CodeIgniter\Controller;

class VehiculosController extends Controller
{
    public function getDatosXcod(){
        $unidadesModel=new VehiculosModel();
        $cod=$this->request->getGet('cod');
        $data=$unidadesModel->getDatosXcod($cod);
        return $this->response->setJSON($data);
    }
}

?>