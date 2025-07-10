<?php 

namespace App\Models;

use CodeIgniter\Model;

class VehiculosModel extends Model
{
    protected $table      = 'unidades';
    protected $primaryKey = 'idunidades';

    protected $allowedFields = ['descripcion', 'marca', 'modelo', 'año_de_unidad', 'placa', 'estado', 'tonelaje', 'cert_inscrip', 'cvehicular'];

    public function getUnidadesGuia()
    {
        return $this->select('idunidades, descripcion, marca, modelo, año_de_unidad, placa, estado, tonelaje, cert_inscrip, cvehicular')
            ->orderBy('idunidades', 'ASC')
            ->where('estado', 'ACT')
            ->findAll();
    }

    public function getDatosXcod($cod)
    {
        return $this->select('idunidades, descripcion, marca, modelo, año_de_unidad, placa, estado, tonelaje, cert_inscrip, cvehicular')
            ->where('idunidades', $cod)
            ->first();
    }
}



?>