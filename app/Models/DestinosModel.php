<?php
namespace App\Models;
use CodeIgniter\Model;

class DestinosModel extends Model
{
    protected $table      = 'destinos';
    protected $primaryKey = 'iddestino';
    protected $allowedFields = ['iddestino', 'nombre', 'estado'];

    public function getDestinos()
    {
        return $this->select('iddestino, nombre, estado')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    public function selectDestinos()
    {
        return $this->select('iddestino, nombre, estado')
            ->where('estado', 'ACTIVO')
            ->orderBy('nombre', 'ASC')
            ->findAll();
    }

    public function getDestinosXcod($cod)
    {
        return $this->select('iddestino, nombre, estado')
            ->where('iddestino', $cod)
            ->first();
    }

    public function exists($nombre, $id = null)
    {
        $query = $this->where('nombre', $nombre);
        if ($id !== null) {
            $query->where('iddestino !=', $id);
        }
        return $query->first() !== null;
    }

    public function buscarDestinos($searchTerm, $limite, $offset)
    {
        return $this->select("iddestino,nombre,estado")
            ->where('estado', 'ACTIVO')
            ->like('nombre', $searchTerm)
            ->orderBy('nombre', 'ASC')
            ->limit($limite, $offset) // Asegurar que se usa limit y offset correctamente
            ->findAll();
    }
}
