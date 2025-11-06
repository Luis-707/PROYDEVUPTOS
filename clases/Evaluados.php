<?php

class Evaluado {
    // Propiedades privadas
    private $conexion;
    private $clave = "";
    private $id_usuario = 0;
    private $cedula_usuario = "";
    private $rol_id = "";
    private $id_evaluador = 0;
    private $id_cargo_evaluado = 0;

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        
        if (isset($dataCliente['id_usuario'])) {
                $this->id_usuario = (int)$dataCliente['id_usuario'];
            }        

        
        if (isset($dataCliente['clave'])) {
            $this->clave = $dataCliente['clave'];
        }
        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = $dataCliente['cedula_usuario'];
        }
        if (isset($dataCliente['rol_id'])) {
            $this->rol_id = $dataCliente['rol_id'];
        }

        if (isset($dataCliente['id_cargo_evaluado'])) {
            $this->id_cargo_evaluado = (int)$dataCliente['id_cargo_evaluado'];
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

    // Método para guardar en usuarios
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

      // Método para guardar registro de evaluado(INSERT)
     /* public function sql_guardar_cargo_evaluado(): string {
        return sprintf(
            "INSERT INTO evaluados (id_usuario, id_cargo_evaluado, id_evaluador) VALUES (%d, %d, %d);",
            $this->id_usuario,
            $this->id_cargo_evaluado,
            $this->id_evaluador
        );
    }*/

    //Metodo para actualizar registro de evaluado(UPDATE)
    /*public function sql_actualizar_evaluado(): string {
        return sprintf(
            "UPDATE evaluados SET id_cargo_evaluado = %d WHERE id_usuario = %d;",
            $this->id_cargo_evaluado,
            $this->id_usuario
        );
    }*/

    //Metodo para eliminar registro de evaluado(DELETE)
    /*public function sql_eliminar_cargos(): string {
        return sprintf(
            "DELETE FROM evaluados WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }*/

    // Método para listar todos (SELECT)
    public static function sql_listar_user_evaluado(): string {
        return "SELECT u.id_usuario,
        u.clave,
       u.cedula_usuario,
       ce.cargo_evaluado,
       e.id_cargo_evaluado
FROM usuarios u
JOIN evaluados e ON u.id_usuario = e.id_usuario
JOIN cargos_evaluados ce ON e.id_cargo_evaluado = ce.id_cargo_evaluado;";
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


   // Método para obtener id_evaluador a partir del id_usuario en sesión
// Buscar id_evaluador a partir del id_usuario en sesión
public static function sql_buscar_id_evaluador_por_usuario(int $idUsuario): string {
    return sprintf(
        "SELECT ev.id_evaluador
         FROM evaluadores ev
         WHERE ev.id_usuario = %d
         LIMIT 1;",
        $idUsuario
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