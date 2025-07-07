<?php

namespace App\Controllers;

use App\Models\DestinosModel;
use CodeIgniter\Controller;

class MantenimientoDestinosController extends Controller
{
    public function index()
    {
        return view('mantdestinos/index');
    }

    public function getDestinos()
    {
        $DestinoModel = new DestinosModel();
        $destinos =  $DestinoModel->getDestinos();
        return $this->response->setJSON($destinos);
    }

    public function agregarDestino()
    {
        $model = new DestinosModel();
        $descripcion  = $this->request->getPost('descripcion');
        $estado       = $this->request->getPost('estado');

        $data = [
            'nombre' => $descripcion,
            'estado' => $estado,
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Destino registrado correctamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar el Destino: ' . $e->getMessage()]);
        }
    }

    public function editarDestino(){
        $model = new DestinosModel();
        $iddestino = $this->request->getPost('cod');
        $descripcion = $this->request->getPost('descripcion');
        $estado = $this->request->getPost('estado');
        $data = [
            'nombre' => $descripcion,
            'estado' => $estado,
        ];
        try {
            // Llama al método de actualización
            if ($model->update($iddestino, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Destino actualizado.']);
            } else {
                return $this->response->setJSON(['error' => 'Carga no encontrada.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al actualizar el Destino: ' . $e->getMessage()]);
        }
    }
}


?>