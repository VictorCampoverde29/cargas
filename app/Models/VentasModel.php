<?php

namespace App\Models;
use CodeIgniter\Model;

class VentasModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'idventas';
    protected $allowedFields = ['numero_doc', 'fecha_emision', 'importe_total', 'estado', 'idalmacen'];

    public function verificarVentaPorGuia($numeroGuia)
    {
        return $this->select('ventas.idventas, ventas.numero_doc, ventas.fecha_emision, ventas.importe_total, ventas.estado, ventas.idalmacen, almacen.descripcion as almacen_nombre')
            ->join('almacen', 'almacen.idalmacen = ventas.idalmacen', 'left')
            ->where('ventas.numero_doc', $numeroGuia)
            ->whereIn('ventas.idalmacen', [20, 39, 40]) // Solo almacenes de FOX
            ->findAll();
    }
}
