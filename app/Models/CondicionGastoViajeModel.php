<?php

namespace App\Models;

use CodeIgniter\Model;

class CondicionGastoViajeModel extends Model
{
    protected $table      = 'condicion_gastoviaje';
    protected $primaryKey = 'idcondicion_gastoviaje';

    protected $allowedFields = ['descripcion', 'estado'];

    public function getCondiciones()
    {
        return $this->findAll();
    }

    public function cmbCondiciones()
    {
        return $this->select('idcondicion_gastoviaje, descripcion')
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    public function getCondicionesXcod($idcondicion_gastoviaje)
    {
        return $this->where('idcondicion_gastoviaje', $idcondicion_gastoviaje)
                    ->first();
    }
}