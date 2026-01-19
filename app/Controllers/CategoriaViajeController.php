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
}