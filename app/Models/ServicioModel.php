<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table      = 'servicio';
    protected $primaryKey = 'idservicio';

    protected $allowedFields = ['idviaje', 'idcarga', 'n_guia', 'fecha_servicio', 'origen', 'destino', 'flete', 'emisor', 'receptor', 'glosa', 'estado'];

    public function traerServiciosXCod($cod)
    {
        $builder = $this->db->table('servicio');
        $builder->select('
        servicio.idservicio,
        servicio.n_guia,
        servicio.fecha_servicio,
        servicio.origen,
        servicio.destino,
        servicio.flete,
        servicio.emisor,
        servicio.receptor,
        servicio.glosa,
        servicio.estado,
        carga.descripcion AS nombre_carga,
    ');
        $builder->join('carga', 'carga.idcarga = servicio.idcarga');
        $builder->where('servicio.idviaje', $cod);

        $query = $builder->get();
        return $query->getResultArray();
    }
}
