<?php

namespace App\Controllers;

use App\Models\DestinosModel;
use App\Models\TipoCargaModel;
use CodeIgniter\Controller;
use Greenter\Model\Despatch\Despatch;

class RegistrarServicioController extends Controller
{
    public function index()
    {
        $Destinos = new DestinosModel();
        $TipoCarga = new TipoCargaModel();
        $data['tipo'] = $TipoCarga->traerTipoCarga();
        $data['destino'] = $Destinos->getDestinos();
        return view('regservicios/index', $data);
    }
}

?>