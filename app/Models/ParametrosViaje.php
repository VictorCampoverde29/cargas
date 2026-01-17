<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametrosViaje extends Model
{
  protected $table      = 'parametros_viaje';
  protected $primaryKey = 'idparametros_viaje';
  protected $allowedFields = [
    'idparametros_viaje',
    'destino_origen',
    'destino_destino',
    'idunidades',
    'idcondiciones_parametros_gastoviaje',
    'carreta',
    'galones',
    'peajes',
    'estado'
  ];

  public function obtenerParametrosViaje($idunidades = null, $destino_origen = null, $destino_destino = null, $idcondiciones_parametros_gastoviaje = null, $carreta = null)
  {
    return $this->select('galones, peajes')
                ->where('destino_origen', $destino_origen)
                ->where('destino_destino', $destino_destino)
                ->where('idunidades', $idunidades)
                ->where('idcondiciones_parametros_gastoviaje', $idcondiciones_parametros_gastoviaje)
                ->where('carreta', $carreta)
                ->first();
  }
}
