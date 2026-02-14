<?php

class ReportesPlanillaAdmin {
    private $conexion;

    public function __construct($conexion = null) {
        if ($conexion !== null) {
            $this->conexion = $conexion;
        }
    }

    // ============================================================
    // 1) Datos completos de la evaluación (ADMIN + EXCEPCIONAL)
    // ============================================================
    public static function sql_datos_combinados(int $idEvalAdmin): string {
        return sprintf("
            SELECT 
                -- Datos de la evaluación
                ea.id_eval_admin,
                ea.periodo_evaluado,
                ea.fecha_inicio,
                ea.fecha_cierre,
                ea.puntaje_final,
                r.rango_actuacion,
                ea.comentario_supervisor,
                ea.comentario_evaluado,
                ea.conformidad,

                -- Evaluado
                u_eval.nombre_completo AS nombre_evaluado,
                u_eval.cedula_usuario AS cedula_evaluado,
                cargo_eval.nombre_cargo AS cargo_evaluado,
                org_eval.nombre AS ubicacion_evaluado,

                -- Evaluador
                u_ev.nombre_completo AS nombre_evaluador,
                u_ev.cedula_usuario AS cedula_evaluador,
                cargo_ev.nombre_cargo AS cargo_evaluador,
                org_ev.nombre AS ubicacion_evaluador,

                -- Supervisor (jefe de la organización del evaluador)
                u_sup.nombre_completo AS nombre_supervisor,
                u_sup.cedula_usuario AS cedula_supervisor,
                cargo_sup.nombre_cargo AS cargo_supervisor,

                -- Desempeño excepcional (si existe)
                de.id_desemp_excepcional,
                de.periodo AS periodo_excep,
                de.fecha AS fecha_excep

            FROM evaluacion_administrativos ea

            -- Evaluado
            JOIN usuarios u_eval ON ea.evaluado_id = u_eval.id_usuario
            JOIN cargos cargo_eval ON u_eval.id_cargo = cargo_eval.id_cargo
            JOIN organizaciones org_eval ON cargo_eval.id_org = org_eval.id_org

            -- Evaluador
            JOIN usuarios u_ev ON ea.evaluador_id = u_ev.id_usuario
            JOIN cargos cargo_ev ON u_ev.id_cargo = cargo_ev.id_cargo
            JOIN organizaciones org_ev ON cargo_ev.id_org = org_ev.id_org

            -- Supervisor (organización padre)
            LEFT JOIN organizaciones org_padre ON org_ev.padre_id = org_padre.id_org
            LEFT JOIN cargos cargo_sup ON cargo_sup.id_org = org_padre.id_org AND cargo_sup.es_jefe = true
            LEFT JOIN usuarios u_sup ON u_sup.id_cargo = cargo_sup.id_cargo AND u_sup.estado_usuario = 'Activo'

            -- Rango
            JOIN rango_actuacion r ON ea.id_rango = r.id_rango

            -- Desempeño excepcional
            LEFT JOIN desempeno_excepcional de ON de.id_eval_admin = ea.id_eval_admin

            WHERE ea.id_eval_admin = %d
            LIMIT 1;
        ", $idEvalAdmin);
    }

    // ============================================================
    // 2) Datos administrativos sin desempeño excepcional
    // ============================================================
    public static function sql_datos_evaluacion(int $idEvalAdmin): string {
        return sprintf("
            SELECT 
                ea.id_eval_admin,
                ea.periodo_evaluado,
                ea.fecha_inicio,
                ea.fecha_cierre,
                ea.puntaje_final,
                r.rango_actuacion,
                ea.comentario_supervisor,
                ea.comentario_evaluado,
                ea.conformidad,

                -- Evaluado
                u_eval.nombre_completo AS nombre_evaluado,
                u_eval.cedula_usuario AS cedula_evaluado,
                cargo_eval.nombre_cargo AS cargo_evaluado,
                org_eval.nombre AS ubicacion_evaluado,

                -- Evaluador
                u_ev.nombre_completo AS nombre_evaluador,
                u_ev.cedula_usuario AS cedula_evaluador,
                cargo_ev.nombre_cargo AS cargo_evaluador,
                org_ev.nombre AS ubicacion_evaluador,

                -- Supervisor
                u_sup.nombre_completo AS nombre_supervisor,
                u_sup.cedula_usuario AS cedula_supervisor,
                cargo_sup.nombre_cargo AS cargo_supervisor

            FROM evaluacion_administrativos ea

            JOIN usuarios u_eval ON ea.evaluado_id = u_eval.id_usuario
            JOIN cargos cargo_eval ON u_eval.id_cargo = cargo_eval.id_cargo
            JOIN organizaciones org_eval ON cargo_eval.id_org = org_eval.id_org

            JOIN usuarios u_ev ON ea.evaluador_id = u_ev.id_usuario
            JOIN cargos cargo_ev ON u_ev.id_cargo = cargo_ev.id_cargo
            JOIN organizaciones org_ev ON cargo_ev.id_org = org_ev.id_org

            LEFT JOIN organizaciones org_padre ON org_ev.padre_id = org_padre.id_org
            LEFT JOIN cargos cargo_sup ON cargo_sup.id_org = org_padre.id_org AND cargo_sup.es_jefe = true
            LEFT JOIN usuarios u_sup ON u_sup.id_cargo = cargo_sup.id_cargo AND u_sup.estado_usuario = 'Activo'

            JOIN rango_actuacion r ON ea.id_rango = r.id_rango

            WHERE ea.id_eval_admin = %d
            LIMIT 1;
        ", $idEvalAdmin);
    }

    // ============================================================
    // 3) Objetivos asociados
    // ============================================================
    public static function sql_objetivos(int $idEvalAdmin): string {
        return sprintf("
            SELECT 
                o.nombre_objetivo,
                o.peso_objetivo,
                eo.rango_obj,
                eo.pesoxrango_obj
            FROM evaluacion_objetivos eo
            JOIN objetivos_desempeno_individual o ON o.id_odi = eo.id_odi
            WHERE eo.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // ============================================================
    // 4) Competencias asociadas
    // ============================================================
    public static function sql_competencias(int $idEvalAdmin): string {
        return sprintf("
            SELECT 
                c.nombre_competencia,
                c.peso_competencia,
                ec.rango_comp,
                ec.pesoxrango_comp
            FROM evaluacion_competencias ec
            JOIN competencias c ON c.id_competencia = ec.id_competencia
            WHERE ec.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // ============================================================
    // 5) Listar evaluaciones disponibles para reporte
    // ============================================================
    public static function sql_listar_reportes(): string {
        return "
            SELECT 
                ea.id_eval_admin,
                u_eval.cedula_usuario,
                u_eval.nombre_completo,
                cargo_eval.nombre_cargo AS cargo_evaluado,
                ea.periodo_evaluado,
                ea.conformidad,
                EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,
                ea.comentario_supervisor,
                ea.comentario_evaluado
            FROM evaluacion_administrativos ea
            JOIN usuarios u_eval ON ea.evaluado_id = u_eval.id_usuario
            JOIN cargos cargo_eval ON u_eval.id_cargo = cargo_eval.id_cargo
            WHERE TRIM(ea.comentario_supervisor) <> ''
              AND TRIM(ea.comentario_evaluado) <> ''
              AND TRIM(ea.conformidad) <> '';
        ";
    }

    public function listarReportesAdmin() {
        if ($this->conexion !== null) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_reportes());
        }
        return [];
    }

    public function ejecutarConsulta(string $sql) {
        if ($this->conexion !== null) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return [];
    }
}
?>