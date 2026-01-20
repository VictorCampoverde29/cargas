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

    public function getCategoriasXcod()
    {
        $idcategoria_viajes = $this->request->getPost('idcategoria_viajes');
        $categoriaModel = new CategoriaViajeModel();
        $categoria = $categoriaModel->getCategoriasXcod($idcategoria_viajes);
        return $this->response->setJSON($categoria);
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

    public function update()
    {
        $categoriaModel = new CategoriaViajeModel();
        $id = $this->request->getPost('idcategoria_viajes');
        $data = [
            'descripcion' => $this->request->getPost('descripcion'),
            'estado' => $this->request->getPost('estado')
        ];
        try {
            $categoriaModel->update($id, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Categoría actualizada exitosamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al actualizar la categoría: ' . $e->getMessage()]);
        }
    }
}