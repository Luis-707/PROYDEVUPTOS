<?php

class CargoSupervisor {
    private $conexion;
    private $nombres_cargos_superv = "";
    private $id_cargos_supervisores = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        $this->nombres_cargos_superv = $dataCliente['nombres_cargos_superv'];
        $this->id_cargos_supervisores = $dataCliente['id_cargos_supervisores'];
    }

    // Método que genera la consulta SQL para obtener todos los cargos
    public static function sql_listar_cargos_supervisor(): string {
        return "SELECT * FROM cargos_supervisores;";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarCargos() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_supervisor());
        }
        return "No se ha definido la conexión";
    }

}
?>
