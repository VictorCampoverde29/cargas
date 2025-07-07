<?php

namespace App\Controllers;

use App\Models\ConductorModel;
use CodeIgniter\Controller;

class ConductorController extends Controller
{
    public function getDatosXcod(){
        $conductorModel=new ConductorModel();
        $cod=$this->request->getGet('cod');
        $data=$conductorModel->getDatosXcod($cod);
        return $this->response->setJSON($data);
    }
}

?>