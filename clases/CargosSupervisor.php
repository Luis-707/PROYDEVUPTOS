<?php

class CargosSupervisor {
    private $conexion;
    /*private $cargo_supervisor = "";*/
    private $id_usuario = "";
    private $id_cargo_supervisor = "";

    // Constructor que recibe la conexión a la base de datos
    public function __construct($dataCliente = array(''),$conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }

        /*$this->cargo_supervisor = $dataCliente['cargo_supervisor'];*/
        $this->id_usuario = $dataCliente['id_usuario'];
        $this->id_cargo_supervisor = $dataCliente['id_cargo_supervisor'];
        
    }

    // Método que genera la consulta SQL para obtener todos los cargos
    public static function sql_listar_cargos_supervisor(): string {
        return "SELECT * FROM cargos_supervisores;";
    }

    public static function sql_listar_supervisores(): string {
        return "
            SELECT 
                e.id_usuario,
                u.cedula_usuario,
                c.cargo_supervisor
            FROM supervisores e
            INNER JOIN usuarios u 
                ON e.id_usuario = u.id_usuario
            INNER JOIN cargos_supervisores c 
                ON e.id_cargo_supervisor = c.id_cargo_supervisor;
        ";
    }
        // Método para guardar (INSERT)
        public function sql_guardar_cargo_supervisor(): string {
            return sprintf(
                "INSERT INTO supervisores (id_usuario, id_cargo_supervisor) VALUES (%d, %d);",
                $this->id_usuario,
                $this->id_cargo_supervisor
            
            );
        }

    //Metodo para actualizar (UPDATE)
    public function sql_actualizar_supervisor(): string {
        return sprintf(
            "UPDATE supervisores SET id_cargo_supervisor = %d WHERE id_usuario = %d;",
            $this->id_cargo_supervisor,
            $this->id_usuario
        );
    }

    //Metodo para eliminar (DELETE)
    public function sql_eliminar_cargos_supervisores(): string {
        return sprintf(
            "DELETE FROM supervisores WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarCargosSupervisores() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_cargos_supervisor());
        }
        return "No se ha definido la conexión";
    }

    public function listarSupervisores() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_supervisores());
        }
        return "No se ha definido la conexión";
    }

    public function sql_buscar_supervisores(): string {
        return sprintf(
            "SELECT * FROM supervisores WHERE id_usuario = %d;",
            $this->id_usuario
        );

    }

    
}


 

?>
