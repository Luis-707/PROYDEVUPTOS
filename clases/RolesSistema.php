<?php

class RolesSistema {
    private $conexion;
    private $rol = "";
    private $rol_id = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        $this->rol = $dataCliente['rol'];
        $this->rol_id = $dataCliente['rol_id'];
    }

    // Método que genera la consulta SQL para obtener todos los roles del sistema
    public static function sql_listar_roles(): string {
        return "SELECT * FROM roles_sistema;";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarRoles() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_roles());
        }
        return "No se ha definido la conexión";
    }

}

?>