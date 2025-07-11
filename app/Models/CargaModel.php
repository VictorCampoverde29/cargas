<?php
namespace App\Models;
use CodeIgniter\Model;

class CargaModel extends Model
{
    protected $table      = 'carga';
    protected $primaryKey = 'idcarga';
    protected $allowedFields = ['descripcion', 'estado'];

    public function traerCarga()
    {
        return $this->select('idcarga, descripcion, estado')
            ->orderBy('descripcion', 'ASC')
            ->findAll();
    }

    public function getCargaXcod($cod)
    {
        return $this->select('idcarga, descripcion, estado')
            ->where('idcarga', $cod)
            ->first();
    }

    public function exists($descripcion, $id = null)
    {
        $query = $this->where('descripcion', $descripcion);
        if ($id !== null) {
            $query->where('idcarga !=', $id);
        }
        return $query->first() !== null;
    }
}
