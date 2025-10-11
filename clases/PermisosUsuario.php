<?php

class PermisosUsuario {
    // Propiedades privadas
    private $conexion;
    private $id_usuario = "";
    private $permisos_id = "";

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = $dataCliente['id_usuario'];
        }
        if (isset($dataCliente['permisos_id'])) {
            $this->permisos_id = $dataCliente['permisos_id'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Destructor
    public function __destruct() {
        // No implementación específica necesaria
    }

    // Método para asignar permiso (INSERT)
    public function sql_asignar(): string {
        return sprintf(
            "INSERT INTO posee_permisos (id_usuario, permisos_id) VALUES (%d, %d);",
            $this->id_usuario,
            $this->permisos_id
        );
    }

    // Método para revocar permiso (DELETE)
    public function sql_revocar(): string {
        return sprintf(
            "DELETE FROM posee_permisos WHERE id_usuario = %d AND permisos_id = %d;",
            $this->id_usuario,
            $this->permisos_id
        );
    }

    // Método para listar permisos con estado ON/OFF
    public function sql_listar(): string {
        return sprintf(
            "SELECT p.permisos_id, p.nombre_permiso,
                    CASE WHEN pp.permisos_id IS NOT NULL THEN TRUE ELSE FALSE END AS acceso
             FROM permisos p
             LEFT JOIN posee_permisos pp
               ON pp.permisos_id = p.permisos_id
              AND pp.id_usuario = %d
             ORDER BY p.nombre_permiso;",
            $this->id_usuario
        );
    }

    // Método para verificar acceso a un permiso específico
    public function sql_verificar($nombre_permiso): string {
        return sprintf(
            "SELECT 1
             FROM posee_permisos pp
             JOIN permisos p ON p.permisos_id = pp.permisos_id
             WHERE pp.id_usuario = %d
               AND p.nombre_permiso = '%s'
             LIMIT 1;",
            $this->id_usuario,
            $nombre_permiso
        );
    }

    // Ejecutores
    public function listar() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_listar());
        }
        return "No se ha definido la conexión";
    }
}