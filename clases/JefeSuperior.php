<?php

class JefeSuperior {
    private $conexion;
    private $cargo_superior = "";
    private $cedula_superior = "";
    private $id_jefe_superior = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        $this->cargo_superior = $dataCliente['cargo_superior'];
        $this->cedula_superior = $dataCliente['cedula_superior'];
        $this->id_jefe_superior = $dataCliente['id_jefe_superior'];
    }

    // Método que genera la consulta SQL para obtener todos los cargos de los jefes
    public static function sql_listar_cargos_jefes(): string {
        return "SELECT * FROM jefes_superiores;";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarJefes() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_jefes());
        }
        return "No se ha definido la conexión";
    }

}

?>