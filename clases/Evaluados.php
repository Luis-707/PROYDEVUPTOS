<?php
class Evaluado {
    private $conexion;
    private $cedula_usuario = "";
    private $clave = "";
    private $id_cargo_evaluado = 0;
    private $id_usuario = 0;
    private $id_evaluador = 0;

    public function __construct($dataCliente=array(''), $conexion = NULL) {
        if (isset($dataCliente['cedula_evaluado'])) {
            $this->cedula_usuario = $dataCliente['cedula_evaluado'];
        }
        if (isset($dataCliente['clave'])) {
            $this->clave = $dataCliente['clave'];
        }
        if (isset($dataCliente['id_cargo_evaluado'])) {
            $this->id_cargo_evaluado = (int)$dataCliente['id_cargo_evaluado'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // setters para id_usuario e id_evaluador
    public function setIdUsuario($id) { $this->id_usuario = (int)$id; }
    public function setIdEvaluador($id) { $this->id_evaluador = (int)$id; }


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


    //listarEvaluados
    public function sql_listarEvaluados(): string {
        
        return "
        SELECT 
            e.id_usuario,
            u.cedula_usuario,
            c.cargo_evaluado
        FROM evaluados e
        INNER JOIN usuarios u 
            ON e.id_usuario = u.id_usuario
        INNER JOIN cargos_evaluados c 
            ON e.id_cargo_evaluado = c.id_cargo_evaluado;
    ";
       

    }

    public function listarEvaluados() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listarEvaluados());
        }
        return "No se ha definido la conexión";
    }

    // 1) Guardar en usuarios
    public function sql_guardar_usuario($rol_id) {
        return sprintf(
            "INSERT INTO usuarios (rol_id, clave, cedula_usuario) 
             VALUES (%d, '%s', '%s') RETURNING id_usuario;",
            $rol_id,
            addslashes($this->clave),
            addslashes($this->cedula_usuario)
        );
    }

    // 2) Guardar en posee_permisos
    public function sql_guardar_permiso($permisos_id) {
        return sprintf(
            "INSERT INTO posee_permisos (permisos_id, id_usuario) 
             VALUES (%d, %d);",
            $permisos_id,
            $this->id_usuario
        );
    }

    // 3) Guardar en evaluados
    public function sql_guardar_evaluado() {
        return sprintf(
            "INSERT INTO evaluados (id_cargo_evaluado, id_usuario, id_evaluador) 
             VALUES (%d, %d, %d);",
            $this->id_cargo_evaluado,
            $this->id_usuario,
            $this->id_evaluador
        );
    }

    // Buscar permiso "Comentarios"
    public static function sql_buscar_permiso_comentarios() {
        return "SELECT permisos_id FROM permisos WHERE nombre_permiso = 'Comentarios' LIMIT 1;";
    }
}
?>