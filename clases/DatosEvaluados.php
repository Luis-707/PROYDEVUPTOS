<?php

class DatosEvaluados {
    // Propiedades privadas
    private $conexion;
    private $id_evaluador = 0;
    private $id_usuario = 0;
    private $id_cargo_evaluado = 0;

    // Constructor
    public function __construct($dataCliente = array(), $conexion = NULL) {
        if (isset($dataCliente['id_evaluador'])) {
            $this->id_evaluador = (int)$dataCliente['id_evaluador'];
        }        
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = (int)$dataCliente['id_usuario'];
        }        
        if (isset($dataCliente['id_cargo_evaluado'])) {
            $this->id_cargo_evaluado = (int)$dataCliente['id_cargo_evaluado'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Setters
    public function setIdEvaluador($id) { $this->id_evaluador = (int)$id; }
    public function setIdUsuario($id) { $this->id_usuario = (int)$id; }
    public function setIdCargoEvaluado($id) { $this->id_cargo_evaluado = (int)$id; }

    // ✅ Getters (faltaban)
    public function getIdEvaluador() { return $this->id_evaluador; }
    public function getIdUsuario() { return $this->id_usuario; }
    public function getIdCargoEvaluado() { return $this->id_cargo_evaluado; }

    // Destructor
    public function __destruct() {}

    // SQLs
    public function sql_listar_cargos_evaluados(): string {
        return "SELECT * FROM cargos_evaluados;";
    }

    public function listarCargosEvaluados() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_evaluados());
        }
        return "No se ha definido la conexión";
    }

    public static function sql_buscar_usuario_por_cedula(string $cedula): string {
        return sprintf(
            "SELECT id_usuario 
             FROM usuarios 
             WHERE cedula_usuario = '%s' 
             LIMIT 1;",
            addslashes($cedula)
        );
    }

    public static function sql_buscar_evaluador_por_usuario(int $idUsuario): string {
        return sprintf(
            "SELECT id_evaluador 
             FROM evaluadores 
             WHERE id_usuario = %d 
             LIMIT 1;",
            $idUsuario
        );
    }

    public static function sql_guardar_evaluado(int $idUsuarioEvaluado, int $idCargoEvaluado, int $idEvaluador): string {
        return sprintf(
            "INSERT INTO evaluados (id_usuario, id_cargo_evaluado, id_evaluador) 
             VALUES (%d, %d, %d)
             RETURNING id_evaluado;",
            $idUsuarioEvaluado,
            $idCargoEvaluado,
            $idEvaluador
        );
    }

     //Metodo para actualizar (UPDATE)
     public function sql_actualizar_datos_evaluados(): string {
        return sprintf(
            "UPDATE evaluados SET id_cargo_evaluado = %d WHERE id_usuario = %d;",
            $this->id_cargo_evaluado,
            $this->id_usuario
        );
    }


    //Metodo para eliminar (DELETE)
    public function sql_eliminar_datos_evaluados(): string {
        return sprintf(
            "DELETE FROM evaluados WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

    public function sql_buscar_evaluados(): string {
        return sprintf(
            "SELECT * FROM evaluados WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }
}