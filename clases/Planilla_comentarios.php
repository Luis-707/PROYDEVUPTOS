<?php

class Planilla_comentarios {

    private $conexion;
    private $cedula_usuario = "";
    private $comentario_supervisor = "";
    private $comentario_evaluado = "";
    private $conformidad = "";
    private $id_eval_admin = 0;

    public function __construct($dataCliente = [], $conexion = NULL) {

        if (!empty($dataCliente)) {

            if (isset($dataCliente['cedula_usuario'])) {
                $this->cedula_usuario = trim($dataCliente['cedula_usuario']);
            }

            if (isset($dataCliente['id_eval_admin'])) {
                $this->id_eval_admin = (int)$dataCliente['id_eval_admin'];
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
        }

        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public function getIdEvalAdmin(): int {
        return $this->id_eval_admin;
    }

    public function setIdEvalAdmin(int $id): void {
        $this->id_eval_admin = $id;
    }
    // ============================================================
    // 1. VALIDAR QUE EL USUARIO ES EL EVALUADO
    // ============================================================
    public function sql_buscar_por_id_y_evaluado(string $cedula): string {
        return sprintf("
            SELECT ea.id_eval_admin
            FROM evaluacion_administrativos ea
            JOIN usuarios u ON u.id_usuario = ea.evaluado_id
            WHERE ea.id_eval_admin = %d
              AND u.cedula_usuario = '%s'
            LIMIT 1;
        ",
        $this->id_eval_admin,
        addslashes($cedula));
    }

    // ============================================================
    // 2. VALIDAR QUE EL USUARIO ES SUPERVISOR DEL EVALUADOR
    // ============================================================
    public function sql_buscar_por_id_y_supervisor(string $cedula): string {
        return sprintf("
            SELECT ea.id_eval_admin
            FROM evaluacion_administrativos ea
            JOIN usuarios ev       ON ev.id_usuario   = ea.evaluador_id
            JOIN cargos   c_ev     ON c_ev.id_cargo   = ev.id_cargo
            JOIN organizaciones org_ev  ON org_ev.id_org = c_ev.id_org

            JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
            JOIN cargos   c_sup    ON c_sup.id_org    = org_sup.id_org
            JOIN usuarios sup      ON sup.id_cargo    = c_sup.id_cargo

            WHERE ea.id_eval_admin = %d
              AND sup.cedula_usuario = '%s'
            LIMIT 1;
        ",
        $this->id_eval_admin,
        addslashes($cedula));
    }

    // ============================================================
    // 3. RELACIONES (evaluado, evaluador, supervisor)
    // ============================================================
    public static function sql_relaciones_por_cedula(string $cedula): string {
        return sprintf("
            SELECT 
                u_eval.cedula_usuario   AS cedula_evaluado,
                u_eval.nombre_completo  AS nombre_evaluado,
                c_eval.nombre_cargo     AS cargo_evaluado,
                org_eval.nombre         AS unidad_evaluado,
                u_ev.cedula_usuario     AS cedula_evaluador,
                u_ev.nombre_completo    AS nombre_evaluador,
                c_ev.nombre_cargo       AS cargo_evaluador,
                org_ev.nombre           AS unidad_evaluador,
                u_sup.cedula_usuario    AS cedula_supervisor,
                u_sup.nombre_completo   AS nombre_supervisor,
                c_sup.nombre_cargo      AS cargo_supervisor,
                org_sup.nombre          AS unidad_supervisor

            FROM usuarios u_eval
            JOIN cargos c_eval        ON c_eval.id_cargo = u_eval.id_cargo
            JOIN organizaciones org_eval ON org_eval.id_org = c_eval.id_org

            JOIN evaluacion_administrativos ea ON ea.evaluado_id = u_eval.id_usuario

            JOIN usuarios u_ev        ON u_ev.id_usuario = ea.evaluador_id
            JOIN cargos c_ev          ON c_ev.id_cargo   = u_ev.id_cargo
            JOIN organizaciones org_ev ON org_ev.id_org  = c_ev.id_org

            JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
            JOIN cargos c_sup          ON c_sup.id_org   = org_sup.id_org
            JOIN usuarios u_sup        ON u_sup.id_cargo = c_sup.id_cargo

            WHERE u_eval.cedula_usuario = '%s'
            LIMIT 1;
        ",
        addslashes($cedula));
    }

    // ============================================================
    // 4. DATOS DE LA EVALUACIÓN
    // ============================================================
    public function sql_buscar(): string {
        return sprintf("
            SELECT 
                ea.*,
                u_eval.cedula_usuario AS cedula_evaluado,
                u_ev.cedula_usuario   AS cedula_evaluador,
                u_sup.cedula_usuario  AS cedula_supervisor,
                r.rango_actuacion AS rango_actuacion
            FROM evaluacion_administrativos ea

            JOIN usuarios u_eval ON u_eval.id_usuario = ea.evaluado_id

            JOIN usuarios u_ev   ON u_ev.id_usuario   = ea.evaluador_id
            JOIN cargos c_ev     ON c_ev.id_cargo     = u_ev.id_cargo
            JOIN organizaciones org_ev  ON org_ev.id_org = c_ev.id_org
            JOIN rango_actuacion r ON ea.id_rango = r.id_rango

            JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
            JOIN cargos c_sup     ON c_sup.id_org     = org_sup.id_org
            JOIN usuarios u_sup   ON u_sup.id_cargo   = c_sup.id_cargo

            WHERE ea.id_eval_admin = %d
            LIMIT 1;
        ",
        $this->id_eval_admin);
    }

    // ============================================================
    // 5. OBJETIVOS
    // ============================================================
    public static function sql_objetivos_por_cedula(string $cedula, int $idEvalAdmin): string {
        return sprintf("
            SELECT 
                odi.id_odi,
                odi.nombre_objetivo,
                odi.peso_objetivo,
                eo.rango_obj,
                eo.pesoxrango_obj
            FROM evaluacion_objetivos eo
            JOIN objetivos_desempeno_individual odi ON odi.id_odi = eo.id_odi
            JOIN evaluacion_administrativos ea ON ea.id_eval_admin = eo.id_eval_admin
            JOIN usuarios u ON u.id_usuario = ea.evaluado_id
            WHERE u.cedula_usuario = '%s'
              AND ea.id_eval_admin = %d
            ORDER BY odi.id_odi ASC;
        ",
        addslashes($cedula),
        $idEvalAdmin);
    }

    // ============================================================
    // 6. COMPETENCIAS
    // ============================================================
    public static function sql_competencias(int $idEvalAdmin): string {
        return "
            SELECT 
                ec.id_comp_result,
                ec.id_competencia,
                c.nombre_competencia,
                c.peso_competencia,
                ec.rango_comp,
                ec.pesoxrango_comp
            FROM evaluacion_competencias ec
            JOIN competencias c ON c.id_competencia = ec.id_competencia
            WHERE ec.id_eval_admin = $idEvalAdmin;
        ";
    }

    // ============================================================
    // 7. ACTUALIZAR COMENTARIO DEL SUPERVISOR
    // ============================================================
    public function sql_update_comentario_supervisor(): string {
        return sprintf("
            UPDATE evaluacion_administrativos
            SET comentario_supervisor = '%s'
            WHERE id_eval_admin = %d
            RETURNING id_eval_admin;
        ",
        addslashes($this->comentario_supervisor),
        $this->id_eval_admin);
    }

    // ============================================================
    // 8. ACTUALIZAR COMENTARIO DEL EVALUADO
    // ============================================================
    public function sql_update_comentario_evaluado(): string {
        return sprintf("
            UPDATE evaluacion_administrativos
            SET comentario_evaluado = '%s',
                conformidad = '%s'
            WHERE id_eval_admin = %d
            RETURNING id_eval_admin;
        ",
        addslashes($this->comentario_evaluado),
        addslashes($this->conformidad),
        $this->id_eval_admin);
    }


    public function getCedulaUsuario(): string {
        return $this->cedula_usuario;
    }

}