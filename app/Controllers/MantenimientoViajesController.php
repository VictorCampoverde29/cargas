<?php

namespace App\Controllers;

use App\Models\CargaModel;
use App\Models\ConductorModel;
use App\Models\DestinosModel;
use App\Models\SucursalModel;
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
        $Sucursal = new SucursalModel();
        $Tipo = new CargaModel();
        $data['destino'] = $Destinos->getDestinos();
        $data['conductor'] = $Conductores->getConductoresViaje();
        $data['vehiculo'] = $Vehiculos->getUnidadesGuia();
        $data['sucursal'] = $Sucursal->traerSucursales();
        $data['tipo']= $Tipo->traerCarga();
        return view('mantviajes/index', $data);
    }
    public function traerViajes(){
        $model = new ViajesModel();
        $data = $model->traerViajeReg();
        return $this->response->setJSON($data);
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

    public function editarViaje(){
        $model = new ViajesModel();
        $idviaje = $this->request->getPost('cod');
        $estado = $this->request->getPost('estado');
        $data = [
            'estado' => $estado
        ];
        try {
            // Llama al método de actualización
            if ($model->update($idviaje, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Viaje actualizado.']);
            } else {
                return $this->response->setJSON(['error' => 'Viaje no encontrado.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al actualizar el Viaje: ' . $e->getMessage()]);
        }
    }
}

?>