<?php

namespace App\Controllers;
use App\Models\CategoriaViajeModel;
use App\Models\DestinosModel;
use App\Models\GastosViajeModel;
use App\Models\VehiculosModel;
use App\Models\ConductorModel;
use App\Models\DetalleGastosViajeModel;
use CodeIgniter\Controller;


class GastosViajesController extends Controller
{
    public function index()
    {
        $Unidades = new VehiculosModel();
        $Conductores = new ConductorModel();
        $CategoriaViaje = new CategoriaViajeModel();
        $data['categoria'] = $CategoriaViaje->getCategoriasActivas();
        $data['unidad'] = $Unidades->getUnidadesGuia();
        $data['conductor'] = $Conductores->getConductoresViaje();
        return view('gastos_viajes/index', $data);
    }

    public function indexConsultarGastos(){
        $Unidades = new VehiculosModel();
        $Destino = new DestinosModel();
        $data['destino'] = $Destino->selectDestinos();
        $data['unidad'] = $Unidades->getUnidadesGuia();
        return view('consultar_gastos/index', $data);
    }

    public function obtenerGastosViaje(){
        $gastosviaje = new GastosViajeModel();
        $data = $gastosviaje->obtenerGastosViaje();
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerGastosViajePorCodigo(){
        $orig = $this->request->getGet('orig');
        $dest = $this->request->getGet('dest');
        $uni = $this->request->getGet('uni');
        $gastosviaje = new GastosViajeModel();
        $data = $gastosviaje->obtenerGastosViajePorCodigo($orig, $dest, $uni);
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerDetalleGastosViaje(){
        $cod = $this->request->getGet('cod');
        $gastosviaje = new DetalleGastosViajeModel();
        $data = $gastosviaje->obtenerDetalleGastosViaje($cod);
        return $this->response->setJSON(['data' => $data]);
    }


}