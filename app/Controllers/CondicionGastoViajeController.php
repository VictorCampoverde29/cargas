<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CondicionGastoViajeModel;

class CondicionGastoViajeController extends Controller
{
    public function index()
    {
        return view('mant_condiciones/index');
    }

    public function getCondiciones()
    {
        $CondicionModel = new CondicionGastoViajeModel();
        $condicion = $CondicionModel->getCondiciones();
        return $this->response->setJSON($condicion);
    }

    public function insert()
    {
        $CondicionModel = new CondicionGastoViajeModel();
        $data =
            [
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado')
            ];
        try {
            $CondicionModel->insert($data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Condición registrada exitosamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar la condición: ' . $e->getMessage()]);
        }
    }

    public function getCondicionesXcod()
    {
        $idcondicion_gastoviaje = $this->request->getPost('idcondicion_gastoviaje');
        $CondicionModel = new CondicionGastoViajeModel();
        $condicion = $CondicionModel->getCondicionesXcod($idcondicion_gastoviaje);
        return $this->response->setJSON($condicion);
    }

    public function update()
    {
        $idcondicion_gastoviaje = $this->request->getPost('idcondicion_gastoviaje');
        $data =
            [
                'descripcion' => $this->request->getPost('descripcion'),
                'estado' => $this->request->getPost('estado')
            ];
        $CondicionModel = new CondicionGastoViajeModel();
        try {
            $CondicionModel->update($idcondicion_gastoviaje, $data);
            return $this->response->setJSON(['status' => 'success', 'message' => 'Condición actualizada exitosamente.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al actualizar la condición: ' . $e->getMessage()]);
        }
    }
}