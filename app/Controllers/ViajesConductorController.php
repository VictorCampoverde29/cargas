<?php

namespace App\Controllers;

use App\Models\ViajesConductorModel;
use App\Models\ParadasEscalaModel;
use App\Models\GastosModel;
use CodeIgniter\Controller;

class ViajesConductorController extends Controller
{
    public function traerViajesConductorVinculados()
    {
        $model = new ViajesConductorModel();
        $idviaje = $this->request->getGet('idviaje');
        
        if (empty($idviaje)) {
            return $this->response->setJSON(['error' => 'ID de viaje requerido']);
        }

        try {
            $data = $model->traerViajesConductorVinculados($idviaje);
            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
        }
    }

    public function traerViajesConductorDisponibles()
    {
        $model = new ViajesConductorModel();
        $viajeModel = new \App\Models\ViajesModel();
        $idviaje = $this->request->getGet('idviaje');
        $fecha_inicio = $this->request->getGet('fecha_inicio');
        $fecha_fin = $this->request->getGet('fecha_fin');
        
        if (empty($idviaje)) {
            return $this->response->setJSON(['error' => 'ID de viaje requerido']);
        }

        try {
            $viaje = $viajeModel->find($idviaje);
            
            if (!$viaje) {
                return $this->response->setJSON(['error' => 'Viaje no encontrado']);
            }

            $idconductor = $viaje['idconductor'];
            $idunidades = $viaje['idunidades'];

            $data = $model->traerViajesConductorDisponibles($idconductor, $idunidades, $fecha_inicio, $fecha_fin);
            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
        }
    }

    public function vincularConViaje()
    {
        $model = new ViajesConductorModel();
        $idsViajesConductor = $this->request->getPost('ids_viajes_conductor');
        $idviaje = $this->request->getPost('idviaje');
        
        if (empty($idsViajesConductor) || !is_array($idsViajesConductor)) {
            return $this->response->setJSON(['success' => false, 'mensaje' => 'Debe seleccionar al menos un registro']);
        }

        if (empty($idviaje)) {
            return $this->response->setJSON(['success' => false, 'mensaje' => 'ID de viaje requerido']);
        }

        try {
            $resultado = $model->vincularConViaje($idsViajesConductor, $idviaje);
            
            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true, 
                    'mensaje' => 'Registros vinculados correctamente con el viaje'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'mensaje' => 'No se pudieron vincular los registros'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false, 
                'mensaje' => 'Error al vincular: ' . $e->getMessage()
            ]);
        }
    }

    public function traerParadasYGastos()
    {
        $paradasModel = new ParadasEscalaModel();
        $gastosModel = new GastosModel();
        $viajesConductorModel = new ViajesConductorModel();
        $idviajes_conductor = $this->request->getGet('idviajes_conductor');
        
        if (empty($idviajes_conductor)) {
            return $this->response->setJSON(['error' => 'ID de viaje conductor requerido']);
        }

        try {
            $viajeConductor = $viajesConductorModel->find($idviajes_conductor);
            
            if (!$viajeConductor || empty($viajeConductor['idviaje'])) {
                return $this->response->setJSON(['error' => 'No se encontró el viaje asociado']);
            }
            
            $idviaje = $viajeConductor['idviaje'];
            
            $paradas = $paradasModel->traerParadasPorViajeConductor($idviajes_conductor);
            $gastos = $gastosModel->traerGastosPorViaje($idviaje);
            
            return $this->response->setJSON([
                'data' => $paradas,
                'gastos' => $gastos
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Error al obtener los datos: ' . $e->getMessage()]);
        }
    }
}
