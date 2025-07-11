<?php
namespace App\Controllers;
use App\Models\DestinosModel;
use CodeIgniter\Controller;

class DestinosController extends Controller
{
    public function index()
    {
        return view('mant_destino/index');
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

        if ($model->exists($descripcion)) {
            return $this->response->setJSON(['error' => 'El destino ingresado ya existe.']);
        }

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
        $nombre = $this->request->getPost('nombre');
        $estado = $this->request->getPost('estado');

        if ($model->exists($nombre, $iddestino)) {
            return $this->response->setJSON(['error' => 'El destino ingresado ya existe']);
        }

        $data = [
            'nombre' => $nombre,
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

    public function getDestinosXcod()
    {
        $destinosX = new DestinosModel();
        $cod = $this->request->getGet('cod');
        $data = $destinosX->getDestinosXcod($cod);
        return $this->response->setJSON([$data]);
    }
}


?>