<?php

class AsignarSupervisores {
    private $id_supervisor;
    private $evaluadores = [];
    private $conexion;

    public function __construct($data, $conexion) {
        $this->conexion = $conexion;
        $this->id_supervisor = intval($data['id_supervisor'] ?? 0);

        // Soporte para array evaluadores[] o string evaluadores_ids
        if (isset($data['evaluadores']) && is_array($data['evaluadores'])) {
            // Caso: FormData con múltiples 'evaluadores[]'
            $this->evaluadores = array_map('intval', $data['evaluadores']);
        } elseif (!empty($data['evaluadores_ids'])) {
            // Caso: cadena "1,2,3" → convertir a array de enteros
            $this->evaluadores = array_map('intval', explode(',', $data['evaluadores_ids']));
           
        } else {
            $this->evaluadores = [];
        }

        $this->evaluadores = array_filter($this->evaluadores, fn($e) => is_numeric($e) && $e > 0);
    }

    public static function sql_listar_evaluadores(): string {
        return "
            SELECT 
                e.id_evaluador,
                u.cedula_usuario
            FROM evaluadores e
            INNER JOIN usuarios u
                ON e.id_usuario = u.id_usuario;
        ";
    }
    
    public function listarEvaluadores() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_evaluadores());
        }
        return "No se ha definido la conexión";
    }

    public function getEvaluadores(): array {
        return $this->evaluadores;
    }

    public function sql_buscar_asignacion(int $idEvaluador): string {
        return sprintf(
            "SELECT 1 FROM asignados_supervisor 
             WHERE id_supervisor = %d AND id_evaluador = %d;",
            $this->id_supervisor,
            $idEvaluador
        );
    }

    public function sql_guardar_asignacion(int $idEvaluador): string {
        return sprintf(
            "INSERT INTO asignados_supervisor (id_supervisor, id_evaluador) 
             VALUES (%d, %d);",
            $this->id_supervisor,
            $idEvaluador
        );
    }

    public function eliminarAsignacion() {
        if ($this->conexion == NULL) return "No se ha definido la conexión";
        if (empty($this->id_supervisor)) return "ID de supervisor no especificado";

        $idSup = $this->id_supervisor;
        $sqlDelete = "DELETE FROM asignados_supervisor WHERE id_supervisor = $idSup";

        $res = $this->conexion->ejecutarConsultaBdds($sqlDelete);
        return $res !== false ? true : "Error en DELETE";
    }
}