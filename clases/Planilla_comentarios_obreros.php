<?php

class Planilla_comentarios_obreros {

    private $conexion;
    private $cedula_usuario = "";
    private $comentario_supervisor = "";
    private $comentario_evaluado = "";
    private $conformidad = "";
    private $id_eval_obreros = 0;

    public function __construct($dataCliente=array(''), $conexion = NULL) {

        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = trim($dataCliente['cedula_usuario']);
        }

        if (isset($dataCliente['id_eval_obreros'])) {
            $this->id_eval_obreros = (int)$dataCliente['id_eval_obreros'];
        }

        if (isset($dataCliente['comentario_supervisor'])) {
            $this->comentario_supervisor = trim($dataCliente['comentario_supervisor']);
        }

        if (isset($dataCliente['comentario_evaluado'])) {
            $this->comentario_evaluado = trim($dataCliente['comentario_evaluado']);
        }

        if (isset($dataCliente['conformidad'])) {
            $this->conformidad = trim($dataCliente['conformidad']);
        }

        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public function getIdEvalObrero(): int {
        return $this->id_eval_obreros;
    }

    public function setIdEvalObrero(int $id): void {
        $this->id_eval_obreros = $id;
    }

    // ============================================================
    // RELACIONES (Evaluado, Evaluador, Supervisor)
    // ============================================================
    public static function sql_relaciones_por_cedula_obrero(string $cedula): string {
        return sprintf(
            "SELECT 
                e.id_evaluado,
                uf_e.nombre_uf,
                ao.nombre_ao,
    
                u_e.cedula_usuario,
                u_e.nombre_completo AS nombre_completo_evaluado,
                u_e.ubicacion_administrativa AS ubicacion_evaluado,
                c_o.cargo_evaluado,
    
                u_ev.id_usuario AS id_usuario_evaluador,
                u_ev.cedula_usuario AS cedula_evaluador,
                u_ev.nombre_completo AS nombre_completo_evaluador,
                u_ev.ubicacion_administrativa AS ubicacion_evaluador,
                c_ee.cargo_evaluador AS cargo_evaluador
    
            FROM evaluados e
            JOIN usuarios u_e ON e.id_usuario = u_e.id_usuario
            JOIN cargos_evaluados c_o ON e.id_cargo_evaluado = c_o.id_cargo_evaluado
            JOIN ubicacion_fisica uf_e ON e.id_uf = uf_e.id_uf
            JOIN area_ocupacional ao ON e.id_ao = ao.id_ao
    
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            JOIN cargos_evaluadores c_ee ON ev.id_cargo_evaluador = c_ee.id_cargo_evaluador
    
            WHERE u_e.cedula_usuario = '%s'
            LIMIT 1;",
            addslashes($cedula)
        );
    }
    // ============================================================
    // BUSCAR EVALUACIÓN OBRERA
    // ============================================================
    public function sql_buscar_obrero(): string {
        return sprintf(
            "SELECT 
                eo.id_eval_obreros,
                eo.id_evaluado,
                eo.puntaje_total,
                eo.rango_id,
                eo.periodo_evaluacion,
                eo.tiempo_puesto,
                rc.nombre_rango

            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN rangos_calificacion rc ON eo.rango_id = rc.rango_id

            WHERE u.cedula_usuario = '%s'
            AND eo.id_eval_obreros = %d
            LIMIT 1;",
            addslashes($this->cedula_usuario),
            addslashes($this->id_eval_obreros)
        );
    }

    public function buscarEvaluacionObrero() {
        if ($this->conexion != null && $this->cedula_usuario !== "") {
            return $this->conexion->ejecutarConsultaBdds($this->sql_buscar_obrero());
        }
        return [];
    }

    // ============================================================
    // VALIDAR PERMISOS (Evaluado)
    // ============================================================
    public function sql_buscar_por_id_y_evaluado_obrero(string $cedula): string {
        return sprintf(
            "SELECT eo.id_eval_obreros
             FROM evaluacion_obreros eo
             JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
             JOIN usuarios u ON e.id_usuario = u.id_usuario
             WHERE eo.id_eval_obreros = %d
               AND u.cedula_usuario = '%s';",
            $this->id_eval_obreros,
            addslashes($cedula)
        );
    }

    // ============================================================
    // VALIDAR PERMISOS (Supervisor)
    // ============================================================
    public function sql_buscar_por_id_y_supervisor_obrero(string $cedula): string {
        return sprintf(
            "SELECT eo.id_eval_obreros
             FROM evaluacion_obreros eo
             JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
             JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
             JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
             JOIN usuarios u ON s.id_usuario = u.id_usuario
             WHERE eo.id_eval_obreros = %d
               AND u.cedula_usuario = '%s';",
            $this->id_eval_obreros,
            addslashes($cedula)
        );
    }

    // ============================================================
    // FACTORES Y CRITERIOS
    // ============================================================
    public static function sql_factores_obrero(int $idEvalObrero): string {
        return "
           SELECT 
                f.nombre_factor,
                c.descripcion_criterio,
                deo.puntaje_obtenido
            FROM detalles_evaluacion_obreros deo
            JOIN criterios c ON deo.criterio_id = c.criterio_id
            JOIN factores f ON c.factor_id = f.factor_id
            WHERE deo.id_eval_obreros = $idEvalObrero
            ORDER BY deo.puntaje_obtenido DESC
            LIMIT 100
        ";
    }

    // ============================================================
    // UPDATE COMENTARIOS
    // ============================================================
    public function sql_update_comentario_supervisor_obrero(): string {
        return sprintf(
            "UPDATE evaluacion_obreros
             SET comentario_supervisor = '%s'
             WHERE id_eval_obreros = %d
             RETURNING id_eval_obreros;",
            addslashes($this->comentario_supervisor),
            $this->id_eval_obreros
        );
    }

    public function sql_update_comentario_evaluado_obrero(): string {
        return sprintf(
            "UPDATE evaluacion_obreros
             SET comentario_evaluado = '%s',
                 conformidad = '%s'
             WHERE id_eval_obreros = %d
             RETURNING id_eval_obreros;",
            addslashes($this->comentario_evaluado),
            addslashes($this->conformidad),
            $this->id_eval_obreros
        );
    }
}

?>
