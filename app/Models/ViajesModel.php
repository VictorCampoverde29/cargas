<?php

namespace App\Models;

use CodeIgniter\Model;

class ViajesModel extends Model
{
    protected $table      = 'viaje';
    protected $primaryKey = 'idviaje';
    protected $allowedFields = ['idviaje', 'idconductor', 'idunidades', 'fecha_inicio', 'fecha_fin', 'observaciones', 'destinos_origen', 'destinos_destino', 'estado'];

    public function traerViajeReg()
    {
        return $this->select("
            viaje.idviaje,
            viaje.fecha_inicio,
            viaje.fecha_fin,
            viaje.observaciones,
            viaje.estado,
            CONCAT(conductor.apellidos, ' ', conductor.nombres) AS conductor,
            unidades.descripcion AS unidad,
            destino_origen.nombre AS dest_origen,
            destino_destino.nombre AS dest_llegada
        ")
            ->join('conductor', 'conductor.idconductor = viaje.idconductor')
            ->join('unidades', 'unidades.idunidades = viaje.idunidades')
            ->join('destinos AS destino_origen', 'destino_origen.iddestino = viaje.destinos_origen')
            ->join('destinos AS destino_destino', 'destino_destino.iddestino = viaje.destinos_destino')
            ->orderBy('viaje.fecha_inicio', 'DESC')
            ->findAll();
    }

    public function getUltimosViajes($limit = 5)
    {
        return $this->select("
            viaje.idviaje,
            DATE_FORMAT(viaje.fecha_inicio, '%d/%m/%Y') as fecha_viaje,
            viaje.observaciones,
            viaje.estado,
            CONCAT(conductor.apellidos, ' ', conductor.nombres) AS conductor,
            unidades.descripcion AS unidad,
            destino_origen.nombre AS origen,
            destino_destino.nombre AS destino
        ")
            ->join('conductor', 'conductor.idconductor = viaje.idconductor', 'left')
            ->join('unidades', 'unidades.idunidades = viaje.idunidades', 'left')
            ->join('destinos AS destino_origen', 'destino_origen.iddestino = viaje.destinos_origen', 'left')
            ->join('destinos AS destino_destino', 'destino_destino.iddestino = viaje.destinos_destino', 'left')
            ->orderBy('viaje.fecha_inicio', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getEstadisticasViajes()
    {
        return $this->select("
            estado,
            COUNT(*) as total
        ")
            ->whereIn('estado', ['ENTREGADO', 'EN CAMINO'])
            ->groupBy('estado')
            ->findAll();
    }

    public function exists($idconductor, $idunidad, $fecha_inicio, $fecha_fin, $observaciones, $destiorigen, $destillegada, $id = null)
    {
        $query = $this->where('idconductor', $idconductor)
            ->where('idunidades', $idunidad)
            ->where('fecha_inicio', $fecha_inicio)
            ->where('fecha_fin', $fecha_fin)
            ->where('observaciones', $observaciones)
            ->where('destinos_origen', $destiorigen)
            ->where('destinos_destino', $destillegada);

        if ($id !== null) {
            $query->where('idviaje !=', $id);
        }
        return $query->first() !== null;
    }

    public function eliminarViaje(int $idviaje, string $xmlContent): string
{
    try {
        // Llamar al SP con los 3 parámetros (2 de entrada, 1 de salida)
        $sql = 'CALL SP_ELIMINAR_VIAJES(?, ?, @mensaje)';
        $this->db->query($sql, [$idviaje, $xmlContent]);
        
        // Obtener el mensaje de retorno
        $result = $this->db->query("SELECT @mensaje AS mensaje");
        $mensaje = $result->getRow()->mensaje;

        // Verificar si hay error en el mensaje del stored procedure
        if (strpos($mensaje, 'ERROR:') !== false) {
            return $mensaje;
        }
        return $mensaje;
        
    } catch (\mysqli_sql_exception $e) {
        log_message('critical', 'Error al eliminar viaje: ' . $e->getMessage());
        return 'ERROR: ' . $e->getMessage();
    } catch (\Exception $e) {
        log_message('critical', 'Error genérico al eliminar viaje: ' . $e->getMessage());
        return 'ERROR: ' . $e->getMessage();
    }
}
}
