<?php

namespace App\Controllers;

use App\Models\ConductorModel;
use App\Models\DestinosModel;
use App\Models\SucursalModel;
use App\Models\VehiculosModel;
use App\Models\ViajesModel;
use CodeIgniter\Controller;

class ViajesController extends Controller
{
    public function index()
    {
        $Destinos = new DestinosModel();
        $Conductores = new ConductorModel();
        $Vehiculos = new VehiculosModel();
        $Sucursal = new SucursalModel();
        $data['destino'] = $Destinos->selectDestinos();
        $data['conductor'] = $Conductores->getConductoresViaje();
        $data['vehiculo'] = $Vehiculos->getUnidadesGuia();
        $data['sucursal'] = $Sucursal->traerSucursales();
        return view('mant_viajes/index', $data);
    }
    public function traerViajes()
    {
        $model = new ViajesModel();
        $estado = $this->request->getGet('estado');
        $fecha_inicio = $this->request->getGet('fecha_inicio');
        $fecha_fin = $this->request->getGet('fecha_fin');
        $data = $model->traerViajeReg($estado, $fecha_inicio, $fecha_fin);
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

        if ($model->exists($idconductor, $idunidad, $fecha_inicio, $fecha_fin, $observaciones, $destiorigen, $destillegada)) {
            return $this->response->setJSON(['error' => 'Este viaje ya fue ingresado con los mismos datos.']);
        }

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
            $idViaje = $model->insert($data);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Viaje registrado correctamente.',
                'idviaje' => $idViaje
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar el viaje: ' . $e->getMessage()]);
        }
    }

    public function editarViaje()
    {
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

    public function ultimosViajesDash()
    {
        $model = new ViajesModel();
        $data = $model->getUltimosViajes(5);
        return $this->response->setJSON(['data' => $data]);
    }

    public function estadisticasViajesDash()
    {
        $model = new ViajesModel();
        $data = $model->getEstadisticasViajes();
        return $this->response->setJSON(['data' => $data]);
    }

    public function delete()
    {
        $idviaje = $this->request->getPost('idviaje');

        try {
            // Generar XML mínimo
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElement('eliminacion');
            $xml->writeElement('idviaje', $idviaje);
            $xml->endElement();
            $xml->endDocument();
            $xmlString = $xml->outputMemory();

            $viajeModel = new ViajesModel();
            $resultado = $viajeModel->eliminarViaje($idviaje, $xmlString);

            return $this->response->setJSON([
                'message' => $resultado
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'ERROR: ' . $e->getMessage()
            ]);
        }
    }
}
