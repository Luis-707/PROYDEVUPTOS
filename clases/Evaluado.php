<?php

class Evaluado {
    // Propiedades privadas
    private $conexion;
    private $clave = "";
    private $id_usuario = 0;
    private $cedula_usuario = "";
    private $rol_id = "";

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        if (isset($dataCliente['clave'])) {
            $this->clave = $dataCliente['clave'];
        }
        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = $dataCliente['cedula_usuario'];
        }
        if (isset($dataCliente['rol_id'])) {
            $this->rol_id = $dataCliente['rol_id'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Destructor
    public function __destruct() {
        // No implementación específica necesaria
    }

    public function setIdUsuario($id) { $this->id_usuario = (int)$id; }

    //listarCargosEvaluados
    public function sql_listar_cargos_evaluados(): string {

        return "SELECT * FROM cargos_evaluados;";
    }

     // Método que ejecuta la consulta y devuelve el resultado
    public function listarCargosEvaluados() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_evaluados());
        }
        return "No se ha definido la conexión";
    }

    public function sql_guardar_user_evaluado(): string {
        return sprintf(
            "INSERT INTO usuarios (clave, cedula_usuario, rol_id) 
             VALUES ('%s', '%s', %d) 
             RETURNING id_usuario;",
            addslashes($this->clave),
            addslashes($this->cedula_usuario),
            (int)$this->rol_id
        );
    }

    // Metodo para guardar en posee_permisos
    public function sql_guardar_permiso($permisos_id) {
        return sprintf(
            "INSERT INTO posee_permisos (permisos_id, id_usuario) 
             VALUES (%d, %d);",
            $permisos_id,
            $this->id_usuario
        );
    }

    // Método para eliminar (DELETE) según cedula_usuario
    public function sql_eliminar_user_evaluado(): string {
        return sprintf(
            "DELETE FROM usuarios WHERE cedula_usuario = %d;",
            $this->cedula_usuario
        );
    }

    // Método para eliminar permisos de un usuario
public function sql_eliminar_permiso(): string {
    return sprintf(
        "DELETE FROM posee_permisos WHERE id_usuario = %d;",
        $this->id_usuario
    );
}

    // Método para actualizar (UPDATE) según cedula_usuario
    public function sql_actualizar_user_evaluado(): string {
        return sprintf(
            "UPDATE usuarios SET clave = '%s' WHERE cedula_usuario = '%d';",
          
            $this->clave,
            $this->cedula_usuario
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listar_user_evaluado(): string {
        return "SELECT u.*
        FROM usuarios u
        JOIN roles_sistema r ON u.rol_id = r.rol_id
        WHERE r.rol = 'Evaluado';";
    }

    public static function sql_listar_evaluados_por_cedula(string $cedula): string {
        return sprintf("
            SELECT u.*
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    // Método para listar todos ejecutando la consulta usando $conexion
    public function listar_user_evaluado() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_evaluados_por_cedula());
        }
        return "No se ha definido la conexión";
    }

    // Método para buscar por clave primaria cedula_usuario
    public function sql_buscar_user_evaluado(): string {
        return sprintf(
            "SELECT u.id_usuario, u.cedula_usuario, u.clave, r.rol_id, r.rol
             FROM usuarios u
             JOIN roles_sistema r ON u.rol_id = r.rol_id
             WHERE u.cedula_usuario = '%s';",
            addslashes($this->cedula_usuario)
        );
    }

    // Método para buscar permiso comentarios
    // Buscar permiso "Comentarios"
    public static function sql_buscar_permiso_comentarios() {
        return "SELECT permisos_id FROM permisos WHERE nombre_permiso = 'Comentarios' LIMIT 1;";
    }

    // Getters
    public function getClave(): string {
        return $this->clave;
    }

    public function getCedulaUsuario(): string {
        return $this->cedula_usuario;
    }
}
?>
