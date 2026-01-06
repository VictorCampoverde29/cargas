<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleGastosViajeModel extends Model
{
    protected $table      = 'det_gasto_viaje';
    protected $primaryKey = 'iddet_gasto_viaje';

    protected $allowedFields = ['idgastos_viaje', 'idcategoria_viajes', 'descripcion', 'monto', 'cantidad', 'total'];

    public function obtenerDetalleGastosViaje($cod){
        return $this->select('det_gasto_viaje.iddet_gasto_viaje, categoria_viajes.descripcion as categoria, det_gasto_viaje.descripcion, det_gasto_viaje.monto, det_gasto_viaje.cantidad, total')
                    ->join('categoria_viajes', 'categoria_viajes.idcategoria_viajes = det_gasto_viaje.idcategoria_viajes')
                    ->where('idgastos_viaje', $cod)
                    ->findAll();
    }
}