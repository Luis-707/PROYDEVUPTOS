<?php

class GestionCompetencia {
    // Propiedades privadas
    private $conexion;
    private $id_competencia = 0;
    private $nombre_competencia = "";
    private $peso_competencia = 0;
    private $estado_competencia = "";

    // Constructor
    public function __construct($data = array(), $conexion = NULL) {
        if (isset($data['id_competencia'])) {
            $this->id_competencia = $data['id_competencia'];
        }
        if (isset($data['nombre_competencia'])) {
            $this->nombre_competencia = $data['nombre_competencia'];
        }
        if (isset($data['peso_competencia'])) {
            $this->peso_competencia = $data['peso_competencia'];
        }
        if (isset($data['estado_competencia'])) {
            $this->estado_competencia = $data['estado_competencia'];
        }
        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    // Método para insertar (INSERT)
    public function sql_insertar_competencia(): string {
        return sprintf(
            "INSERT INTO competencias (nombre_competencia, peso_competencia, estado_competencia) VALUES ('%s', %d, '%s');",
            $this->nombre_competencia,
            $this->peso_competencia,
            ('Activo')
        );
    }

    // Método para eliminar (DELETE) según id_competencia
    public function sql_eliminar_competencia(): string {
        return sprintf(
            "DELETE FROM competencias WHERE id_competencia = %d;",
            $this->id_competencia
        );
    }

    // Método para actualizar (UPDATE) según id_competencia
    public function sql_actualizar_competencia(): string {
        return sprintf(
            "UPDATE competencias SET nombre_competencia = '%s', peso_competencia = %d WHERE id_competencia = %d;",
            $this->nombre_competencia,
            $this->peso_competencia,
            //$this->estado_competencia,
            $this->id_competencia
        );
    }

    // Método para actualizar el estado de la competencia (Activo, Inactivo) según id_competencia
    public function sql_actualizar_estado_competencia(): string {
        return sprintf(
            "UPDATE competencias SET estado_competencia = '%s' WHERE id_competencia = %d;",
            $this->estado_competencia,
            $this->id_competencia
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listarCompetencias(): string {
        return "SELECT * FROM competencias;";
    }

    // Método para listar usando la conexión
    public function listarCompetencias() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listarCompetencias());
        }
        return "No se ha definido la conexión";
    }

    // Método para buscar por nombre_competencia
    public function sql_buscar_por_nombre_competencia(): string {
        return sprintf(
            "SELECT * FROM competencias WHERE UPPER(nombre_competencia) = UPPER('%s');",
            $this->nombre_competencia
        );
    }

    // Método para buscar por id_competencia
    public function sql_buscar_por_id(): string {
        return sprintf(
            "SELECT * FROM competencias WHERE id_competencia = %d;",
            $this->id_competencia
        );
    }

    // Getters y setters
    public function getIdCompetencia(): int {
        return $this->id_competencia;
    }

    public function setIdCompetencia(int $id): void {
        $this->id_competencia = $id;
    }

    public function getNombreCompetencia(): string {
        return $this->nombre_competencia;
    }

    public function setNombreCompetencia(string $nombre): void {
        $this->nombre_competencia = $nombre;
    }

    public function getPesoCompetencia() {
        return $this->peso_competencia;
    }

    public function setPesoCompetencia($peso): void {
        $this->peso_competencia = $peso;
    }

    public function getEstadoCompetencia(): string {
        return $this->estado_competencia;
    }

    public function setEstadoCompetencia(string $estado): void {
        $this->estado_competencia = $estado;
    }
}
?>
