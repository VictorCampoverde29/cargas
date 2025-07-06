<?php

namespace App\Models;

use CodeIgniter\Model;

class CargaModel extends Model
{
    protected $table      = 'carga';
    protected $primaryKey = 'idcarga';
    protected $allowedFields = ['descripcion', 'estado', 'idtipo_carga'];

    public function traerCarga()
    {
        return $this->select('carga.idcarga, carga.descripcion, carga.estado, tipo_carga.tipo as tipo_carga_nombre')
            ->join('tipo_carga', 'tipo_carga.idtipo_carga = carga.idtipo_carga')
            ->orderBy('carga.descripcion', 'ASC')
            ->findAll();
    }
}
