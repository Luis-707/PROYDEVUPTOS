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
    public static function sql_listar_reportes_filtrado(int $idUsuarioSesion, array $roles): string {

        // ADMINISTRADOR → ve todo
        if (in_array("administrador", $roles)) {
            return self::sql_listar_reportes();
        }
    
        // EVALUADOR → ve solo sus evaluaciones
        if (in_array("evaluador", $roles)) {
            return sprintf("
                SELECT 
                    eo.id_eval_obreros,
                    u.cedula_usuario,
                    u.nombre_completo,
                    c.nombre_cargo AS cargo_evaluado,
                    eo.periodo_evaluacion,
                    eo.comentario_supervisor,
                    eo.comentario_evaluado,
                    eo.conformidad,
                    EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
                FROM evaluacion_obreros eo
                JOIN usuarios u ON u.id_usuario = eo.evaluado_id
                JOIN cargos c ON c.id_cargo = u.id_cargo
                WHERE eo.evaluador_id = %d
                  AND TRIM(eo.comentario_supervisor) <> ''
                  AND TRIM(eo.comentario_evaluado) <> ''
                  AND TRIM(eo.conformidad) <> '';
            ", $idUsuarioSesion);
        }
    
        // SUPERVISOR DEL EVALUADOR → ve evaluaciones de sus subordinados
        if (in_array("supervisor del evaluador", $roles)) {
            return sprintf("
                SELECT 
                    eo.id_eval_obreros,
                    u.cedula_usuario,
                    u.nombre_completo,
                    c.nombre_cargo AS cargo_evaluado,
                    eo.periodo_evaluacion,
                    eo.comentario_supervisor,
                    eo.comentario_evaluado,
                    eo.conformidad,
                    EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
                FROM evaluacion_obreros eo
                JOIN usuarios u ON u.id_usuario = eo.evaluado_id
                JOIN cargos c ON c.id_cargo = u.id_cargo
                JOIN organizaciones org_eval ON org_eval.id_org = c.id_org
                JOIN usuarios u_ev ON u_ev.id_usuario = eo.evaluador_id
                JOIN cargos c_ev ON c_ev.id_cargo = u_ev.id_cargo
                JOIN organizaciones org_ev ON org_ev.id_org = c_ev.id_org
                JOIN usuarios u_sup ON u_sup.id_usuario = %d
                JOIN cargos c_sup ON c_sup.id_cargo = u_sup.id_cargo
                JOIN organizaciones org_sup ON org_sup.id_org = c_sup.id_org
    
                WHERE org_ev.padre_id = org_sup.id_org
                  AND TRIM(eo.comentario_supervisor) <> ''
                  AND TRIM(eo.comentario_evaluado) <> ''
                  AND TRIM(eo.conformidad) <> '';
            ", $idUsuarioSesion);
        }
    
        // Si no tiene roles válidos → no ve nada
        return "SELECT * FROM evaluacion_obreros WHERE 1=0;";
    }

    public function listarReportesObrerosFiltrado(int $idUsuarioSesion, array $roles) {
        $sql = self::sql_listar_reportes_filtrado($idUsuarioSesion, $roles);
        return $this->conexion->ejecutarConsultaBdds($sql);
    }

    // ============================================================
    // DATOS GENERALES DE LA EVALUACIÓN OBRERA
    // ============================================================
    public static function sql_datos_evaluacion(int $idEvalObrero): string {
        return sprintf("
           SELECT 
                eo.id_eval_obreros,
                eo.periodo_evaluacion,
                eo.fecha_inicio,
                eo.fecha_cierre,
                eo.puntaje_total,
                eo.rango_id,
                r.nombre_rango,
                eo.comentario_supervisor,
                eo.comentario_evaluado,
                eo.conformidad,

                u_eval.cedula_usuario AS cedula_evaluado,
                u_eval.nombre_completo AS nombre_evaluado,
                c_eval.nombre_cargo AS cargo_evaluado,
                org_eval.nombre AS area_ocupacional,
                uf.nombre_ubicacion AS ubicacion_fisica,
                u_eval.ubicacion_administrativa AS ubicacion_evaluado,
                eo.tiempo_puesto,
                u_eval.fecha_ingreso,
                u_ev.cedula_usuario AS cedula_evaluador,
                u_ev.nombre_completo AS nombre_evaluador,
                c_ev.nombre_cargo AS cargo_evaluador,
                org_ev.nombre AS ubicacion_evaluador

            FROM evaluacion_obreros eo

            JOIN usuarios u_eval ON u_eval.id_usuario = eo.evaluado_id
            JOIN cargos c_eval ON c_eval.id_cargo = u_eval.id_cargo
            JOIN organizaciones org_eval ON org_eval.id_org = c_eval.id_org
            JOIN ubicacion_fisica uf ON uf.id_uf = u_eval.id_uf
            JOIN usuarios u_ev ON u_ev.id_usuario = eo.evaluador_id
            JOIN cargos c_ev ON c_ev.id_cargo = u_ev.id_cargo
            JOIN organizaciones org_ev ON org_ev.id_org = c_ev.id_org

            JOIN rangos_calificacion r ON r.rango_id = eo.rango_id

            WHERE eo.id_eval_obreros = %d
            LIMIT 1;
        ", $idEvalObrero);
    }

    // ============================================================
    // FACTORES
    // ============================================================
    public static function sql_factores(): string {
        return "
            SELECT factor_id, nombre_factor, valor_factor
            FROM factores
            ORDER BY factor_id;
        ";
    }

    // ============================================================
    // CRITERIOS
    // ============================================================
    public static function sql_criterios(): string {
        return "
            SELECT criterio_id, factor_id, codigo_criterio, descripcion_criterio, valor_criterio
            FROM criterios
            ORDER BY factor_id, criterio_id;
        ";
    }

    // ============================================================
    // SELECCIONADOS
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

?>
