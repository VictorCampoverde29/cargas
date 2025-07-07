<?php

namespace App\Controllers;

use App\Models\DestinosModel;
use CodeIgniter\Controller;

class DestinosController extends Controller
{
    public function getDestinos()
    {
        $DestinoModel = new DestinosModel();
        $destinos =  $DestinoModel->getDestinos();
        return $this->response->setJSON($destinos);
    }
}


?>