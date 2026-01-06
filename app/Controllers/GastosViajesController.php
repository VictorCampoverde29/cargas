<?php

namespace App\Controllers;
use App\Models\CategoriaViajeModel;
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

    public function obtenerGastosViaje(){
        $gastosviaje = new GastosViajeModel();
        $data = $gastosviaje->obtenerGastosViaje();
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerDetalleGastosViaje(){
        $cod = $this->request->getGet('cod');
        $gastosviaje = new DetalleGastosViajeModel();
        $data = $gastosviaje->obtenerDetalleGastosViaje($cod);
        return $this->response->setJSON(['data' => $data]);
    }


}