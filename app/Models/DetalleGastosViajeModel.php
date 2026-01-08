<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleGastosViajeModel extends Model
{
    protected $table      = 'det_gastos_viaje';
    protected $primaryKey = 'iddet_gastos_viaje';

    protected $allowedFields = ['idgastos_viaje', 'idcategoria_viajes', 'descripcion', 'monto', 'cantidad', 'total'];

    public function obtenerDetalleGastosViaje($cod){
        return $this->select('det_gastos_viaje.iddet_gastos_viaje, categoria_viajes.descripcion as categoria, det_gastos_viaje.descripcion, det_gastos_viaje.monto, det_gastos_viaje.cantidad, total')
                    ->join('categoria_viajes', 'categoria_viajes.idcategoria_viajes = det_gastos_viaje.idcategoria_viajes')
                    ->where('idgastos_viaje', $cod)
                    ->findAll();
    }
}