<?php

namespace App\Controllers;

use App\Models\CategoriaViajeModel;
use App\Models\DestinosModel;
use App\Models\GastosViajeModel;
use App\Models\VehiculosModel;
use App\Models\DetalleGastosViajeModel;
use CodeIgniter\Controller;

class GastosViajesController extends Controller
{
    public function index()
    {
        $Unidades = new VehiculosModel();
        $CategoriaViaje = new CategoriaViajeModel();
        $data['categoria'] = $CategoriaViaje->getCategoriasActivas();
        $data['unidad'] = $Unidades->getUnidadesGuia();
        return view('gastos_viajes/index', $data);
    }

    public function indexConsultarGastos(){
        $Unidades = new VehiculosModel();
        $Destino = new DestinosModel();
        $data['destino'] = $Destino->selectDestinos();
        $data['unidad'] = $Unidades->getUnidadesGuia();
        return view('consultar_gastos/index', $data);
    }

    public function obtenerGastosViaje()
    {
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

    public function obtenerDetalleGastosViaje()
    {
        $cod = $this->request->getGet('cod');
        $gastosviaje = new DetalleGastosViajeModel();
        $data = $gastosviaje->obtenerDetalleGastosViaje($cod);
        return $this->response->setJSON(['data' => $data]);
    }

    public function insert()
    {
        $unidad = $this->request->getPost('unidad');
        $distancia = $this->request->getPost('distancia');
        $origen = $this->request->getPost('origen');
        $destino = $this->request->getPost('destino');

        $data = [
            'idunidades' => $unidad,
            'tramo_km' => $distancia,
            'destino_origen' => $origen,
            'destino_destino' => $destino
        ];
        try {
            $gastosviaje = new GastosViajeModel();
            $gastosviaje->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Gasto de viaje registrado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar el gasto de viaje: ' . $e->getMessage()]);
        }
    }
}
