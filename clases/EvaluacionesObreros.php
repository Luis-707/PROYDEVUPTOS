<?php

class EvaluacionesObreros {

    private $conexion;

    private $id_eval_obreros = 0;
    private $evaluador_id = 0;
    private $evaluado_id = 0;
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluacion = "";
    private $estado_eval_obreros = "";

    // Nuevos campos necesarios para guardar evaluación
    private $rango_id = 0;
    private $puntaje_total = 0;
    private $tiempo_puesto = 0;

    public function __construct($dataCliente = array(), $conexion = NULL) {

        if (isset($dataCliente['id_eval_obreros'])) {
            $this->id_eval_obreros = intval($dataCliente['id_eval_obreros']);
        }
        if (isset($dataCliente['evaluador_id'])) {
            $this->id_usuario = intval($dataCliente['evaluador_id']);
        }
        if (isset($dataCliente['evaluado_id'])) {
            $this->evaluado_id = intval($dataCliente['evaluado_id']);
        }
        if (isset($dataCliente['fecha_inicio'])) {
            $this->fecha_inicio = $dataCliente['fecha_inicio'];
        }
        if (isset($dataCliente['fecha_cierre'])) {
            $this->fecha_cierre = $dataCliente['fecha_cierre'];
        }
        if (isset($dataCliente['periodo_evaluacion'])) {
            $this->periodo_evaluacion = $dataCliente['periodo_evaluacion'];
        }
        if (isset($dataCliente['estado_eval_obreros'])) {
            $this->estado_eval_obreros = $dataCliente['estado_eval_obreros'];
        }

        // Nuevos campos
        if (isset($dataCliente['rango_id'])) {
            $this->rango_id = intval($dataCliente['rango_id']);
        }
        if (isset($dataCliente['puntaje_total'])) {
            $this->puntaje_total = intval($dataCliente['puntaje_total']);
        }
        if (isset($dataCliente['tiempo_puesto'])) {
            $this->tiempo_puesto = intval($dataCliente['tiempo_puesto']);
        }

        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    // ============================================================
    // SQL: Datos de identificación de la planilla
    // ============================================================
    public static function sql_datos_planilla_obrero(string $cedula, int $idEval): string {
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
                e.evaluado_id AS id_evaluado,
                e.evaluador_id AS id_usuario_evaluador,
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
    // SQL PARA GUARDAR EVALUACIÓN (UPDATE)
    // ============================================================
    public function sql_guardar_eval_obreros(): string {
        return sprintf(
            "INSERT INTO evaluacion_obreros 
                (evaluador_id, evaluado_id, fecha_inicio, fecha_cierre, periodo_evaluacion, estado_eval_obreros)
             VALUES (%d, %d, '%s', '%s', '%s', '%s')
             RETURNING id_eval_obreros;",
            $this->evaluador_id,
            $this->evaluado_id,
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluacion),
            ('Iniciada')
        );
    }

    // ============================================================
    // SQL PARA GUARDAR DETALLE DE CRITERIOS
    // ============================================================
    public static function sql_guardar_detalle(int $idEval, array $c): string {
        return sprintf(
            "INSERT INTO detalles_evaluacion_obreros (id_eval_obreros, criterio_id, puntaje_obtenido)
             VALUES (%d, %d, %d);",
            $idEval,
            intval($c['criterio_id']),
            intval($c['puntaje_obtenido'])
        );
    }

    // ============================================================
    // SQL PARA LISTAR EVALUACIONES
    // ============================================================
    public function sql_listar_eval_obreros(): string {
        return "
            SELECT 
                u.cedula_usuario,
                u.nombre_completo,
                u.ubicacion_administrativa,
                c.cargo_evaluado,
                eo.estado_eval_obreros,
                eo.periodo_evaluacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio,
                eo.id_eval_obreros,
                eo.id_evaluado,
                eo.id_usuario
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            ORDER BY u.cedula_usuario;
        ";
    }
}

?>
