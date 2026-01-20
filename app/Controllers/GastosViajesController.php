<?php

namespace App\Controllers;

use App\Models\CategoriaViajeModel;
use App\Models\CondicionesParametrosGastosViajeModel;
use App\Models\ConsumoCombustibleModel;
use App\Models\GastosViajeModel;
use App\Models\VehiculosModel;
use App\Models\DetalleGastosViajeModel;
use CodeIgniter\Controller;

class GastosViajesController extends Controller
{
    public function index()
    {
        $Unidades = new VehiculosModel();
        $CategoriaViaje = new CategoriaViajeModel();
        $ConsumoCombustible = new ConsumoCombustibleModel();
        $CondicionesParametros = new CondicionesParametrosGastosViajeModel();
        $data['condicion'] = $CondicionesParametros->getCondiciones();
        $data['consumo_combustible'] = $ConsumoCombustible->getDesPrecioKm();
        $data['categoria'] = $CategoriaViaje->getCategoriasActivas();
        $data['unidad'] = $Unidades->getUnidadesGuia();
        return view('gastos_viajes/index', $data);
    }

    public function indexConsultarGastos()
    {
        $Consulta = new GastosViajeModel();
        $data['origen']    = $Consulta->obtenerOrigenes();
        $data['destino']    = $Consulta->obtenerDestinos();
        $data['unidad']    = $Consulta->obtenerUnidades();
        $data['condicion'] = $Consulta->obtenerCondiciones();
        return view('consultar_gastos/index', $data);
    }

    public function obtenerGastosViaje()
    {
        $gastosviaje = new GastosViajeModel();
        $data = $gastosviaje->obtenerGastosViaje();
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerGastosViajePorCodigo()
    {
        $orig = $this->request->getGet('orig');
        $dest = $this->request->getGet('dest');
        $uni = $this->request->getGet('uni');
        $gastosviaje = new GastosViajeModel();
        $data = $gastosviaje->obtenerGastosViajePorCodigo($orig, $dest, $uni);
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerDetalleGastosViaje()
    {
        $cod = $this->request->getGet('cod');
        $gastosviaje = new DetalleGastosViajeModel();
        $data = $gastosviaje->obtenerDetalleGastosViaje($cod);
        return $this->response->setJSON(['data' => $data]);
    }

    public function obtenerPrecioCombustiblePorId()
    {
        $cod = $this->request->getGet('cod');
        $consumocombustible = new ConsumoCombustibleModel();
        $data = $consumocombustible->getPreciosPorId($cod);
        return $this->response->setJSON(['data' => $data]);
    }

    public function insert()
    {
        try {
            $data = [
                'origen'        => $this->request->getPost('origen'),
                'destino'       => $this->request->getPost('destino'),
                'unidad'        => $this->request->getPost('unidad'),
                'condicion'     => $this->request->getPost('condicion'),
                'tramo_km'      => $this->request->getPost('tramo_km'),
                'carreta'       => $this->request->getPost('carreta'),
                'precio_galon'  => $this->request->getPost('precio_galon'),
                'cant_galones'  => $this->request->getPost('cant_galones'),
                'viaticos'      => $this->request->getPost('viaticos'),
                'dias'          => $this->request->getPost('dias'),
                'peajes'        => $this->request->getPost('peajes'),
            ];

            $xml = new \XMLWriter();
            $xml->openMemory();
            $xml->setIndent(true);
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElement('GastosViaje');
            foreach ($data as $key => $value) {
                $xml->writeElement($key, (string)$value);
            }
            $xml->endElement();
            $xml->endDocument();

            $gastosModel = new GastosViajeModel();
            $mensaje = $gastosModel->registrarGastosViaje($xml->outputMemory());

            return $this->response->setJSON([
                'success' => strpos($mensaje, 'ERROR') === false,
                'message' => $mensaje
            ]);
        } catch (\Exception $e) {

            log_message('error', 'Error al registrar gastos de viaje: ' . $e->getMessage());

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
