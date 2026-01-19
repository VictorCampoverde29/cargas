<?php

namespace App\Models;

use CodeIgniter\Model;

class GastosViajeModel extends Model
{
    protected $table      = 'gastos_viaje';
    protected $primaryKey = 'idgastos_viaje';

    protected $allowedFields = ['destino_origen', 'destino_destino', 'idunidades', 'tramo_km', 'carreta', 'idcondicion'];

    public function obtenerGastosViaje(){
        return $this->select("gastos_viaje.idgastos_viaje, gastos_viaje.tramo_km, gastos_viaje.carreta, CONCAT(des.nombre, ' - ', des2.nombre) as viaje, uni.descripcion as unidad, condi.descripcion as condicion, (
                SELECT cantidad FROM det_gastos_viaje d
                WHERE d.idgastos_viaje = gastos_viaje.idgastos_viaje
                ORDER BY d.iddet_gastos_viaje ASC
                LIMIT 1
            ) as total_galones")
            ->join('destinos des', 'des.iddestino = gastos_viaje.destino_origen')
            ->join('destinos des2', 'des2.iddestino = gastos_viaje.destino_destino')
            ->join('unidades uni', 'uni.idunidades = gastos_viaje.idunidades')
            ->join('condiciones_parametros_gastoviaje condi', 'condi.idcondiciones_parametros_gastoviaje = gastos_viaje.idcondicion')
            ->findAll();
    }

    public function obtenerGastosViajePorCodigo($orig, $dest, $uni){
        return $this->select('gastos_viaje.idgastos_viaje, gastos_viaje.tramo_km, CONCAT(des.nombre, " - ", des2.nombre) as viaje, uni.descripcion as unidad')
            ->join('destinos des', 'des.iddestino = gastos_viaje.destino_origen')
            ->join('destinos des2', 'des2.iddestino = gastos_viaje.destino_destino')
            ->join('unidades uni', 'uni.idunidades = gastos_viaje.idunidades')
            ->where('gastos_viaje.destino_origen', $orig)
            ->where('gastos_viaje.destino_destino', $dest)
            ->where('gastos_viaje.idunidades', $uni)
            ->first();
    }

    public function registrarGastosViaje(string $xmlContent): string
    {
        try {
            $sql = 'CALL SP_REGISTRAR_GASTOS_VIAJE(?, @mensaje)';
            $this->db->query($sql, [$xmlContent]);

            $result = $this->db->query("SELECT @mensaje AS mensaje");
            $mensaje = $result->getRow()->mensaje;

            if (strpos($mensaje, 'ERROR:') !== false) {
                return $mensaje;
            }

            return $mensaje;
        } catch (\mysqli_sql_exception $e) {
            log_message('critical', 'Error al registrar gasto viaje: ' . $e->getMessage());
            return 'ERROR: ' . $e->getMessage();
        } catch (\Exception $e) {
            log_message('critical', 'Error genérico al registrar gasto viaje: ' . $e->getMessage());
            return 'ERROR: ' . $e->getMessage();
        }
    }

}