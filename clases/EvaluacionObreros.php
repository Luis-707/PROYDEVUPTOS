<?php

class EvaluacionObreros {

    private $conexion;

    private $id_eval_obreros = 0;
    private $evaluado_id = 0;     // ← CORRECTO   // ← CORRECTO
    private $rango_id = 0;
    private $tiempo_puesto = 0;
    private $puntaje_total = 0;
    private $conformidad = 0;
    private $comentario_evaluado = '';
    private $comentario_supervisor = '';

    public function __construct($data = array(), $conexion = NULL) {

        if (isset($data['id_eval_obreros']))
            $this->id_eval_obreros = (int)$data['id_eval_obreros'];

        if (isset($data['evaluado_id']))
            $this->evaluado_id = (int)$data['evaluado_id'];

        if (isset($data['rango_id']))
            $this->rango_id = (int)$data['rango_id'];

        if (isset($data['tiempo_puesto']))
            $this->tiempo_puesto = (int)$data['tiempo_puesto'];

        if (isset($data['puntaje_total']))
            $this->puntaje_total = (int)$data['puntaje_total'];

        if (isset($data['conformidad']))
            $this->conformidad = (int)$data['conformidad'];

        if (isset($data['comentario_evaluado']))
            $this->comentario_evaluado = trim($data['comentario_evaluado']);

        if (isset($data['comentario_supervisor']))
            $this->comentario_supervisor = trim($data['comentario_supervisor']);

        if ($conexion !== NULL)
            $this->conexion = $conexion;
    }

    // ============================================================
    // 1) CONSULTA REAL PARA PLANILLA OBREROS
    // ============================================================
    public static function sql_datos_planilla(string $cedula, int $idEval): string {
        return sprintf("
           SELECT     
                ev.nombre_completo AS nombre_completo_evaluador,
                ev.cedula_usuario AS cedula_evaluador,
                ev_cargo.nombre_cargo AS cargo_evaluador,
                ev.ubicacion_administrativa AS ubicacion_evaluador,
                
                ed.nombre_completo AS nombre_completo,
                ed.cedula_usuario AS cedula_usuario,
                ed_cargo.nombre_cargo AS cargo_evaluado,
                ed_org.nombre AS area_ocupacional,
                ed.ubicacion_administrativa AS ubicacion_administrativa,
                uf.nombre_ubicacion AS ubicacion_fisica,
                ed.fecha_ingreso,

                e.id_eval_obreros,
                e.evaluado_id,
                e.periodo_evaluacion,
                e.fecha_inicio,
                e.fecha_cierre

            FROM evaluacion_obreros e

            JOIN usuarios ev ON e.evaluador_id = ev.id_usuario
            JOIN cargos ev_cargo ON ev.id_cargo = ev_cargo.id_cargo
            
            JOIN usuarios ed ON e.evaluado_id = ed.id_usuario
            JOIN ubicacion_fisica uf ON ed.id_uf = uf.id_uf
            JOIN cargos ed_cargo ON ed.id_cargo = ed_cargo.id_cargo
            JOIN organizaciones ed_org ON ed_cargo.id_org = ed_org.id_org

            WHERE e.id_eval_obreros = %d
              AND ed.cedula_usuario = '%s'

            LIMIT 1
        ", $idEval, addslashes($cedula));
    }

    // ============================================================
    // 2) ACTUALIZAR EVALUACIÓN (UPDATE REAL)
    // ============================================================
    public function sql_guardar_evaluacion(): string {
        return sprintf(
            "UPDATE evaluacion_obreros
             SET rango_id = %d,
                 puntaje_total = %d,
                 tiempo_puesto = %d
             WHERE evaluado_id = %d
             RETURNING id_eval_obreros;",
            $this->rango_id,
            $this->puntaje_total,
            $this->tiempo_puesto,
            $this->evaluado_id
        );
    }

    // ============================================================
    // 3) GUARDAR DETALLE DE CRITERIOS
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
