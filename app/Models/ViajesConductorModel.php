<?php

namespace App\Models;

use CodeIgniter\Model;

class ViajesConductorModel extends Model
{
    protected $table      = 'viajes_conductor';
    protected $primaryKey = 'idviajes_conductor';
    protected $allowedFields = ['idviajes_conductor', 'idunidades', 'conductor', 'km_inicial', 'km_final', 'fecha_reg', 'partida', 'llegada', 'estado', 'idviaje'];

    public function traerViajesConductorVinculados($idviaje)
    {
        return $this->select('
            viajes_conductor.idviajes_conductor,
            viajes_conductor.idunidades,
            viajes_conductor.conductor,
            viajes_conductor.km_inicial,
            viajes_conductor.km_final,
            viajes_conductor.fecha_reg,
            viajes_conductor.partida,
            viajes_conductor.llegada,
            viajes_conductor.estado,
            CONCAT(conductor.apellidos, " ", conductor.nombres) AS nombre_conductor,
            unidades.descripcion AS nombre_unidad
        ')
            ->join('conductor', 'conductor.idconductor = viajes_conductor.conductor', 'left')
            ->join('unidades', 'unidades.idunidades = viajes_conductor.idunidades', 'left')
            ->where('viajes_conductor.idviaje', $idviaje)
            ->orderBy('viajes_conductor.fecha_reg', 'DESC')
            ->findAll();
    }

    public function traerViajesConductorDisponibles($idconductor, $idunidades, $fecha_inicio = null, $fecha_fin = null)
    {
        $query = $this->select('
            viajes_conductor.idviajes_conductor,
            viajes_conductor.idunidades,
            viajes_conductor.conductor,
            viajes_conductor.km_inicial,
            viajes_conductor.km_final,
            viajes_conductor.fecha_reg,
            viajes_conductor.partida,
            viajes_conductor.llegada,
            viajes_conductor.estado,
            CONCAT(conductor.apellidos, " ", conductor.nombres) AS nombre_conductor,
            unidades.descripcion AS nombre_unidad
        ')
            ->join('conductor', 'conductor.idconductor = viajes_conductor.conductor', 'left')
            ->join('unidades', 'unidades.idunidades = viajes_conductor.idunidades', 'left')
            ->where('viajes_conductor.conductor', $idconductor)
            ->where('viajes_conductor.idunidades', $idunidades)
            ->where('viajes_conductor.idviaje IS NULL');

        if ($fecha_inicio !== null && $fecha_fin !== null) {
            $query->where('viajes_conductor.fecha_reg >=', $fecha_inicio)
                  ->where('viajes_conductor.fecha_reg <=', $fecha_fin);
        }

        return $query->orderBy('viajes_conductor.fecha_reg', 'DESC')
                    ->findAll();
    }

    public function vincularConViaje($idsViajesConductor, $idviaje)
    {

        $data = [
            'idviaje' => $idviaje
        ];

        $builder = $this->builder();
        $builder->whereIn('idviajes_conductor', $idsViajesConductor);
        return $builder->update($data);
    }
}
