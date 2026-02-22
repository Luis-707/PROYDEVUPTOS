<?php

class RolesSistema {
    private $conexion;
    private $rol_id = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        if (isset($dataCliente['rol_id'])) {
            $this->rol_id = $dataCliente['rol_id'];
        }
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = $dataCliente['id_usuario'];
        }
    }

    // Método para asignar roles (INSERT)
    public function sql_asignar_rol(): string {
        return sprintf(
            "INSERT INTO posee_rol (id_usuario, rol_id) VALUES (%d, %d);",
            $this->id_usuario,
            $this->rol_id
        );
    }

    // Método para revocar rol (DELETE)
    public function sql_revocar_rol(): string {
        return sprintf(
            "DELETE FROM posee_rol WHERE id_usuario = %d AND rol_id = %d;",
            $this->id_usuario,
            $this->rol_id
        );
    }

    // Método para listar rol con estado ON/OFF
    public function sql_listar_rol(): string {
        return sprintf(
            "SELECT r.rol_id, r.rol,
                    CASE WHEN rr.rol_id IS NOT NULL THEN 1 ELSE 0 END AS acceso
             FROM roles_sistema r
             LEFT JOIN posee_rol rr
               ON rr.rol_id = r.rol_id
              AND rr.id_usuario = %d
             ORDER BY r.rol;",
            $this->id_usuario
        );
    }

    // Método para verificar acceso a un rol específico
    public function sql_verificar_rol($rol): string {
        return sprintf(
            "SELECT 1
             FROM posee_rol rr
             JOIN roles_sistema r ON r.rol_id = rr.rol_id
             WHERE rr.id_usuario = %d
               AND r.rol = '%s'
             LIMIT 1;",
            $this->id_usuario,
            $rol
        );
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

    public static function sql_listar_roles_select(): string {
        return "SELECT * FROM roles_sistema WHERE rol != 'Administrador' AND rol != 'Evaluado';";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarRolesSelect() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_roles_select());
        }
        return "No se ha definido la conexión";
    }

}

?>