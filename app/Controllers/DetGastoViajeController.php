<?php
namespace App\Controllers;
use App\Models\DetalleGastosViajeModel;
use CodeIgniter\Controller;

class DetGastoViajeController extends Controller
{
    public function registrarGastoViaje()
    {
        $idgastos_viaje = $this->request->getPost('idgastos_viaje');
        $categoria = $this->request->getPost('idcategorias_viajes');
        $descripcion = $this->request->getPost('descripcion');
        $monto = $this->request->getPost('monto');
        $cantidad = $this->request->getPost('cantidad');
        $total = $this->request->getPost('total');

        $data = [
            'idgastos_viaje' => $idgastos_viaje,
            'idcategoria_viajes' => $categoria,
            'descripcion' => $descripcion,
            'monto' => $monto,
            'cantidad' => $cantidad,
            'total' => $total
        ];
        try {
            $dtgastoviaje = new DetalleGastosViajeModel();
            $dtgastoviaje->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Gasto registrado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar el gasto: ' . $e->getMessage()]);
        }
    }
    public function deleteGastoViaje()
    {
        $id = $this->request->getPost('iddet_gastos_viaje');
        try {
            $dtgastoviaje = new DetalleGastosViajeModel();
            $dtgastoviaje->delete($id);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Gasto eliminado correctamente']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al eliminar el gasto: ' . $e->getMessage()]);
        }
    }
}