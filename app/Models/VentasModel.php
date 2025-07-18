<?php

namespace App\Models;
use CodeIgniter\Model;

class VentasModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'idventas';
    protected $allowedFields = ['numero_doc', 'fecha_emision', 'importe_total', 'estado', 'idalmacen', 'guia_remision', 'importe_igv'];

    public function verificarVentaPorGuia($numeroGuia)
    {
        // Extraer serie y número correlativo exacto
        $patron = '/V(\d{3})-(\d{1,8})$/';
        preg_match($patron, $numeroGuia, $matches);
        $serie = isset($matches[1]) ? $matches[1] : '';
        $correlativo = isset($matches[2]) ? $matches[2] : '';
        if (empty($serie) || empty($correlativo)) {
            return [];
        }
        // 1. Buscar coincidencia exacta V001-00000019 (en lista separada por /)
        $exacto = $this->select('ventas.idventas, ventas.numero_doc, ventas.fecha_emision, ventas.importe_total, ventas.estado, ventas.idalmacen, sucursal.descripcion as sucursal_nombre, ventas.guia_remision, servicio.n_guia as numero_guia, ventas.importe_igv, (COALESCE(ventas.importe_gravado,0)+COALESCE(ventas.importe_exonerado,0)+COALESCE(ventas.importe_gratuito,0)+COALESCE(ventas.importe_inafecto,0)) as subtotal')
            ->join('almacen', 'almacen.idalmacen = ventas.idalmacen', 'left')
            ->join('sucursal', 'sucursal.idsucursal = almacen.idsucursal', 'left')
            ->join('servicio', 'servicio.n_guia = "' . $numeroGuia . '"', 'left')
            ->where('FIND_IN_SET("' . 'V' . $serie . '-' . str_pad($correlativo, 8, '0', STR_PAD_LEFT) . '", REPLACE(REPLACE(ventas.guia_remision, "/", ","), " ", "")) >', 0)
            ->where('sucursal.idempresa', 9)
            ->findAll();
        if (!empty($exacto)) {
            return $exacto;
        }
        // 2. Buscar variantes V001-19, V001-019, V001-000019, etc. (sin perder ceros intermedios)
        $correlativos = [];
        $correlativos[] = ltrim($correlativo, '0'); // sin ceros a la izquierda
        for ($i = 1; $i < strlen($correlativo); $i++) {
            $correlativos[] = substr($correlativo, $i);
        }
        $correlativos = array_unique($correlativos);
        $formatos = [];
        foreach ($correlativos as $corr) {
            if ($corr !== '') {
                $formatos[] = 'V' . $serie . '-' . $corr;
            }
        }
        $todasVariantes = [];
        foreach ($formatos as $formato) {
            $res = $this->select('ventas.idventas, ventas.numero_doc, ventas.fecha_emision, ventas.importe_total, ventas.estado, ventas.idalmacen, sucursal.descripcion as sucursal_nombre, ventas.guia_remision, servicio.n_guia as numero_guia, ventas.importe_igv, (COALESCE(ventas.importe_gravado,0)+COALESCE(ventas.importe_exonerado,0)+COALESCE(ventas.importe_gratuito,0)+COALESCE(ventas.importe_inafecto,0)) as subtotal')
                ->join('almacen', 'almacen.idalmacen = ventas.idalmacen', 'left')
                ->join('sucursal', 'sucursal.idsucursal = almacen.idsucursal', 'left')
                ->join('servicio', 'servicio.n_guia = "' . $numeroGuia . '"', 'left')
                ->where('FIND_IN_SET("' . $formato . '", REPLACE(REPLACE(ventas.guia_remision, "/", ","), " ", "")) >', 0)
                ->where('sucursal.idempresa', 9)
                ->findAll();
            if (!empty($res)) {
                $todasVariantes = array_merge($todasVariantes, $res);
            }
        }
        if (!empty($todasVariantes)) {
            return $todasVariantes;
        }
        // 3. Buscar con patrón flexible (respaldo)
        $patronBusqueda = '(^|/)V' . $serie . '-0*' . ltrim($correlativo, '0') . '($|/)';
        return $this->select('ventas.idventas, ventas.numero_doc, ventas.fecha_emision, ventas.importe_total, ventas.estado, ventas.idalmacen, sucursal.descripcion as sucursal_nombre, ventas.guia_remision, servicio.n_guia as numero_guia, (COALESCE(ventas.importe_gravado,0)+COALESCE(ventas.importe_exonerado,0)+COALESCE(ventas.importe_gratuito,0)+COALESCE(ventas.importe_inafecto,0)) as subtotal')
            ->join('almacen', 'almacen.idalmacen = ventas.idalmacen', 'left')
            ->join('sucursal', 'sucursal.idsucursal = almacen.idsucursal', 'left')
            ->join('servicio', 'servicio.n_guia = "' . $numeroGuia . '"', 'left')
            ->where("ventas.guia_remision REGEXP '" . $patronBusqueda . "'")
            ->where('sucursal.idempresa', 9)
            ->findAll();
    }
}
