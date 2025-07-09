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
}

?>