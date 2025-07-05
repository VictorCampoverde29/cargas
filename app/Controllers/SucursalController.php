<?php

namespace App\Controllers;

use App\Models\SucursalModel;
use CodeIgniter\Controller;
class SucursalController extends Controller
{
    public function get_sucursal_activas()
    {
        $sucModel=new SucursalModel();
        $codEmpresa=$this->request->getPost('cod');
        $data=$sucModel->get_sucursal_activas($codEmpresa);
        return $this->response->setJSON(['sucursales'=>$data]);
    }

    public function getSucursales()
    {
        $sucursales = new SucursalModel();
        $cod = $this->request->getGet('cod');
        $data =  $sucursales->getSucursales($cod);
        return $this->response->setJSON(['data' => $data]);
    }
    public function sucursalXcod()
    {
        $sucursalesx = new SucursalModel();
        $cods = $this->request->getGet('cods');
        $data = $sucursalesx->sucursalXcod($cods);
        return $this->response->setJSON([$data]);
    }
}