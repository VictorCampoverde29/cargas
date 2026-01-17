<?php

namespace App\Models;

use CodeIgniter\Model;

class CondicionesParametrosGastosViajeModel extends Model
{
    protected $table      = 'condiciones_parametros_gastoviaje';
    protected $primaryKey = 'idcondiciones_parametros_gastoviaje';

    protected $allowedFields = ['descripcion', 'estado'];

    public function getCondiciones(){
        return $this->select('idcondiciones_parametros_gastoviaje, descripcion, estado')
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }
}