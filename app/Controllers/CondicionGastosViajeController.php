<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\CondicionesParametrosGastosViajeModel;

class CondicionGastosViajeController extends Controller
{
    public function index()
    {
        return view('mant_condiciones/index');
    }

    public function getCondiciones(){
        $CondicionModel = new CondicionesParametrosGastosViajeModel();
        $condicion = $CondicionModel->getCondiciones();
        return $this->response->setJSON($condicion);
    }
}