<?php

class Planilla_comentarios {
    private $conexion;
    private $cedula_usuario = "";
    private $comentario_supervisor;
    private $comentario_evaluado;
    private $conformidad = "";
    private $id_eval_admin = 0;

    public function __construct($dataCliente=array(''), $conexion = NULL) {
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

    // =============================
    // Consulta de relaciones de personal (evaluado, evaluador, supervisor)
    // =============================
    public static function sql_relaciones_por_cedula(string $cedula): string {
        return sprintf(
            "SELECT 
                e.id_evaluado AS id_evaluado,
                u_evaluado.cedula_usuario AS cedula_usuario,
                c_ev.cargo_evaluado AS cargo_evaluado,
    
                u_evaluador.id_usuario AS id_usuario_evaluador,
                u_evaluador.cedula_usuario AS cedula_evaluador,
                c_ee.cargo_evaluador AS cargo_evaluador,
    
                u_supervisor.cedula_usuario AS cedula_supervisor,
                c_es.cargo_supervisor AS cargo_supervisor
             FROM evaluados e
             JOIN usuarios u_evaluado ON e.id_usuario = u_evaluado.id_usuario
             JOIN cargos_evaluados c_ev ON e.id_cargo_evaluado = c_ev.id_cargo_evaluado
             JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
             JOIN usuarios u_evaluador ON ev.id_usuario = u_evaluador.id_usuario
             JOIN cargos_evaluadores c_ee ON ev.id_cargo_evaluador = c_ee.id_cargo_evaluador
             JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
             JOIN usuarios u_supervisor ON s.id_usuario = u_supervisor.id_usuario
             JOIN cargos_supervisores c_es ON s.id_cargo_supervisor = c_es.id_cargo_supervisor
             WHERE u_evaluado.cedula_usuario = '%s'
             LIMIT 1;",
            addslashes($cedula)
        );
    }
   
    // =============================
    // Consulta: Buscar evaluación por cédula
    // =============================
    public function sql_buscar(): string {
        return sprintf(
            "SELECT ea.id_eval_admin, ea.id_usuario, ea.id_evaluado,
                    ea.id_rango, ea.puntaje_final,
                    ea.fecha_inicio, ea.fecha_cierre, ea.periodo_evaluado,
                    u_eval.cedula_usuario AS cedula_evaluado,
                    u_ev.cedula_usuario   AS cedula_evaluador,
                    u_sup.cedula_usuario  AS cedula_supervisor,
                    r.rango_actuacion AS rango_actuacion
             FROM evaluacion_administrativos ea
             JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
             JOIN usuarios u_eval ON e.id_usuario = u_eval.id_usuario
             JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
             JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
             JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
             JOIN usuarios u_sup ON s.id_usuario = u_sup.id_usuario
             JOIN rango_actuacion r ON ea.id_rango = r.id_rango
             WHERE u_eval.cedula_usuario = '%s'
             ORDER BY ea.fecha_cierre DESC
             LIMIT 1;",
            addslashes($this->cedula_usuario)
        );
    }
    public function buscarEvaluacion() {
        if ($this->conexion != null && $this->cedula_usuario !== "") {
            return $this->conexion->ejecutarConsultaBdds($this->sql_buscar());
        }
        return [];
    }

    /*public function sql_buscarEvaluacionPorId(): string {
        return sprintf(
            "SELECT *
             FROM evaluacion_administrativos
             WHERE id_eval_admin = %d;",
            $this->id_eval_admin
        );
    }*/

    // =============================
// Buscar evaluación por ID restringida a un evaluado
// =============================
public function sql_buscar_por_id_y_evaluado(string $cedula): string {
    return sprintf(
        "SELECT ea.id_eval_admin
         FROM evaluacion_administrativos ea
         JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
         JOIN usuarios u ON e.id_usuario = u.id_usuario
         WHERE ea.id_eval_admin = %d
           AND u.cedula_usuario = '%s';",
        $this->id_eval_admin,
        addslashes($cedula)
    );
}

// =============================
// Buscar evaluación por ID restringida a un supervisor
// =============================
public function sql_buscar_por_id_y_supervisor(string $cedula): string {
    return sprintf(
        "SELECT ea.id_eval_admin
         FROM evaluacion_administrativos ea
         JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
         JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
         JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
         JOIN usuarios u ON s.id_usuario = u.id_usuario
         WHERE ea.id_eval_admin = %d
           AND u.cedula_usuario = '%s';",
        $this->id_eval_admin,
        addslashes($cedula)
    );
}

    // =============================
    // Objetivos
    // =============================
    public static function sql_objetivos_por_cedula(string $cedula): string {
        return sprintf(
            "SELECT 
                odi.id_odi,
                odi.nombre_objetivo,
                odi.peso_objetivo,
                eo.id_eval_admin,
                u.cedula_usuario,
                eo.rango_obj,
                eo.pesoxrango_obj
             FROM evaluacion_objetivos eo
             JOIN objetivos_desempeno_individual odi 
                  ON eo.id_odi = odi.id_odi
             JOIN evaluacion_administrativos ea 
                  ON eo.id_eval_admin = ea.id_eval_admin
             JOIN evaluados e 
                  ON ea.id_evaluado = e.id_evaluado
             JOIN usuarios u 
                  ON e.id_usuario = u.id_usuario
             WHERE u.cedula_usuario = '%s'
             ORDER BY eo.id_obj_result ASC;",
            addslashes($cedula)
        );
    }


    // =============================
    // Competencias
    // =============================
    public static function sql_competencias(int $idEvalAdmin): string {
        return "
            SELECT ec.id_comp_result,
                   ec.id_competencia,
                   c.nombre_competencia,
                   c.peso_competencia,
                   ec.rango_comp,
                   ec.pesoxrango_comp
            FROM evaluacion_competencias ec
            JOIN competencias c ON ec.id_competencia = c.id_competencia
            WHERE ec.id_eval_admin = $idEvalAdmin;
        ";
    }

    // 🔹 Actualizar comentario del supervisor
    public function sql_update_comentario_supervisor(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos 
             SET comentario_supervisor = '%s'
             WHERE id_eval_admin = %d
             RETURNING id_eval_admin;",
            addslashes($this->comentario_supervisor),
            $this->id_eval_admin
        );
    }
     // 🔹 Actualizar comentario del evaluado + conformidad
     public function sql_update_comentario_evaluado(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos 
             SET comentario_evaluado = '%s',
                 conformidad = '%s'
             WHERE id_eval_admin = %d;",
            addslashes($this->comentario_evaluado),
            addslashes($this->conformidad),
            $this->id_eval_admin
        );
    }


    public function getCedulaUsuario(): string {
        return $this->cedula_usuario;
    }

    

   
}