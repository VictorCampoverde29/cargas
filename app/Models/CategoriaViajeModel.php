<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaViajeModel extends Model
{
    protected $table      = 'categoria_viajes';
    protected $primaryKey = 'idcategoria_viajes';

    protected $allowedFields = ['descripcion', 'estado'];

    public function getCategoriasActivas(){
        return $this->select('idcategoria_viajes, descripcion')
                    ->where('estado', 'ACTIVO')
                    ->findAll();
    }

    public function getCategorias()
    {
        return $this->findAll();
    }
}