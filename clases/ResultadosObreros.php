<?php

class ResultadosObreros
{
    private $conexion;

    public function __construct($conexion = null)
    {
        $this->conexion = $conexion;
    }

    /* ============================================================
     * LISTADOS
     * ============================================================ */

    public static function sql_listar_por_evaluador(int $idUsuarioEvaluador): string
    {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u.cedula_usuario,
                u.nombre_completo,
                co.nombre_cargo AS cargo_obrero,
                org.nombre AS ubicacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
            FROM evaluacion_obreros eo
            JOIN usuarios u ON u.id_usuario = eo.evaluado_id
            JOIN cargos co ON co.id_cargo = u.id_cargo
            JOIN organizaciones org ON org.id_org = co.id_org
            WHERE eo.evaluador_id = %d
              AND eo.estado_eval_obrero = 'Finalizada'
            ORDER BY u.cedula_usuario ASC
        ", $idUsuarioEvaluador);
    }

    public static function sql_listar_por_supervisor(int $idUsuarioSupervisor): string
    {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u.cedula_usuario,
                u.nombre_completo,
                co.nombre_cargo AS cargo_obrero,
                org.nombre AS ubicacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
            FROM evaluacion_obreros eo
            JOIN usuarios u ON u.id_usuario = eo.evaluado_id
            JOIN cargos co ON co.id_cargo = u.id_cargo
            JOIN organizaciones org ON org.id_org = co.id_org

            JOIN usuarios u_ev ON u_ev.id_usuario = eo.evaluador_id
            JOIN cargos co_ev ON co_ev.id_cargo = u_ev.id_cargo
            JOIN organizaciones org_ev ON org_ev.id_org = co_ev.id_org

            JOIN usuarios u_sup ON u_sup.id_usuario = %d
            JOIN cargos co_sup ON co_sup.id_cargo = u_sup.id_cargo
            JOIN organizaciones org_sup ON org_sup.id_org = co_sup.id_org

            WHERE org_ev.padre_id = org_sup.id_org
              AND eo.estado_eval_obrero = 'Finalizada'
            ORDER BY u.cedula_usuario ASC
        ", $idUsuarioSupervisor);
    }

    public function listarResultados(string $sql)
    {
        return $this->conexion->ejecutarConsultaBdds($sql);
    }

    /* ============================================================
     * DETALLE PLANILLA RESULTADOS
     * ============================================================ */

    public static function sql_evaluacion_detalle(int $idEval): string
    {
        return "
            SELECT 
                o.id_eval_obreros,
                o.evaluado_id,
                o.evaluador_id,
                o.puntaje_total,
                r.nombre_rango,
                o.comentario_supervisor,
                o.comentario_evaluado,
                o.conformidad
            FROM evaluacion_obreros o
            JOIN rangos_calificacion r ON r.rango_id = o.rango_id
            WHERE id_eval_obreros = $idEval
              AND estado_eval_obrero = 'Finalizada'
            LIMIT 1;
        ";
    }

    public static function sql_relaciones_resultados(int $idEval): string
    {
        return "
           SELECT 
                u_eval.nombre_completo AS nombre_evaluado,
                u_eval.cedula_usuario AS cedula_evaluado,
                co_eval.nombre_cargo AS cargo_evaluado,
                u_eval.fecha_ingreso,
                eo.tiempo_puesto,
                u_eval.ubicacion_administrativa AS ubicacion_evaluado,
                org_eval.nombre AS area_ocupacional,
                uf.nombre_ubicacion AS ubicacion_fisica,

                u_ev.nombre_completo AS nombre_evaluador,
                u_ev.cedula_usuario AS cedula_evaluador,
                org_ev.nombre AS ubicacion_evaluador,

                u_sup.nombre_completo AS nombre_supervisor,
                u_sup.cedula_usuario AS cedula_supervisor,
                co_sup.nombre_cargo AS cargo_supervisor

            FROM evaluacion_obreros eo
            JOIN usuarios u_eval ON u_eval.id_usuario = eo.evaluado_id
            JOIN ubicacion_fisica uf ON u_eval.id_uf = uf.id_uf
            JOIN cargos co_eval ON co_eval.id_cargo = u_eval.id_cargo
            JOIN organizaciones org_eval ON org_eval.id_org = co_eval.id_org

            JOIN usuarios u_ev ON u_ev.id_usuario = eo.evaluador_id
            JOIN cargos co_ev ON co_ev.id_cargo = u_ev.id_cargo
            JOIN organizaciones org_ev ON org_ev.id_org = co_ev.id_org

            JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
            JOIN cargos co_sup ON co_sup.id_org = org_sup.id_org
            JOIN usuarios u_sup ON u_sup.id_cargo = co_sup.id_cargo

            WHERE eo.id_eval_obreros = $idEval
            LIMIT 1;
        ";
    }

    /* ============================================================
     * FACTORES + CRITERIOS + SELECCIONADOS
     * ============================================================ */

    public static function sql_factores(): string {
        return "
            SELECT factor_id, nombre_factor, valor_factor
            FROM factores
            ORDER BY factor_id;
        ";
    }

    public static function sql_criterios(): string {
        return "
            SELECT criterio_id, factor_id, codigo_criterio, descripcion_criterio
            FROM criterios
            ORDER BY factor_id, criterio_id;
        ";
    }

    public static function sql_seleccionados(int $idEval): string {
        return "
            SELECT criterio_id, puntaje_obtenido
            FROM detalles_evaluacion_obreros
            WHERE id_eval_obreros = $idEval;
        ";
    }

    public function ejecutar(string $sql)
    {
        return $this->conexion->ejecutarConsultaBdds($sql);
    }
}