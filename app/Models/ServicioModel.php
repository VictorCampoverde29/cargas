<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table      = 'servicio';
    protected $primaryKey = 'idservicio';
    protected $allowedFields = ['idservicio', 'idviaje', 'idcarga', 'n_guia', 'fecha_servicio', 'origen', 'destino', 'flete', 'emisor', 'receptor', 'glosa', 'estado'];

    public function traerServiciosXCod($cod)
    {
        return $this->select('
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
            (CASE WHEN ventas_filtradas.numero_doc IS NOT NULL THEN 1 ELSE 0 END) AS tiene_venta
        ')
            ->join('carga', 'carga.idcarga = servicio.idcarga')
            ->join('ventas', 'ventas.numero_doc = servicio.n_guia', 'left')
            ->join('ventas ventas_filtradas', 'ventas_filtradas.numero_doc = servicio.n_guia AND ventas_filtradas.idalmacen IN (20, 39, 40)', 'left')
            ->where('servicio.idviaje', $cod)
            ->groupBy('servicio.idservicio')
            ->findAll();
    }

    public function exists($nguia, $id = null)
    {
        $query = $this->where('n_guia', $nguia);
        if ($id !== null) {
            $query->where('idservicio !=', $id);
        }
        return $query->first() !== null;
    }

    public function actualizarEstadoServicio($xmlData)
    {
        try {
            $this->db->query("CALL SP_ACTUALIZAR_ESTADO_SERVICIO(?, @message)", [$xmlData]);

            while ($this->db->connID->more_results() && $this->db->connID->next_result()) {
                $this->db->connID->use_result();
            }

            $result = $this->db->query("SELECT @message AS message");
            $mensaje = $result->getRow()->message;

            return $mensaje;
        } catch (\mysqli_sql_exception $e) {
            return 'Error: ' . $e->getMessage();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
