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
    'idcondicion',
    'carreta',
    'galones',
    'peajes',
    'estado'
  ];

  public function obtenerParametrosViaje($idunidades = null, $destino_origen = null, $destino_destino = null, $idcondicion = null, $carreta = null)
  {
    return $this->select('galones, peajes')
                ->where('destino_origen', $destino_origen)
                ->where('destino_destino', $destino_destino)
                ->where('idunidades', $idunidades)
                ->where('idcondicion', $idcondicion)
                ->where('carreta', $carreta)
                ->first();
  }

  public function getParametros()
  {
    return $this->select('parametros_viaje.idparametros_viaje, CONCAT(des.nombre, " - ", des2.nombre) as viaje, uni.descripcion as unidad, condi.descripcion as condicion, parametros_viaje.carreta, parametros_viaje.galones, parametros_viaje.peajes, parametros_viaje.estado')
                ->join('destinos des', 'des.iddestino = parametros_viaje.destino_origen')
                ->join('destinos des2', 'des2.iddestino = parametros_viaje.destino_destino')
                ->join('unidades uni', 'uni.idunidades = parametros_viaje.idunidades')
                ->join('condicion_gastoviaje condi', 'condi.idcondicion_gastoviaje = parametros_viaje.idcondicion')
                ->findAll();
  }
  public function getParametrosXcod($idparametros_viaje)
  {
    return $this->select('parametros_viaje.idparametros_viaje, des.iddestino as destino_origen, des2.iddestino as destino_destino, uni.idunidades as unidad, condi.idcondicion_gastoviaje as condicion, parametros_viaje.carreta, parametros_viaje.galones, parametros_viaje.peajes, parametros_viaje.estado')
                ->join('destinos des', 'des.iddestino = parametros_viaje.destino_origen')
                ->join('destinos des2', 'des2.iddestino = parametros_viaje.destino_destino')
                ->join('unidades uni', 'uni.idunidades = parametros_viaje.idunidades')
                ->join('condicion_gastoviaje condi', 'condi.idcondicion_gastoviaje = parametros_viaje.idcondicion')
                ->where('parametros_viaje.idparametros_viaje', $idparametros_viaje)
                ->first();
  }
}
