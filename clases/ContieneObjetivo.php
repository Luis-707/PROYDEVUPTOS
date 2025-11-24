<?php

class ContieneObjetivo {
    private $conexion;
    private $id_eval_admin = "";
    private $id_odi = "";

    // Constructor
    public function __construct($data = array(''), $conexion = NULL) {
        if (isset($data['id_eval_admin'])) {
            $this->id_eval_admin = $data['id_eval_admin'];
        }
        if (isset($data['id_odi'])) {
            $this->id_odi = $data['id_odi'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Destructor
    public function __destruct() {
        // No se necesita acción específica
    }

    // Método para insertar una relación Contiene (INSERT)
    public function sql_agregar_objetivo(): string {
        return sprintf(
            "INSERT INTO contiene (id_eval_admin, id_odi) VALUES (%d, %d);",
            $this->id_eval_admin,
            $this->id_odi
        );
    }

    // Método para eliminar una relación Contiene (DELETE)
    public function sql_quitar_objetivo(): string {
        return sprintf(
            "DELETE FROM contiene WHERE id_eval_admin = %d AND id_odi = %d;",
            $this->id_eval_admin,
            $this->id_odi
        );
    }

    // Método para listar permisos con estado ON/OFF
    public function sql_listar_odi(): string {
        return sprintf(
            "SELECT o.id_odi, o.nombre_objetivo, o.peso_objetivo,
                CASE WHEN c.id_odi IS NOT NULL THEN 1 ELSE 0 END AS acceso
                FROM objetivos_desempeno_individual o
            LEFT JOIN contiene c
            ON c.id_odi = o.id_odi
            AND c.id_eval_admin = %d
            ORDER BY o.nombre_objetivo;",
            $this->id_eval_admin
        );
    }

    // Ejecutor de la acción de agregar
    public function agregarObjetivo() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_agregar_objetivo());
        }
        return "No se ha definido la conexión";
    }

    // Ejecutor de la acción de quitar
    public function quitarObjetivo() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_quitar_objetivo());
        }
        return "No se ha definido la conexión";
    }
}
