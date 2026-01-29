<?php
class EvaluacionObreros {
    private $conexion;
    private $id_eval_obreros = 0;
    private $id_evaluado = 0;
    private $rango_id = 0;
    private $id_usuario = 0;
    private $puntaje_total = 0;

    // Campos que existen en la tabla pero NO se usan en esta etapa
    private $conformidad = "";
    private $comentario_evaluado = "";
    private $comentario_supervisor = "";

    public function __construct($data = array(''), $conexion = NULL) {

        if (isset($data['id_eval_obreros'])) 
            $this->id_eval_obreros = (int)$data['id_eval_obreros'];

        if (isset($data['id_evaluado'])) 
            $this->id_evaluado = (int)$data['id_evaluado'];

        if (isset($data['rango_id'])) 
            $this->rango_id = (int)$data['rango_id'];

        if (isset($data['id_usuario'])) 
            $this->id_usuario = (int)$data['id_usuario'];

        if (isset($data['puntaje_total'])) 
            $this->puntaje_total = (int)$data['puntaje_total'];

        if ($conexion != NULL) 
            $this->conexion = $conexion;
    }

    public function setIdEvalOb(int $id): void {
        $this->id_eval_obreros = $id;
    }


    // ============================================================
    // 1) CONSULTA PARA PLANILLA OBREROS
    // ============================================================
    public static function sql_datos_planilla(string $cedula): string {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                e.id_evaluado,
                u.cedula_usuario,
                u.nombre_completo,
                c.cargo_evaluado,
                eo.fecha_inicio,
                ao.nombre_ao AS area_ocupacional,
                uf.nombre_uf AS ubicacion_fisica,
                u.ubicacion_administrativa,
                ev.id_usuario AS id_usuario_evaluador,
                uev.nombre_completo AS nombre_completo_evaluador,
                cev.cargo_evaluador,
                uev.ubicacion_administrativa AS ubicacion_evaluador,
                eo.periodo_evaluacion
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN evaluacion_obreros eo ON eo.id_evaluado = e.id_evaluado
            LEFT JOIN area_ocupacional ao ON e.id_ao = ao.id_ao
            LEFT JOIN ubicacion_fisica uf ON e.id_uf = uf.id_uf
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios uev ON ev.id_usuario = uev.id_usuario
            JOIN cargos_evaluadores cev ON ev.id_cargo_evaluador = cev.id_cargo_evaluador
            WHERE u.cedula_usuario = '%s'
            LIMIT 1;
        ", addslashes($cedula));
    }

    // ============================================================
    // 2) ACTUALIZAR EVALUACIÓN (VERSIÓN FINAL)
    // ============================================================
    public function sql_guardar_evaluacion(): string {
        return sprintf(
            "UPDATE evaluacion_obreros
             SET rango_id = %d,
                 puntaje_total = %d
             WHERE id_evaluado = %d
             RETURNING id_eval_obreros;",
            $this->rango_id,
            $this->puntaje_total,
            $this->id_evaluado
        );
    }

    // ============================================================
    // 3) GUARDAR DETALLE DE CRITERIOS (VERSIÓN FINAL)
    // ============================================================
    public static function sql_guardar_detalle(int $idEval, array $c): string {
        return sprintf(
            "INSERT INTO detalles_evaluacion_obreros
             (id_eval_obreros, criterio_id, puntaje_obtenido)
             VALUES (%d, %d, %d);",
            $idEval,
            (int)$c['criterio_id'],
            (int)$c['puntaje_obtenido']
        );
    }

    // ============================================================
    // 4) LISTAR EVALUACIONES
    // ============================================================
    public static function sql_listar(): string {
        return "
            SELECT 
                eo.id_eval_obreros,
                eo.periodo_evaluacion,
                eo.puntaje_total,
                eo.fecha_inicio,
                eo.fecha_cierre,
                u.cedula_usuario,
                u.nombre_completo,
                r.nombre_rango
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN rangos_calificacion r ON eo.rango_id = r.rango_id
            ORDER BY u.cedula_usuario;
        ";
    }

    public function getIdEvalOb(): int {
        return $this->id_eval_obreros;
    }
}
