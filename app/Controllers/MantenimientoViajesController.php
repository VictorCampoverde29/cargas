<?php

namespace App\Controllers;

use App\Models\ConductorModel;
use App\Models\DestinosModel;
use App\Models\VehiculosModel;
use App\Models\ViajesModel;
use CodeIgniter\Controller;

class MantenimientoViajesController extends Controller
{
    public function index()
    {
        $Destinos = new DestinosModel();
        $Conductores = new ConductorModel();
        $Vehiculos = new VehiculosModel();
        $data['departamento'] = $Destinos->getDepartamentos();
        $data['conductor'] = $Conductores->getConductoresViaje();
        $data['vehiculo'] = $Vehiculos->getUnidadesGuia();
        return view('mantviajes/index', $data);
    }

    public function registrarViaje()
    {
        $model = new ViajesModel();

        $idconductor  = $this->request->getPost('idconductor');
        $idunidad  = $this->request->getPost('idunidad');
        $fecha_inicio  = $this->request->getPost('f_inicio');
        $fecha_fin  = $this->request->getPost('f_fin');
        $observaciones  = $this->request->getPost('observaciones');
        $destiorigen  = $this->request->getPost('destorigen');
        $destillegada  = $this->request->getPost('destllegada');

        $data = [
            'idconductor' => $idconductor,
            'idunidades' => $idunidad,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'observaciones' => $observaciones,
            'destinos_origen' => $destiorigen,
            'destinos_destino' => $destillegada
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Viaje registrada correctamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar el viaje: ' . $e->getMessage()]);
        }
    }

}

?>