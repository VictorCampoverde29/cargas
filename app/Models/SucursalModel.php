<?php 

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table      = 'sucursal';
    protected $primaryKey = 'idsucursal';

    protected $allowedFields = ['idempresa', 'descripcion', 'estado', 'direccion', 'est_anexo'];

    public function traerSucursales(){
        return $this->select('idsucursal, descripcion')
                    ->where('estado', 'ACTIVO')
                    ->where('idempresa', '9')
                    ->findAll();
    }
}

?>