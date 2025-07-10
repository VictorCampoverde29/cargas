<?php 

namespace App\Models;

use CodeIgniter\Model;

class ViajesModel extends Model
{
    protected $table      = 'viaje';
    protected $primaryKey = 'idviaje';

    protected $allowedFields = ['idconductor', 'idunidades', 'fecha_inicio', 'fecha_fin', 'observaciones', 'destinos_origen', 'destinos_destino', 'estado'];

    public function traerViajeReg(){
        return $this->select("
            viaje.idviaje,
            viaje.fecha_inicio,
            viaje.fecha_fin,
            viaje.observaciones,
            viaje.estado,
            CONCAT(conductor.apellidos, ' ', conductor.nombres) AS conductor,
            unidades.descripcion AS unidad,
            destino_origen.nombre AS dest_origen,
            destino_destino.nombre AS dest_llegada
        ")
        ->join('conductor', 'conductor.idconductor = viaje.idconductor')
        ->join('unidades', 'unidades.idunidades = viaje.idunidades')
        ->join('destinos AS destino_origen', 'destino_origen.iddestino = viaje.destinos_origen')
        ->join('destinos AS destino_destino', 'destino_destino.iddestino = viaje.destinos_destino')
        ->orderBy('viaje.fecha_inicio', 'DESC')
        ->findAll();
    }
    
}

?>