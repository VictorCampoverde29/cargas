<?php 

namespace App\Models;

use CodeIgniter\Model;

class ViajesModel extends Model
{
    protected $table      = 'viaje';
    protected $primaryKey = 'idviaje';

    protected $allowedFields = ['idconductor', 'idunidades', 'fecha_inicio', 'fecha_fin', 'observaciones', 'destinos_origen', 'destinos_destino', 'estado'];

    
}

?>