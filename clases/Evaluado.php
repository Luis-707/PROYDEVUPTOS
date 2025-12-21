<?php

class Evaluado {
    // Propiedades privadas
    private $conexion;
    private $clave = "";
    private $id_usuario = 0;
    private $cedula_usuario = "";
    private $rol_id = "";
    private $nombre_completo = "";
    private $tipo_empleado = "";
    private $ubicacion_administrativa = "";
    private $estado_usuario = "";

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        if (isset($dataCliente['clave'])) {
            $this->clave = $dataCliente['clave'];
        }
        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = $dataCliente['cedula_usuario'];
        }
        if (isset($dataCliente['nombre_completo'])) {
            $this->nombre_completo = $dataCliente['nombre_completo'];
        }
        if (isset($dataCliente['tipo_empleado'])) {
            $this->tipo_empleado = $dataCliente['tipo_empleado'];
        }
        if (isset($dataCliente['ubicacion_administrativa'])) {
            $this->ubicacion_administrativa = $dataCliente['ubicacion_administrativa'];
        }
        if (isset($dataCliente['rol_id'])) {
            $this->rol_id = $dataCliente['rol_id'];
        }
        if (isset($dataCliente['estado_usuario'])) {
            $this->estado_usuario = $dataCliente['estado_usuario'];
        }
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = $dataCliente['id_usuario'];
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
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        return sprintf(
            "INSERT INTO usuarios (clave, cedula_usuario, nombre_completo, tipo_empleado, ubicacion_administrativa, rol_id, estado_usuario) VALUES ('%s', %d, '%s', '%s', '%s', %d, '%s') 
             RETURNING id_usuario;",
            addslashes($hash),
            addslashes($this->cedula_usuario),
            addslashes($this->nombre_completo),
            addslashes($this->tipo_empleado),
            addslashes($this->ubicacion_administrativa),
            (int)$this->rol_id,
            ('Activo')
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
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        return sprintf(
            "UPDATE usuarios SET clave = '%s', rol_id = '%d', nombre_completo = '%s', tipo_empleado = '%s', ubicacion_administrativa = '%s' WHERE cedula_usuario = '%d';",
          
            $hash,
            $this->rol_id,
            $this->nombre_completo,
            $this->tipo_empleado,
            $this->ubicacion_administrativa,
            $this->cedula_usuario
        );
    }

    // Método para actualizar el estado del usuario (Activo, Inactivo) según id_usuario
    public function sql_actualizar_estado_usuario_eval(): string {
        return sprintf(
            "UPDATE usuarios SET estado_usuario = '%s' WHERE id_usuario = %d;",
            $this->estado_usuario,
            $this->id_usuario
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listar_user_evaluado(): string {
        return "SELECT u.*
        FROM usuarios u
        JOIN roles_sistema r ON u.rol_id = r.rol_id
        WHERE r.rol = 'Evaluado';";
    }

    // Método para listar todos ejecutando la consulta usando $conexion
    public function listar_user_evaluado() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_user_evaluado());
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

    // Método para buscar por id_usuario
    public function sql_buscar_usuario_eval_id(): string {
        return sprintf(
            "SELECT u.id_usuario, u.cedula_usuario, u.clave, u.estado_usuario, r.rol_id, r.rol
            FROM usuarios u
            JOIN roles_sistema r ON u.rol_id = r.rol_id
            WHERE id_usuario = %d;",
            $this->id_usuario
        );
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
