<?php

namespace App\Models;

use CodeIgniter\Model;

class GuiaTransModel extends Model
{
    protected $table      = 'guia_transportista';
    protected $primaryKey = 'idguia_transportista';

    protected $allowedFields = ['idalmacen', 'idpersonal', 'idremitente', 'idsubcontratado', 'idpaga_flete', 'iddestinatario_guia', 'numero', 'fecha_emision', 'fecha_iniciot', 'ind_transbordoprog', 'ind_transpsubcontratado', 'ind_pagaflete', 'ubi_partida', 'dir_partida', 'ubi_llegada', 'dir_llegada', 'estado', 'glosa', 'xml', 'cdr', 'hash', 'pdf', 'codsunat', 'status_sunat', 'tk_sunat', 'fecha_envio', 'fecha_baja'];

    public function traerGuiasXRangoFechaYSucursal($fechaInicio, $fechaFin, $idsucursal)
    {
        $builder = $this->db->table('guia_transportista');
        $builder->select('
        guia_transportista.numero,
        guia_transportista.fecha_emision,
        guia_transportista.dir_partida,
        guia_transportista.dir_llegada,
        guia_transportista.glosa,
        guia_transportista.estado,
        remitente.razon_social AS remitente_nombre,
        destinatario_guia.nombre AS destinatario_nombre,
        paga_flete.razon_social AS pagaflete,
        sucursal.descripcion AS sucursal_nombre
    ');
        $builder->join('almacen', 'almacen.idalmacen = guia_transportista.idalmacen');
        $builder->join('sucursal', 'sucursal.idsucursal = almacen.idsucursal');
        $builder->join('remitente', 'remitente.idremitente = guia_transportista.idremitente');
        $builder->join('destinatario_guia', 'destinatario_guia.iddestinatario_guia = guia_transportista.iddestinatario_guia');
        $builder->join('paga_flete', 'paga_flete.idpaga_flete = guia_transportista.idpaga_flete', 'left');
        $builder->where('guia_transportista.fecha_emision >=', $fechaInicio);
        $builder->where('guia_transportista.fecha_emision <=', $fechaFin);
        $builder->where('almacen.idsucursal', $idsucursal);
        
        $query = $builder->get();
        return $query->getResultArray();
    }
}
