<?php

class Usuario {
    // Propiedades privadas
    private $conexion;
    private $clave = "";
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

    // Método para guardar (INSERT)
    public function sql_guardar(): string {
        return sprintf(
            "INSERT INTO usuarios (clave, cedula_usuario, rol_id) VALUES ('%s', %d, %d);",
            $this->clave,
            $this->cedula_usuario,
            $this->rol_id
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
        return sprintf(
            "UPDATE usuarios SET clave = '%s', rol_id = '%d' WHERE cedula_usuario = '%d';",
          
            $this->clave,
            $this->rol_id,
            $this->cedula_usuario
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listar(): string {
        return "SELECT * FROM usuarios;";
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
            "SELECT * FROM usuarios WHERE cedula_usuario = %d;",
            $this->cedula_usuario
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
