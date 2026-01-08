<?php

namespace App\Models;

use CodeIgniter\Model;

class GastosViajeModel extends Model
{
    protected $table      = 'gastos_viaje';
    protected $primaryKey = 'idgastos_viaje';

    protected $allowedFields = ['destino_origen', 'destino_destino', 'idunidades', 'tramo_km'];

    public function obtenerGastosViaje(){
        return $this->select('gastos_viaje.idgastos_viaje, gastos_viaje.tramo_km, CONCAT(des.nombre, " - ", des2.nombre) as viaje, uni.descripcion as unidad')
            ->join('destinos des', 'des.iddestino = gastos_viaje.destino_origen')
            ->join('destinos des2', 'des2.iddestino = gastos_viaje.destino_destino')
            ->join('unidades uni', 'uni.idunidades = gastos_viaje.idunidades')
            ->findAll();
    }

    public function obtenerGastosViajePorCodigo($orig, $dest, $uni){
        return $this->select('gastos_viaje.idgastos_viaje, gastos_viaje.tramo_km, CONCAT(des.nombre, " - ", des2.nombre) as viaje, CONCAT(conduc.apellidos, " ", conduc.nombres) as conductor, uni.descripcion as unidad')
            ->join('destinos des', 'des.iddestino = gastos_viaje.destino_origen')
            ->join('destinos des2', 'des2.iddestino = gastos_viaje.destino_destino')
            ->join('conductor conduc', 'conduc.idconductor = gastos_viaje.idconductor')
            ->join('unidades uni', 'uni.idunidades = gastos_viaje.idunidades')
            ->where('gastos_viaje.destino_origen', $orig)
            ->where('gastos_viaje.destino_destino', $dest)
            ->where('gastos_viaje.idunidades', $uni)
            ->first();
    }
}