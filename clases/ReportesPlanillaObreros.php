<?php

class ReportesPlanillaObreros {

    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // ============================================================
    // LISTAR EVALUACIONES APTAS PARA REPORTE
    // ============================================================
    public static function sql_listar_reportes(): string {
        return "
            SELECT eo.id_eval_obreros,
                   u.cedula_usuario,
                   u.nombre_completo,
                   c.cargo_evaluado,
                   eo.periodo_evaluacion,
                   eo.comentario_supervisor,
                   eo.comentario_evaluado,
                   eo.conformidad,
                   EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            WHERE TRIM(eo.comentario_supervisor) <> ''
              AND TRIM(eo.comentario_evaluado) <> ''
              AND TRIM(eo.conformidad) <> '';
        ";
    }

    public function listarReportesObreros() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_reportes());
        }
        return [];
    }

    // ============================================================
    // DATOS GENERALES DE LA EVALUACIÓN OBRERA
    // ============================================================
    public static function sql_datos_evaluacion(int $idEvalObrero): string {
        return sprintf("
            SELECT eo.id_eval_obreros,
                   eo.periodo_evaluacion,
                   eo.fecha_inicio,
                   eo.fecha_cierre,
                   eo.puntaje_total,
                   eo.rango_id,
                   r.nombre_rango,
                   eo.comentario_supervisor,
                   eo.comentario_evaluado,
                   eo.conformidad,

                   -- Evaluado
                   u_eval.cedula_usuario AS cedula_evaluado,
                   u_eval.nombre_completo AS nombre_evaluado,
                   c_ev.cargo_evaluado,
                   u_eval.ubicacion_administrativa AS ubicacion_evaluado,
                   uf.nombre_uf AS ubicacion_fisica,
                   ao.nombre_ao AS area_ocupacional,
                   eo.tiempo_puesto,

                   -- Evaluador
                   u_ev.cedula_usuario AS cedula_evaluador,
                   u_ev.nombre_completo AS nombre_evaluador,
                   c_ee.cargo_evaluador,
                   u_ev.ubicacion_administrativa AS ubicacion_evaluador

            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u_eval ON e.id_usuario = u_eval.id_usuario
            JOIN cargos_evaluados c_ev ON e.id_cargo_evaluado = c_ev.id_cargo_evaluado
            JOIN ubicacion_fisica uf ON e.id_uf = uf.id_uf
            JOIN area_ocupacional ao ON e.id_ao = ao.id_ao

            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            JOIN cargos_evaluadores c_ee ON ev.id_cargo_evaluador = c_ee.id_cargo_evaluador

            JOIN rangos_calificacion r ON eo.rango_id = r.rango_id

            WHERE eo.id_eval_obreros = %d;
        ", $idEvalObrero);
    }

    // ============================================================
    // FACTORES COMPLETOS
    // ============================================================
    public static function sql_factores(): string {
        return "
            SELECT factor_id, nombre_factor, valor_factor
            FROM factores
            ORDER BY factor_id;
        ";
    }

    // ============================================================
    // CRITERIOS COMPLETOS
    // ============================================================
    public static function sql_criterios(): string {
        return "
            SELECT criterio_id, factor_id, codigo_criterio, descripcion_criterio, valor_criterio
            FROM criterios
            ORDER BY factor_id, criterio_id;
        ";
    }

    // ============================================================
    // CRITERIOS SELECCIONADOS
    // ============================================================
    public static function sql_criterios_seleccionados(int $idEvalObrero): string {
        return sprintf("
            SELECT criterio_id, puntaje_obtenido
            FROM detalles_evaluacion_obreros
            WHERE id_eval_obreros = %d;
        ", $idEvalObrero);
    }

    public function ejecutarConsulta(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return [];
    }
}

