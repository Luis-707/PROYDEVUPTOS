<?php

class Usuario {
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

    // Método para guardar (INSERT)
    public function sql_guardar(): string {
        $hash = password_hash($this->clave, PASSWORD_DEFAULT);
        return sprintf(
            "INSERT INTO usuarios (clave, cedula_usuario, nombre_completo, tipo_empleado, ubicacion_administrativa, rol_id, estado_usuario) VALUES ('%s', %d, '%s', '%s', '%s', %d, '%s');",
            $hash,
            $this->cedula_usuario,
            $this->nombre_completo,
            $this->tipo_empleado,
            $this->ubicacion_administrativa,
            $this->rol_id,
            ('Activo')
        );
    }

    // Método para eliminar (DELETE) según cedula_usuario
    public function sql_eliminar(): string {
        return sprintf(
            "DELETE FROM usuarios WHERE cedula_usuario = %d;",
            $this->cedula_usuario
        );
    }

    // Método para actualizar (UPDATE) según cedula_usuario
    public function sql_actualizar(): string {
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
    public function sql_actualizar_estado_usuario(): string {
        return sprintf(
            "UPDATE usuarios SET estado_usuario = '%s' WHERE id_usuario = %d;",
            $this->estado_usuario,
            $this->id_usuario
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listar(): string {
        return "SELECT u.*
        FROM usuarios u
        JOIN roles_sistema r ON u.rol_id = r.rol_id
        WHERE r.rol != 'Evaluado';";
    }

    // Método para listar todos ejecutando la consulta usando $conexion
    public function listar() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
        }
        return "No se ha definido la conexión";
    }

    // Método para buscar por clave primaria cedula_usuario
    public function sql_buscar(): string {
        return sprintf(
            "SELECT 
                u.id_usuario,
                u.cedula_usuario,
                u.clave,
                u.estado_usuario,
                r.rol AS nombre_rol
            FROM usuarios u
            JOIN posee_rol pr ON pr.id_usuario = u.id_usuario
            JOIN roles_sistema r ON r.rol_id = pr.rol_id
            WHERE u.cedula_usuario = '%s';",
            addslashes($this->cedula_usuario)
        );
    }

    // Método para consultar datos para el perfil de usuario
    public function sql_perfil_usuario(): string {
        return sprintf(
            "SELECT 
            u.cedula_usuario, u.nombre_completo, u.ubicacion_administrativa,
            COALESCE(ce.cargo_evaluado, ceva.cargo_evaluador, cs.cargo_supervisor) AS cargo_usuario,
            r.rol
            FROM usuarios u
            JOIN roles_sistema r ON u.rol_id = r.rol_id
            LEFT JOIN evaluados e ON u.id_usuario = e.id_usuario
            LEFT JOIN cargos_evaluados ce ON e.id_cargo_evaluado = ce.id_cargo_evaluado
            LEFT JOIN evaluadores eva ON u.id_usuario = eva.id_usuario
            LEFT JOIN cargos_evaluadores ceva ON eva.id_cargo_evaluador = ceva.id_cargo_evaluador
            LEFT JOIN supervisores s ON u.id_usuario = s.id_usuario
            LEFT JOIN cargos_supervisores cs ON s.id_cargo_supervisor = cs.id_cargo_supervisor
             WHERE u.cedula_usuario = '%d';",
            $this->cedula_usuario
        );
    }

    // Método para buscar por id_usuario
    public function sql_buscar_usuario_por_id(): string {
        return sprintf(
            "SELECT 
                u.id_usuario,
                u.cedula_usuario,
                u.estado_usuario,
                r.rol AS nombre_rol
            FROM usuarios u
            JOIN posee_rol pr ON pr.id_usuario = u.id_usuario
            JOIN roles_sistema r ON r.rol_id = pr.rol_id
            WHERE u.id_usuario = %d;",
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
