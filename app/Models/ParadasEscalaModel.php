<?php

namespace App\Models;

use CodeIgniter\Model;

class ParadasEscalaModel extends Model
{
    protected $table      = 'paradas_escala';
    protected $primaryKey = 'idparadas_escala';
    protected $allowedFields = ['idparadas_escala', 'idviajes_conductor', 'km_inicial', 'km_final', 'estado', 'cod_telefono', 'sincronizado', 'observacion'];

    public function traerParadasPorViajeConductor($idviajes_conductor)
    {
        return $this->where('idviajes_conductor', $idviajes_conductor)
            ->orderBy('km_inicial', 'ASC')
            ->findAll();
    }
}
