<?php

class Criterios {

    private $conexion;

    public function __construct($dataCliente=array(''), $conexion=null){
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_listar_por_factor($factorId): string {
        return sprintf(
            "SELECT criterio_id, factor_id, codigo_criterio, descripcion_criterio, valor_criterio
             FROM criterios 
             WHERE factor_id=%d 
             ORDER BY codigo_criterio;",
            (int)$factorId
        );
    }
}
