<?php

class UsuariosSistema {
    private $conexion;
    private $id_usuario = "";
    private $cedula_usuario = "";
    private $rol = "";

    // Constructor que recibe datos y la conexión a la base de datos
    public function __construct($dataUsuario = array(''), $conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        $this->id_usuario = $dataUsuario['id_usuario'] ?? "";
        $this->cedula_usuario = $dataUsuario['cedula_usuario'] ?? "";
        $this->rol = $dataUsuario['rol'] ?? "";
    }

    // Método estático que devuelve la consulta SQL para listar usuarios con su rol
    public static function sql_listar_usuarios(): string {
        return "
            SELECT u.id_usuario, u.cedula_usuario, r.rol
            FROM usuarios u
            INNER JOIN roles_sistema r ON u.rol_id = r.rol_id;
        ";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarUsuarios() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_usuarios());
        }
        return "No se ha definido la conexión";
    }
}

?>
