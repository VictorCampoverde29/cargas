<?php 

namespace App\Models;

use CodeIgniter\Model;

class ConductorModel extends Model
{
    protected $table      = 'conductor';
    protected $primaryKey = 'idconductor';
    protected $allowedFields = ['idconductor', 'apellidos', 'nombres', 'tipo_doc', 'nro_doc', 'nro_licencia', 'estado'];

    public function getConductoresViaje()
    {
        return $this->select("idconductor, CONCAT(apellidos, ' ', nombres) AS nombrecompleto, tipo_doc, nro_doc, nro_licencia, estado")
            ->orderBy('idconductor', 'ASC')
            ->where('idconductor !=', 0)
            ->where('estado', 'ACTIVO')
            ->findAll();
    }

    public function getDatosXcod($cod)
    {
        return $this->select('idconductor, apellidos, nombres, tipo_doc, nro_doc, nro_licencia, estado')
            ->where('idconductor', $cod)
            ->first();
    }
}
?>