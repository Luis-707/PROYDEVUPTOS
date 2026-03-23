<?php

class ResultadosAdmin
{
    private $conexion;

    public function __construct($conexion = null)
    {
        $this->conexion = $conexion;
    }

    /* ============================
     * LISTADOS (para lista_resultados.php)
     * ============================ */

   public static function sql_listar_por_evaluador(int $idUsuarioEvaluador): string
{
    return sprintf("
        SELECT 
            ea.id_eval_admin,
            u_eval.cedula_usuario,
            u_eval.nombre_completo,
            cargo_eval.nombre_cargo AS cargo_evaluado,
            org_eval.nombre AS ubicacion_administrativa,
            ea.periodo_evaluado,
            EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,
            r.rango_actuacion,
            ea.puntaje_final
        FROM evaluacion_administrativos ea
        JOIN usuarios u_eval       ON u_eval.id_usuario = ea.evaluado_id
        JOIN cargos   cargo_eval   ON cargo_eval.id_cargo = u_eval.id_cargo
        JOIN organizaciones org_eval ON org_eval.id_org = cargo_eval.id_org
        JOIN rango_actuacion r ON r.id_rango = ea.id_rango
        WHERE ea.evaluador_id = %d
          AND ea.estado_eval_admin = 'Finalizada'
        ORDER BY u_eval.cedula_usuario ASC
    ", $idUsuarioEvaluador);
}


public static function sql_listar_por_supervisor(int $idUsuarioSupervisor): string
{
    return sprintf("
        SELECT 
            ea.id_eval_admin,
            u_eval.cedula_usuario,
            u_eval.nombre_completo,
            cargo_eval.nombre_cargo AS cargo_evaluado,
            org_eval.nombre AS ubicacion_administrativa,
            ea.periodo_evaluado,
            EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,
            r.rango_actuacion,
            ea.puntaje_final
        FROM evaluacion_administrativos ea
        JOIN usuarios u_eval        ON u_eval.id_usuario = ea.evaluado_id
        JOIN cargos   cargo_eval    ON cargo_eval.id_cargo = u_eval.id_cargo
        JOIN organizaciones org_eval ON org_eval.id_org = cargo_eval.id_org

        JOIN usuarios u_ev       ON u_ev.id_usuario = ea.evaluador_id
        JOIN cargos   cargo_ev   ON cargo_ev.id_cargo = u_ev.id_cargo
        JOIN organizaciones org_ev ON org_ev.id_org = cargo_ev.id_org

        JOIN usuarios u_sup       ON u_sup.id_usuario = %d
        JOIN cargos   cargo_sup   ON cargo_sup.id_cargo = u_sup.id_cargo
        JOIN organizaciones org_sup ON org_sup.id_org = cargo_sup.id_org

        JOIN rango_actuacion r ON r.id_rango = ea.id_rango

        WHERE org_ev.padre_id = org_sup.id_org
          AND ea.estado_eval_admin = 'Finalizada'
        ORDER BY u_eval.cedula_usuario ASC
    ", $idUsuarioSupervisor);
}
    public function listarResultados(string $sql)
    {
        if ($this->conexion !== null) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return [];
    }

    /* ============================
     * DETALLE PLANILLA RESULTADOS
     * ============================ */

    // Evaluación (cabecera + resultado final)
    public static function sql_evaluacion_detalle(int $idEvalAdmin): string
    {
        return sprintf("
            SELECT 
                ea.id_eval_admin,
                ea.evaluado_id,
                ea.evaluador_id,
                ea.puntaje_final,
                ea.periodo_evaluado,
                ea.comentario_supervisor,
                ea.comentario_evaluado,
                ea.conformidad,
                r.rango_actuacion
            FROM evaluacion_administrativos ea
            JOIN rango_actuacion r ON r.id_rango = ea.id_rango
            WHERE ea.id_eval_admin = %d
              AND ea.estado_eval_admin = 'Finalizada'
            LIMIT 1
        ", $idEvalAdmin);
    }

    // Relaciones: evaluado, evaluador, supervisor (por jerarquía organizacional)
    public static function sql_relaciones_resultados(int $idEvalAdmin): string
    {
        return sprintf("
            SELECT 
                -- Evaluado
                u_eval.cedula_usuario              AS cedula_usuario,
                u_eval.nombre_completo             AS nombre_completo_evaluado,
                cargo_eval.nombre_cargo            AS cargo_evaluado,
                org_eval.nombre                    AS ubicacion_evaluado,

                -- Evaluador
                u_ev.cedula_usuario                AS cedula_evaluador,
                u_ev.nombre_completo               AS nombre_completo_evaluador,
                cargo_ev.nombre_cargo              AS cargo_evaluador,
                org_ev.nombre                      AS ubicacion_evaluador,

                -- Supervisor (derivado de la jerarquía)
                u_sup.cedula_usuario               AS cedula_supervisor,
                u_sup.nombre_completo              AS nombre_completo_supervisor,
                cargo_sup.nombre_cargo             AS cargo_supervisor

            FROM evaluacion_administrativos ea

            -- Evaluado
            JOIN usuarios u_eval        ON u_eval.id_usuario = ea.evaluado_id
            JOIN cargos   cargo_eval    ON cargo_eval.id_cargo = u_eval.id_cargo
            JOIN organizaciones org_eval ON org_eval.id_org = cargo_eval.id_org

            -- Evaluador
            JOIN usuarios u_ev       ON u_ev.id_usuario = ea.evaluador_id
            JOIN cargos   cargo_ev   ON cargo_ev.id_cargo = u_ev.id_cargo
            JOIN organizaciones org_ev ON org_ev.id_org = cargo_ev.id_org

            -- Supervisor (organización padre del evaluador)
            JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
            JOIN cargos   cargo_sup     ON cargo_sup.id_org = org_sup.id_org
            JOIN usuarios u_sup         ON u_sup.id_cargo = cargo_sup.id_cargo

            WHERE ea.id_eval_admin = %d
            LIMIT 1
        ", $idEvalAdmin);
    }

    // Objetivos
    public static function sql_objetivos_resultados(int $idEvalAdmin): string
    {
        return sprintf("
            SELECT 
                odi.id_odi,
                odi.nombre_objetivo,
                odi.peso_objetivo,
                eo.rango_obj,
                eo.pesoxrango_obj
            FROM evaluacion_objetivos eo
            JOIN objetivos_desempeno_individual odi 
                  ON eo.id_odi = odi.id_odi
            WHERE eo.id_eval_admin = %d
            ORDER BY eo.id_obj_result ASC
        ", $idEvalAdmin);
    }

    // Competencias
    public static function sql_competencias_resultados(int $idEvalAdmin): string
    {
        return sprintf("
            SELECT 
                ec.id_comp_result,
                ec.id_competencia,
                c.nombre_competencia,
                c.peso_competencia,
                ec.rango_comp,
                ec.pesoxrango_comp
            FROM evaluacion_competencias ec
            JOIN competencias c ON ec.id_competencia = c.id_competencia
            WHERE ec.id_eval_admin = %d
        ", $idEvalAdmin);
    }

    public function ejecutar(string $sql)
    {
        if ($this->conexion !== null) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return [];
    }
}
