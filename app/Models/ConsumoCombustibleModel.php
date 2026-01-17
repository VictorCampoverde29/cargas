<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsumoCombustibleModel extends Model
{
    protected $table      = 'consumo_combustible';
    protected $primaryKey = 'idconsumo_combustible';

    protected $allowedFields = ['idunidades_medida', 'descripcion', 'precio_km', 'precio_combustible'];

    public function getDesPrecioKm(){
        return $this->select('idconsumo_combustible, descripcion')
                    ->findAll();

    }

    public function getPreciosPorId($cod){
        return $this->select('precio_km, precio_combustible')
                    ->where('idconsumo_combustible', $cod)
                    ->first();
    }
}