<?php 

namespace App\Models;

use CodeIgniter\Model;

class DestinosModel extends Model
{
    protected $table      = 'destinos';
    protected $primaryKey = 'iddestino';
    protected $allowedFields = ['nombre', 'estado'];

    public function getDestinos(){
        return $this->select('iddestino, nombre, estado')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }
    
}

?>