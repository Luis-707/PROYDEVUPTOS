<?php

class CargosEvaluador {
    private $conexion;
    private $cargo_evaluador = "";
    private $id_usuario = "";
    private $id_cargo_evaluador = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        $this->id_usuario = $dataCliente['id_usuario'];
        $this->id_cargo_evaluador = $dataCliente['id_cargo_evaluador'];
        $this->cargo_evaluador = $dataCliente['cargo_evaluador'];

    }

     // Método que genera la consulta SQL para obtener todos los cargos evaluadores
     public static function sql_listar_cargos_evaluadores(): string {
        return "SELECT * FROM cargos_evaluadores;";
    }

    public static function sql_listar_evaluadores(): string {
        return "
            SELECT 
                e.id_usuario,
                u.cedula_usuario,
                c.cargo_evaluador
            FROM evaluadores e
            INNER JOIN usuarios u 
                ON e.id_usuario = u.id_usuario
            INNER JOIN cargos_evaluadores c 
                ON e.id_cargo_evaluador = c.id_cargo_evaluador;
        ";
    }

     // Método para guardar (INSERT)
     public function sql_guardar_cargo_evaluador(): string {
        return sprintf(
            "INSERT INTO evaluadores (id_usuario, id_cargo_evaluador) VALUES (%d, %d);",
            $this->id_usuario,
            $this->id_cargo_evaluador
           
        );
    }

    //Metodo para actualizar (UPDATE)
    public function sql_actualizar_evaluador(): string {
        return sprintf(
            "UPDATE evaluadores SET id_cargo_evaluador = %d WHERE id_usuario = %d;",
            $this->id_cargo_evaluador,
            $this->id_usuario
        );
    }

    //Metodo para eliminar (DELETE)
    public function sql_eliminar_cargos(): string {
        return sprintf(
            "DELETE FROM evaluadores WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

     // Método que ejecuta la consulta y devuelve el resultado
     public function listarCargosEvaluadores() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_evaluadores());
        }
        return "No se ha definido la conexión";
    }

    public function listarEvaluadores() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_evaluadores());
        }
        return "No se ha definido la conexión";
    }


    // Método para buscar por clave primaria evaluadores
    public function sql_buscar_evaluadores(): string {
        return sprintf(
            "SELECT * FROM evaluadores WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

}



?>
