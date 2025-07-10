<?php

namespace App\Models;

use CodeIgniter\Model;

class CargaModel extends Model
{
    protected $table      = 'carga';
    protected $primaryKey = 'idcarga';
    protected $allowedFields = ['descripcion', 'estado'];

    public function traerCarga()
    {
        return $this->select('idcarga, descripcion, estado')
                    ->orderBy('descripcion', 'ASC')
                    ->findAll();
    }
}
