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
        // Generar todas las variantes posibles del correlativo (con y sin ceros a la izquierda)
        $variantes = [];
        $len = strlen($correlativo);
        for ($i = 0; $i < $len; $i++) {
            $corr = substr($correlativo, $i);
            if ($corr !== '') {
                $variantes[] = 'V' . $serie . '-' . $corr;
            }
        }
        // Asegurar la variante con todos los ceros a la izquierda
        $variantes[] = 'V' . $serie . '-' . str_pad($correlativo, 8, '0', STR_PAD_LEFT);
        $variantes = array_unique($variantes);

        // Buscar todas las variantes en una sola consulta usando WHERE IN
        $condiciones = [];
        foreach ($variantes as $variante) {
            // Buscar la variante como elemento exacto en la lista separada por /
            $condiciones[] = 'FIND_IN_SET("' . $variante . '", REPLACE(REPLACE(ventas.guia_remision, "/", ","), " ", "")) > 0';
        }
        $whereIn = '(' . implode(' OR ', $condiciones) . ')';

        return $this->select('ventas.idventas, ventas.numero_doc, ventas.fecha_emision, ventas.importe_total, ventas.estado, ventas.idalmacen, sucursal.descripcion as sucursal_nombre, ventas.guia_remision, servicio.n_guia as numero_guia, ventas.importe_igv, (COALESCE(ventas.importe_gravado,0)+COALESCE(ventas.importe_exonerado,0)+COALESCE(ventas.importe_gratuito,0)+COALESCE(ventas.importe_inafecto,0)) as subtotal')
            ->join('almacen', 'almacen.idalmacen = ventas.idalmacen', 'left')
            ->join('sucursal', 'sucursal.idsucursal = almacen.idsucursal', 'left')
            ->join('servicio', 'servicio.n_guia = "' . $numeroGuia . '"', 'left')
            ->where($whereIn)
            ->where('sucursal.idempresa', 9)
            ->findAll();
    }
}
