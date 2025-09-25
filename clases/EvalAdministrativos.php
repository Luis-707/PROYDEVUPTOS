<?php

class EvalAdministrativos {
    private $conexion;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Método estático que devuelve la consulta SQL para listar evaluados administrativos
    public static function sql_listar_evaluados(): string {
        return "SELECT e.id_evaluado, u.cedula_usuario, c.cargo_evaluado
                FROM evaluados e
                JOIN usuarios u ON e.id_usuario = u.id_usuario
                JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarEvaluados() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_evaluados());
        }
        return "No se ha definido la conexión";
    }
}

?>

