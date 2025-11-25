<?php

namespace App\Models;

use CodeIgniter\Model;

class GastosModel extends Model
{
    protected $table      = 'gastos';
    protected $primaryKey = 'idgastos';
    protected $allowedFields = ['idgastos', 'idviaje', 'factura', 'fecha', 'importe'];

    public function traerGastosPorViaje($idviaje)
    {
        return $this->where('idviaje', $idviaje)
            ->orderBy('fecha', 'ASC')
            ->findAll();
    }
}
