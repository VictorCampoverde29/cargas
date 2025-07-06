<?php 

namespace App\Controllers;

use App\Models\CargaModel;
use CodeIgniter\Controller;
use App\Models\TipoCargaModel;
class MantenimientoCargaController extends Controller
{
    public function index()
    {
        $TipoCarga = new TipoCargaModel();
        $data['tipo'] = $TipoCarga->traerTipoCarga();
        return view('mantcarga/index', $data);
    }

    public function traerCarga(){
        $model = new CargaModel();
        $data = $model->traerCarga();
        return $this->response->setJSON($data);
    }

    public function agregarCarga()
    {
        $model = new CargaModel();

        $tipo_doc  = $this->request->getPost('tipo_doc');
        $descripcion  = $this->request->getPost('descripcion');
        $estado       = $this->request->getPost('estado');

        $data = [
            'descripcion' => $descripcion,
            'estado' => $estado,
            'idtipo_carga' => $tipo_doc
        ];

        try {
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Carga registrada correctamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => 'Ocurrió un error al registrar la carga: ' . $e->getMessage()]);
        }
    }

    public function editarCarga(){
        $model = new CargaModel();
        $idcarga = $this->request->getPost('cod');
        $descripcion = $this->request->getPost('descripcion');
        $estado = $this->request->getPost('estado');
        $tipo_carga = $this->request->getPost('tipo_carga');
        $data = [
            'descripcion' => $descripcion,
            'estado' => $estado,
            'idtipo_carga' => $tipo_carga
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
}


?>