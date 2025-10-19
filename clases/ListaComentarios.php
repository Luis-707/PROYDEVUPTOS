<?php

class ListaComentarios {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Consulta SQL extendida: incluye periodo_evaluado desde evaluacion_administrativos
    public static function sql_listar_evaluados(): string {
        return "
            SELECT 
                e.id_evaluado,
                u.cedula_usuario,
                c.cargo_evaluado,
                ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
        ";
    }

    public function listarEvaluadosComentarios() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_evaluados());
        }
        return "No se ha definido la conexión";
    }
}