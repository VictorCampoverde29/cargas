<?php 

namespace App\Models;

use CodeIgniter\Model;

class DestinosModel extends Model
{
    protected $table      = 'destinos';
    protected $primaryKey = 'iddestino';
    protected $allowedFields = ['departamento', 'provincia', 'distrito'];

    public function getDepartamentos(){
        return $this->select("iddestino, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER(departamento), 'Á', 'A'), 'É', 'E'), 'Í', 'I'), 'Ó', 'O'), 'Ú', 'U') AS departamento")
                    ->orderBy('departamento', 'ASC')
                    ->where('distrito','D')
                    ->findAll();
    }
    public function getProvinciaXDep($departamento){
        return $this->select("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER(provincia), 'Á', 'A'), 'É', 'E'), 'Í', 'I'), 'Ó', 'O'), 'Ú', 'U') AS provincia, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER(distrito), 'Á', 'A'), 'É', 'E'), 'Í', 'I'), 'Ó', 'O'), 'Ú', 'U') AS distrito")
                    ->orderBy('departamento', 'ASC')
                    ->where('distrito','P')
                    ->where('departamento',$departamento)
                    ->findAll();
    }

    public function getDistritoXProv($provincia){
        return $this->select("iddestino, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(UPPER(distrito), 'Á', 'A'), 'É', 'E'), 'Í', 'I'), 'Ó', 'O'), 'Ú', 'U') AS distrito")
                    ->where('provincia', $provincia)
                    ->whereNotIn('distrito', ['P'])
                    ->orderBy('distrito', 'ASC')
                    ->findAll();
    }
}

?>