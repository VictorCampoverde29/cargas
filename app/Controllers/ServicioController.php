<?php

namespace App\Controllers;

use App\Models\ServicioModel;
use CodeIgniter\Controller;

class ServicioController extends Controller
{
    public function traerServiciosXCod()
    {
        $servicios = new ServicioModel();
        $cod = $this->request->getGet('cod');
        $data = $servicios->traerServiciosXCod($cod);
        return $this->response->setJSON($data);
    }

    public function registrarServicio()
    {
        $model = new ServicioModel();

        $idviaje = $this->request->getPost('idviaje');
        $idcarga  = $this->request->getPost('idcarga');
        $nguia  = $this->request->getPost('nguia');
        $fecha_servicio  = $this->request->getPost('f_servicio');
        $origen  = $this->request->getPost('origen');
        $destino  = $this->request->getPost('destino');
        $flete  = $this->request->getPost('flete');
        $emisor  = $this->request->getPost('emisor');
        $receptor  = $this->request->getPost('receptor');
        $glosa  = $this->request->getPost('glosa');
        $estado  = $this->request->getPost('estado');

        if ($model->exists($nguia)) {
            return $this->response->setJSON(['error' => 'El numero de guia ya fue registrado.']);
        }

        $data = [
            'idviaje' => $idviaje,
            'idcarga' => $idcarga,
            'n_guia' => $nguia,
            'fecha_servicio' => $fecha_servicio,
            'origen' => $origen,
            'destino' => $destino,
            'flete' => $flete,
            'emisor' => $emisor,
            'receptor' => $receptor,
            'glosa' => $glosa,
            'estado' => $estado
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Servicio registrado correctamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar el servicio: ' . $e->getMessage()]);
        }
    }

    public function validarServiciosViaje()
    {
        $servicioModel = new ServicioModel();
        $idviaje = $this->request->getPost('idviaje');

        try {
            // Obtener todos los servicios del viaje
            $servicios = $servicioModel->traerServiciosXCod($idviaje);

            // Verificar si hay servicios
            if (empty($servicios)) {
                return $this->response->setJSON([
                    'success' => true,
                    'puede_entregar' => true,
                    'message' => 'No hay servicios asociados al viaje.'
                ]);
            }

            // Verificar servicios pendientes (no entregados)
            $serviciosPendientes = [];
            foreach ($servicios as $servicio) {
                $estado = trim(strtoupper($servicio['estado']));
                if ($estado !== 'ENTREGADO') {
                    $serviciosPendientes[] = [
                        'n_guia' => $servicio['n_guia'],
                        'origen' => $servicio['origen'],
                        'destino' => $servicio['destino'],
                        'estado' => $servicio['estado']
                    ];
                }
            }

            if (!empty($serviciosPendientes)) {
                return $this->response->setJSON([
                    'success' => true,
                    'puede_entregar' => false,
                    'servicios_pendientes' => $serviciosPendientes,
                    'message' => 'Existen servicios pendientes de entrega.'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'puede_entregar' => true,
                'message' => 'Todos los servicios están entregados.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => 'Error al validar servicios: ' . $e->getMessage()
            ]);
        }
    }

    public function update()
    {
        $idservicio = $this->request->getPost('cod');
        $estado = $this->request->getPost('estado');

        try {
            // Generar XML
            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->setIndent(true);
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElement('datos');
            $xml->writeElement('idservicio', $idservicio);
            $xml->writeElement('estado', $estado);
            $xml->endElement();
            $xml->endDocument();

            $xmlString = $xml->outputMemory();

            // Llamar al procedimiento almacenado
            $servicioModel = new ServicioModel();
            $resultado = $servicioModel->actualizarEstadoServicio($xmlString);

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
