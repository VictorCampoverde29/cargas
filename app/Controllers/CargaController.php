<?php 

namespace App\Controllers;
use App\Models\CargaModel;
use CodeIgniter\Controller;

class CargaController extends Controller
{
    public function index()
    {
        $Carga = new CargaModel();
        $data['carga'] = $Carga->traerCarga();
        return view('mant_carga/index', $data);
    }

    public function selectCargas()
    {
        $cargas = new CargaModel();
        $data = $cargas->traerCarga();
        return $this->response->setJSON(['data' => $data]);
    }

    public function traerCarga(){
        $model = new CargaModel();
        $data = $model->traerCarga();
        return $this->response->setJSON($data);
    }

    public function agregarCarga()
    {
        $model = new CargaModel();
        $descripcion  = $this->request->getPost('descripcion');
        $estado       = $this->request->getPost('estado');

        if ($model->exists($descripcion)) {
            return $this->response->setJSON(['error' => 'La carga ingresada ya existe.']);
        }

        $data = [
            'descripcion' => $descripcion,
            'estado' => $estado,
        ];

        try {
            $model->insert($data);
            $idInsertado = $model->getInsertID(); // <-- Este es el ID que necesitas
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Carga registrada correctamente.',
                'idcarga' => $idInsertado
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar la carga: ' . $e->getMessage()]);
        }
    }

    public function editarCarga(){
        $model = new CargaModel();
        $idcarga = $this->request->getPost('cod');
        $descripcion = $this->request->getPost('descripcion');
        $estado = $this->request->getPost('estado');

        if ($model->exists($descripcion, $idcarga)) {
            return $this->response->setJSON(['error' => 'La carga ingresada ya existe']);
        }

        $data = [
            'descripcion' => $descripcion,
            'estado' => $estado,
        ];
        try {
            // Llama al método de actualización
            if ($model->update($idcarga, $data)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Carga actualizada.']);
            } else {
                return $this->response->setJSON(['error' => 'Carga no encontrada.']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al actualizar la Carga: ' . $e->getMessage()]);
        }
    }

    public function getCargaXcod()
    {
        $cargasX = new CargaModel();
        $cod = $this->request->getGet('cod');
        $data = $cargasX->getCargaXcod($cod);
        return $this->response->setJSON([$data]);
    }
}


?>