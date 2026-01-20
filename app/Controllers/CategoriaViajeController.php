<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\CategoriaViajeModel;

class CategoriaViajeController extends Controller
{
    public function getCategorias()
    {
        $categoriaModel = new CategoriaViajeModel();
        $categorias = $categoriaModel->getCategorias();
        return $this->response->setJSON($categorias);
    }

    public function insert()
    {
        $categoriaModel = new CategoriaViajeModel();
        $data = [
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => $this->request->getPost('estado')
        ];
        try {
            $categoriaModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Categoría registrada exitosamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar la categoría: ' . $e->getMessage()]);
        }
    }
}