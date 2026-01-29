<?php
class RangosCalificacion {
    private $conexion;

    public function __construct($conexion=NULL) {
        if ($conexion != NULL) $this->conexion = $conexion;
    }

    public static function sql_listar(): string {
        return "SELECT rango_id, nombre_rango, puntaje_min, puntaje_max FROM rangos_calificacion ORDER BY puntaje_min;";
    }

    public function obtenerPorPuntaje($puntaje): string {
        return sprintf(
            "SELECT rango_id, nombre_rango 
             FROM rangos_calificacion 
             WHERE %d BETWEEN puntaje_min AND puntaje_max 
             LIMIT 1;",
            (int)$puntaje
        );
    }
}
?>