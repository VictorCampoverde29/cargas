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
            (CASE WHEN ventas_filtradas.idventas IS NOT NULL THEN 1 ELSE 0 END) AS tiene_venta
        ')
            ->join('carga', 'carga.idcarga = servicio.idcarga')
            ->join('(SELECT idventas, guia_remision FROM ventas WHERE idalmacen IN (20, 39, 40)) ventas_filtradas',
                '(
                    FIND_IN_SET(servicio.n_guia, REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 8) AS UNSIGNED), 8, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 7) AS UNSIGNED), 7, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 6) AS UNSIGNED), 6, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 5) AS UNSIGNED), 5, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 4) AS UNSIGNED), 4, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", LPAD(CAST(RIGHT(servicio.n_guia, 3) AS UNSIGNED), 3, "0")), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 8) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 7) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 6) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 5) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 4) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                    OR FIND_IN_SET(CONCAT("V", SUBSTRING(servicio.n_guia, 2, 3), "-", CAST(RIGHT(servicio.n_guia, 3) AS UNSIGNED)), REPLACE(REPLACE(ventas_filtradas.guia_remision, "/", ","), " ", "")) > 0
                )',
                'left')
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
