<?php 

namespace App\Models;

use CodeIgniter\Model;

class TipoCargaModel extends Model
{
    protected $table      = 'tipo_carga';
    protected $primaryKey = 'idtipo_carga';

    protected $allowedFields = ['tipo', 'estado'];

    public function traerTipoCarga(){
        return $this->select('idtipo_carga, tipo')
                    ->findAll();
    }
}


?>