<?php
Namespace App\Controllers;

use App\Models\CondicionGastoViajeModel;
use App\Models\DestinosModel;
use CodeIgniter\Controller;
use App\Models\ParametrosViaje;
use App\Models\VehiculosModel;

class ParametrosViajeController extends Controller
{
    public function index()
    {
        $destinos = new DestinosModel();
        $unidades = new VehiculosModel();
        $condiciones = new CondicionGastoViajeModel();
        $data['destinos'] = $destinos->getDestinos();
        $data['vehiculos'] = $unidades->getUnidadesGuia();
        $data['condiciones'] = $condiciones->cmbCondiciones();
        return view('mant_parametros/index', $data);
    }

    public function getParametros()
    {
        $parametrosViajeModel = new ParametrosViaje();
        $parametros = $parametrosViajeModel->getParametros();
        return $this->response->setJSON($parametros);
    }
    public function getParametrosXcod()
    {
        $idparametros_viaje = $this->request->getPost('idparametros_viaje');
        $parametrosViajeModel = new ParametrosViaje();
        $parametros = $parametrosViajeModel->getParametrosXcod($idparametros_viaje);
        return $this->response->setJSON($parametros);
    }

    public function obtenerParametrosViaje()
    {
        $idunidades = $this->request->getPost('unidad');
        $destino_origen = $this->request->getPost('origen');
        $destino_destino = $this->request->getPost('destino');
        $idcondicion = $this->request->getPost('condicion');
        $carreta = $this->request->getPost('carreta');

        $parametrosViajeModel = new ParametrosViaje();
        $parametros = $parametrosViajeModel->obtenerParametrosViaje(
            $idunidades,
            $destino_origen,
            $destino_destino,
            $idcondicion,
            $carreta
        );

        return $this->response->setJSON($parametros);
    }
    public function insert()
    {
        $parametrosViajeModel = new ParametrosViaje();

        $data = [
            'destino_origen' => $this->request->getPost('origen'),
            'destino_destino' => $this->request->getPost('destino'),
            'idunidades' => $this->request->getPost('unidad'),
            'idcondicion' => $this->request->getPost('condicion'),
            'carreta' => $this->request->getPost('carreta'),
            'galones' => $this->request->getPost('galones'),
            'peajes' => $this->request->getPost('peajes'),
            'estado' => $this->request->getPost('estado')
        ];

        try {
            $parametrosViajeModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Parámetro de viaje registrado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar el parámetro de viaje: ' . $e->getMessage()]);
        }
    }
    public function update()
    {
        $parametrosViajeModel = new ParametrosViaje();

        $idparametros_viaje = $this->request->getPost('idparametros_viaje');

        $data = [
            'destino_origen' => $this->request->getPost('origen'),
            'destino_destino' => $this->request->getPost('destino'),
            'idunidades' => $this->request->getPost('unidad'),
            'idcondicion' => $this->request->getPost('condicion'),
            'carreta' => $this->request->getPost('carreta'),
            'galones' => $this->request->getPost('galones'),
            'peajes' => $this->request->getPost('peajes'),
            'estado' => $this->request->getPost('estado')
        ];

        try {
            $parametrosViajeModel->update($idparametros_viaje, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Parámetro de viaje actualizado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al actualizar el parámetro de viaje: ' . $e->getMessage()]);
        }
    }

}