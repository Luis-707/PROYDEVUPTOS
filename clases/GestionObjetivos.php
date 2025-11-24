<?php

class GestionObjetivo {
    // Propiedades privadas
    private $conexion;
    private $id_odi = 0;
    private $nombre_objetivo = "";
    private $peso_objetivo = 0;

    // Constructor
    public function __construct($dataCliente = array(''), $conexion = NULL) {
        if (isset($dataCliente['id_odi'])) {
            $this->id_odi = $dataCliente['id_odi'];
        }
        if (isset($dataCliente['nombre_objetivo'])) {
            $this->nombre_objetivo = $dataCliente['nombre_objetivo'];
        }
        if (isset($dataCliente['peso_objetivo'])) {
            $this->peso_objetivo = $dataCliente['peso_objetivo'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Destructor
    public function __destruct() {
        // No implementación específica necesaria
    }

    // Método para insertar (INSERT)
    public function sql_insertar_odi(): string {
        return sprintf(
            "INSERT INTO objetivos_desempeno_individual (nombre_objetivo, peso_objetivo) VALUES ('%s', %s);",
            $this->nombre_objetivo,
            $this->peso_objetivo
        );
    }

    // Método para eliminar (DELETE) según id_odi
    public function sql_eliminar_odi(): string {
        return sprintf(
            "DELETE FROM objetivos_desempeno_individual WHERE id_odi = %d;",
            $this->id_odi
        );
    }

    // Método para actualizar (UPDATE) según id_odi
    public function sql_actualizar_odi(): string {
        return sprintf(
            "UPDATE objetivos_desempeno_individual SET nombre_objetivo = '%s', peso_objetivo = %d WHERE id_odi = %d;",
            $this->nombre_objetivo,
            $this->peso_objetivo,
            $this->id_odi
        );
    }

    // Método para listar todos (SELECT)
    public static function sql_listarObjetivo(): string {
        return "SELECT * FROM objetivos_desempeno_individual;";
    }

    // Método para listar usando la conexión
    public function listarOdi() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listarObjetivo());
        }
        return "No se ha definido la conexión";
    }

    // Método para buscar por nombre_objetivo
    public function sql_buscar_odi(): string {
        return sprintf(
            "SELECT * FROM objetivos_desempeno_individual WHERE UPPER(nombre_objetivo) = UPPER('%s');",
            $this->nombre_objetivo
        );
    }

    //Metodo para buscar por id_odi
    public function sql_buscar_odi_id(): string {
        return sprintf(
            "SELECT * FROM objetivos_desempeno_individual WHERE id_odi = %d;",
            $this->id_odi
        );
    }

    // Getters y Setters opcionales
    public function getIdOdi(): int {
        return $this->id_odi;
    }

    public function setIdOdi(int $id): void {
        $this->id_odi = $id;
    }

    public function getNombreObjetivo(): string {
        return $this->nombre_objetivo;
    }

    public function setNombreObjetivo(string $nombre): void {
        $this->nombre_objetivo = $nombre;
    }

    public function getPesoObjetivo() {
        return $this->peso_objetivo;
    }

    public function setPesoObjetivo($peso): void {
        $this->peso_objetivo = $peso;
    }
}
?>
