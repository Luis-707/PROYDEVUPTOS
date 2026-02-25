<?php

/*class PlanillaAdministrativos {
    private $conexion;
    private $id_usuario = 0;
    private $id_evaluado = 0;
    private $id_eval_admin = 0;
    private $id_rango = 0;
    private $puntaje_final = 0;
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluado = "";

    public function __construct($dataCliente=array(''),$conexion = NULL) {
        
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = (int)$dataCliente['id_usuario'];
        }
        if (isset($dataCliente['id_evaluado'])) {
            $this->id_evaluado = (int)$dataCliente['id_evaluado'];
        }
        if (isset($dataCliente['id_eval_admin'])) {
            $this->id_eval_admin = (int)$dataCliente['id_eval_admin'];
        }
        if (isset($dataCliente['id_rango'])) {
            $this->id_rango = (int)$dataCliente['id_rango'];
        }
        if (isset($dataCliente['puntaje_final'])) {
            $this->puntaje_final = (int)$dataCliente['puntaje_final'];
        }
        if (isset($dataCliente['fecha_inicio'])) {
            $this->fecha_inicio = $dataCliente['fecha_inicio'];
        }
        if (isset($dataCliente['fecha_cierre'])) {
            $this->fecha_cierre = $dataCliente['fecha_cierre'];
        }
        if (isset($dataCliente['periodo_evaluado'])) {
            $this->periodo_evaluado = $dataCliente['periodo_evaluado'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public function setIdEvalAdmin(int $id): void {
        $this->id_eval_admin = $id;
    }

    public static function sql_listar_relaciones(): string {
        return "
        SELECT 
        e.id_evaluado AS id_evaluado,
        u_evaluado.cedula_usuario AS cedula_usuario,
        u_evaluado.nombre_completo AS nombre_completo_evaluado,
        u_evaluado.ubicacion_administrativa AS ubicacion_evaluado,
        c_ev.cargo_evaluado AS cargo_evaluado,
    
        u_evaluador.id_usuario AS id_usuario_evaluador,
        u_evaluador.cedula_usuario AS cedula_evaluador,
        u_evaluador.nombre_completo AS nombre_completo_evaluador,
        u_evaluador.ubicacion_administrativa AS ubicacion_evaluador,
        c_ee.cargo_evaluador AS cargo_evaluador,
    
        u_supervisor.cedula_usuario AS cedula_supervisor,
        u_supervisor.nombre_completo AS nombre_completo_supervisor,
        c_es.cargo_supervisor AS cargo_supervisor
    FROM evaluados e
    JOIN usuarios u_evaluado ON e.id_usuario = u_evaluado.id_usuario
    JOIN cargos_evaluados c_ev ON e.id_cargo_evaluado = c_ev.id_cargo_evaluado
    
    JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
    JOIN usuarios u_evaluador ON ev.id_usuario = u_evaluador.id_usuario
    JOIN cargos_evaluadores c_ee ON ev.id_cargo_evaluador = c_ee.id_cargo_evaluador
    
    JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
    JOIN usuarios u_supervisor ON s.id_usuario = u_supervisor.id_usuario
    JOIN cargos_supervisores c_es ON s.id_cargo_supervisor = c_es.id_cargo_supervisor;    
        ";
    }
    
    public function listarRelaciones() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_relaciones());
        }
        return "No se ha definido la conexión";
    }

   // Método para obtener el id_rango según el puntaje
   public function obtenerIdRangoPorPuntaje($puntaje) {
    $sql = sprintf(
        "SELECT id_rango 
         FROM rango_actuacion 
         WHERE %d BETWEEN puntaje_minimo AND puntaje_maximo 
         LIMIT 1;",
        (int)$puntaje
    );
    $res = $this->conexion->ejecutarConsultaBdds($sql);
    return !empty($res[0]['id_rango']) ? (int)$res[0]['id_rango'] : null;
}

  // Guardar evaluación general (UPDATE)
  public function sql_guardar_evaluacion(): string {
    return sprintf(
        "UPDATE evaluacion_administrativos
         SET id_rango = %d,
             puntaje_final = %d
         WHERE id_evaluado = %d
           AND id_usuario = %d
           AND periodo_evaluado = '%s'
         RETURNING id_eval_admin;",
        $this->id_rango,
        $this->puntaje_final,
        $this->id_evaluado,
        $this->id_usuario,
        $this->periodo_evaluado
    );
}

// Guardar evaluación general (UPDATE)
public function sql_actualizar_evaluacion(): string {
    return sprintf(
        "UPDATE evaluacion_administrativos
         SET id_rango = %d,
             puntaje_final = %d
         WHERE id_evaluado = %d
           AND id_usuario = %d
           AND id_eval_admin = %d
         RETURNING id_eval_admin;",
        $this->id_rango,
        $this->puntaje_final,
        $this->id_evaluado,
        $this->id_usuario,
        $this->id_eval_admin
    );
}

// Guardar objetivo
public function sql_guardar_objetivo($idEvalAdmin, $idOdi, $rango, $pesoXRango): string {
    return sprintf(
        "INSERT INTO evaluacion_objetivos (id_eval_admin,id_odi, rango_obj, pesoxrango_obj)
         VALUES (%d,%d, %d, %d)
         RETURNING id_obj_result;",
         (int)$idEvalAdmin,
        (int)$idOdi,
        (int)$rango,
        (int)$pesoXRango
    );
}

// Guardar competencia
public function sql_guardar_competencia($idEvalAdmin, $idCompetencia, $rango, $pesoXRango): string {
    return sprintf(
        "INSERT INTO evaluacion_competencias (id_eval_admin, id_competencia, rango_comp, pesoxrango_comp)
         VALUES (%d, %d, %d, %d)
         RETURNING id_comp_result;",
        (int)$idEvalAdmin,
        (int)$idCompetencia,
        (int)$rango,
        (int)$pesoXRango
    );
}

public function sql_actualizar_periodo(): string {
    return sprintf(
        "UPDATE evaluacion_administrativos
         SET periodo_evaluado = '%s',
             fecha_inicio = '%s',
             fecha_cierre = '%s'
         WHERE id_evaluado = %d
         RETURNING id_eval_admin;",
        $this->periodo_evaluado,
        $this->fecha_inicio,
        $this->fecha_cierre,
        $this->id_evaluado
    );
}

// Obtener periodo y fechas de una evaluación
public function sql_obtener_periodo(): string {
    return sprintf(
        "SELECT periodo_evaluado, fecha_inicio, fecha_cierre
         FROM evaluacion_administrativos
         WHERE id_evaluado = %d
           AND id_usuario = %d
         ORDER BY id_eval_admin DESC
         LIMIT 1;",
        $this->id_evaluado,
        $this->id_usuario
    );
}

public function sql_listar_periodo_por_id($idEvalAdmin): string {
    return sprintf(
        "SELECT fecha_inicio, fecha_cierre, periodo_evaluado
         FROM evaluacion_administrativos
         WHERE id_eval_admin = %d;",
        (int)$idEvalAdmin
    );
}



    // Buscar evaluación existente
    public function sql_buscar($idEvalAdmin): string {
        return sprintf(
            "SELECT id_eval_admin, periodo_evaluado
             FROM evaluacion_administrativos
             WHERE id_evaluado = %d
               AND id_usuario = %d
               AND id_eval_admin = %d
             ORDER BY id_eval_admin DESC
             LIMIT 1;",
            $this->id_evaluado,
            $this->id_usuario,
            (int)$idEvalAdmin

        );
    }

    // Verificar si ya existen objetivos para una evaluación
    public function sql_existen_objetivos($idEvalAdmin): string {
        return sprintf(
            "SELECT COUNT(*) AS total 
             FROM evaluacion_objetivos 
             WHERE id_eval_admin = %d;",
            (int)$idEvalAdmin
        );
    }

    // Verificar si ya existen competencias para una evaluación
    public function sql_existen_competencias($idEvalAdmin): string {
        return sprintf(
            "SELECT COUNT(*) AS total 
             FROM evaluacion_competencias 
             WHERE id_eval_admin = %d;",
            (int)$idEvalAdmin
        );
    }

     // Actualizar un objetivo existente
     public function sql_actualizar_objetivo($idEvalAdmin, $idOdi, $rango, $pesoXRango): string {
        return sprintf(
            "UPDATE evaluacion_objetivos
             SET rango_obj = %d,
                 pesoxrango_obj = %d
             WHERE id_eval_admin = %d
               AND id_odi = %d
             RETURNING id_obj_result;",
            (int)$rango,
            (int)$pesoXRango,
            (int)$idEvalAdmin,
            (int)$idOdi
        );
    }

    // Actualizar una competencia existente
    public function sql_actualizar_competencia($idEvalAdmin, $idComp, $rango, $pesoXRango): string {
        return sprintf(
            "UPDATE evaluacion_competencias
             SET rango_comp = %d,
                 pesoxrango_comp = %d
             WHERE id_eval_admin = %d
               AND id_competencia = %d
             RETURNING id_comp_result;",
            (int)$rango,
            (int)$pesoXRango,
            (int)$idEvalAdmin,
            (int)$idComp
        );
    }


    public function getIdEvalAdmin(): int {
        return $this->id_eval_admin;
    }
    public function getCedulaUsuario(): string {
        return $this->cedula_usuario;
    }

    public function getIdevaluado(): int {
        return $this->id_evaluado;
    }

    public function getIdrango(): int {
        return $this->id_rango;
    }

    public function getPuntajefinal(): int {
        return $this->puntaje_final;
    }

    public function getFechainicio(): string {
        return $this->fecha_inicio;
    }

    public function getFechacierre(): string {
        return $this->fecha_cierre;
    }

    public function getPeriodoevaluado(): string {
        return $this->periodo_evaluado;
    }

}*/


class PlanillaAdministrativos {

    private $conexion;

    private $evaluado_id = 0;
    private $evaluador_id = 0;
    private $id_eval_admin = 0;

    private $id_rango = 0;
    private $puntaje_final = 0;

    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluado = "";

    public function __construct($data = [], $conexion = null) {

        if (isset($data['evaluado_id'])) {
            $this->evaluado_id = (int)$data['evaluado_id'];
        }

        if (isset($data['evaluador_id'])) {
            $this->evaluador_id = (int)$data['evaluador_id'];
        }

        if (isset($data['id_eval_admin'])) {
            $this->id_eval_admin = (int)$data['id_eval_admin'];
        }

        if (isset($data['id_rango'])) {
            $this->id_rango = (int)$data['id_rango'];
        }

        if (isset($data['puntaje_final'])) {
            $this->puntaje_final = (int)$data['puntaje_final'];
        }

        if (isset($data['fecha_inicio'])) {
            $this->fecha_inicio = $data['fecha_inicio'];
        }

        if (isset($data['fecha_cierre'])) {
            $this->fecha_cierre = $data['fecha_cierre'];
        }

        if (isset($data['periodo_evaluado'])) {
            $this->periodo_evaluado = $data['periodo_evaluado'];
        }

        if ($conexion !== null) {
            $this->conexion = $conexion;
        }
    }

    public function setIdEvalAdmin(int $id): void {
        $this->id_eval_admin = $id;
    }


    // ============================================================
    // 1) Cargar datos del evaluado, evaluador y supervisor
    // ============================================================
    public static function sql_cargar_planilla(int $evaluado_id, int $id_eval_admin): string {
        return sprintf("
          SELECT     
    ev.nombre_completo AS evaluador_nombre,
    ev.cedula_usuario AS evaluador_cedula,
    ev_cargo.nombre_cargo AS evaluador_cargo,
    ev_org.nombre AS evaluador_ubicacion,

    sup.nombre_completo AS supervisor_nombre,
    sup.cedula_usuario AS supervisor_cedula,
    sup_cargo.nombre_cargo AS supervisor_cargo,
    
    ed.nombre_completo AS evaluado_nombre,
    ed.cedula_usuario AS evaluado_cedula,
    ed_cargo.nombre_cargo AS evaluado_cargo,
    ed_org.nombre AS evaluado_ubicacion,

    e.id_eval_admin,
    e.evaluado_id,
    e.evaluador_id

FROM evaluacion_administrativos e

JOIN usuarios ev ON e.evaluador_id = ev.id_usuario
JOIN cargos ev_cargo ON ev.id_cargo = ev_cargo.id_cargo
JOIN organizaciones ev_org ON ev_cargo.id_org = ev_org.id_org

JOIN usuarios ed ON e.evaluado_id = ed.id_usuario
JOIN cargos ed_cargo ON ed.id_cargo = ed_cargo.id_cargo
JOIN organizaciones ed_org ON ed_cargo.id_org = ed_org.id_org

LEFT JOIN organizaciones org_padre ON ev_org.padre_id = org_padre.id_org
LEFT JOIN cargos sup_cargo ON sup_cargo.id_org = org_padre.id_org AND sup_cargo.es_jefe = true
LEFT JOIN usuarios sup ON sup.id_cargo = sup_cargo.id_cargo AND sup.estado_usuario = 'Activo'
LEFT JOIN organizaciones sup_org ON sup_cargo.id_org = sup_org.id_org

WHERE e.id_eval_admin = %d
  AND e.evaluado_id = %d

LIMIT 1;
        ", $id_eval_admin, $evaluado_id);
    }

    public static function sql_buscar_evaluacion(int $idEvalAdmin, int $evaluado_id, int $evaluador_id): string {
        return sprintf("
            SELECT id_eval_admin
            FROM evaluacion_administrativos
            WHERE id_eval_admin = %d
              AND evaluado_id = %d
              AND evaluador_id = %d
            LIMIT 1;
        ", (int)$idEvalAdmin, $evaluado_id, $evaluador_id);
    }

    // ============================================================
    // 2) Cargar periodo
    // ============================================================
    public static function sql_periodo(int $id_eval_admin): string {
        return sprintf("
            SELECT fecha_inicio, fecha_cierre, periodo_evaluado
            FROM evaluacion_administrativos
            WHERE id_eval_admin = %d
            LIMIT 1;
        ", $id_eval_admin);
    }

    // ============================================================
    // 3) Guardar evaluación general
    // ============================================================
    public function sql_guardar_evaluacion(): string {
        return sprintf("
            UPDATE evaluacion_administrativos
            SET id_rango = %d,
                puntaje_final = %d
            WHERE id_eval_admin = %d
              AND evaluado_id = %d
              AND evaluador_id = %d
            RETURNING id_eval_admin;
        ",
            $this->id_rango,
            $this->puntaje_final,
            $this->id_eval_admin,
            $this->evaluado_id,
            $this->evaluador_id
        );
    }

    // ============================================================
    // 4) Guardar objetivos
    // ============================================================
    public function sql_guardar_objetivo($idEvalAdmin, $idOdi, $rango, $pesoXRango): string {
        return sprintf("
            INSERT INTO evaluacion_objetivos (id_eval_admin, id_odi, rango_obj, pesoxrango_obj)
            VALUES (%d, %d, %d, %d)
            RETURNING id_obj_result;
        ",   (int)$idEvalAdmin,
        (int)$idOdi,
        (int)$rango,
        (int)$pesoXRango);
    }

    // ============================================================
    // 5) Guardar competencias
    // ============================================================
    public function sql_guardar_competencia($idEvalAdmin, $idComp, $rango, $pesoXRango): string {
        return sprintf("
            INSERT INTO evaluacion_competencias (id_eval_admin, id_competencia, rango_comp, pesoxrango_comp)
            VALUES (%d, %d, %d, %d)
            RETURNING id_comp_result;
        ", (int)$idEvalAdmin,
        (int)$idComp,
        (int)$rango,
        (int)$pesoXRango);
    }

    public static function sql_existen_objetivos(int $idEvalAdmin): string {
        return sprintf("
            SELECT COUNT(*) AS total
            FROM evaluacion_objetivos
            WHERE id_eval_admin = %d;
        ", $idEvalAdmin);
    }
    
    public static function sql_existen_competencias(int $idEvalAdmin): string {
        return sprintf("
            SELECT COUNT(*) AS total
            FROM evaluacion_competencias
            WHERE id_eval_admin = %d;
        ", $idEvalAdmin);
    }
    
    public static function sql_id_rango_por_puntaje(int $puntaje): string {
        return sprintf("
            SELECT id_rango
            FROM rango_actuacion
            WHERE %d BETWEEN puntaje_minimo AND puntaje_maximo
            LIMIT 1;
        ", $puntaje);
    }

     // ============================================================
    // 1) Cargar datos del evaluado, evaluador y supervisor
    // ============================================================
    public static function sql_cargar_planilla_editar(int $evaluado_id, int $id_eval_admin): string {
        return sprintf("
            SELECT     
                ev.nombre_completo AS evaluador_nombre,
                ev.cedula_usuario AS evaluador_cedula,
                ev_cargo.nombre_cargo AS evaluador_cargo,
                ev_org.nombre AS evaluador_ubicacion,

                sup.nombre_completo AS supervisor_nombre,
                sup.cedula_usuario AS supervisor_cedula,
                sup_cargo.nombre_cargo AS supervisor_cargo,
                
                ed.nombre_completo AS evaluado_nombre,
                ed.cedula_usuario AS evaluado_cedula,
                ed_cargo.nombre_cargo AS evaluado_cargo,
                ed_org.nombre AS evaluado_ubicacion,

                e.id_eval_admin,
                e.evaluado_id,
                e.evaluador_id

            FROM evaluacion_administrativos e

            JOIN usuarios ev ON e.evaluador_id = ev.id_usuario
            JOIN cargos ev_cargo ON ev.id_cargo = ev_cargo.id_cargo
            JOIN organizaciones ev_org ON ev_cargo.id_org = ev_org.id_org

            JOIN usuarios ed ON e.evaluado_id = ed.id_usuario
            JOIN cargos ed_cargo ON ed.id_cargo = ed_cargo.id_cargo
            JOIN organizaciones ed_org ON ed_cargo.id_org = ed_org.id_org

            LEFT JOIN organizaciones org_padre ON ev_org.padre_id = org_padre.id_org
            LEFT JOIN cargos sup_cargo ON sup_cargo.id_org = org_padre.id_org AND sup_cargo.es_jefe = true
            LEFT JOIN usuarios sup ON sup.id_cargo = sup_cargo.id_cargo AND sup.estado_usuario = 'Activo'

            WHERE e.id_eval_admin = %d
              AND e.evaluado_id = %d

            LIMIT 1;
        ", $id_eval_admin, $evaluado_id);
    }

    // ============================================================
    // 2) Buscar evaluación existente
    // ============================================================
    public static function sql_buscar(int $idEvalAdmin, int $evaluado_id, int $evaluador_id): string {
        return sprintf("
            SELECT id_eval_admin
            FROM evaluacion_administrativos
            WHERE id_eval_admin = %d
              AND evaluado_id = %d
              AND evaluador_id = %d
            LIMIT 1;
        ", $idEvalAdmin, $evaluado_id, $evaluador_id);
    }

    // ============================================================
    // 3) Actualizar evaluación general
    // ============================================================
    public function sql_actualizar_evaluacion(): string {
        return sprintf("
            UPDATE evaluacion_administrativos
            SET id_rango = %d,
                puntaje_final = %d
            WHERE id_eval_admin = %d
              AND evaluado_id = %d
              AND evaluador_id = %d
            RETURNING id_eval_admin;
        ",
            $this->id_rango,
            $this->puntaje_final,
            $this->id_eval_admin,
            $this->evaluado_id,
            $this->evaluador_id
        );
    }

    // ============================================================
    // 5) Actualizar objetivos
    // ============================================================
    public function sql_actualizar_objetivo($idEvalAdmin, $idOdi, $rango, $pesoXRango): string {
        return sprintf("
            UPDATE evaluacion_objetivos
            SET rango_obj = %d,
                pesoxrango_obj = %d
            WHERE id_eval_admin = %d
              AND id_odi = %d
            RETURNING id_obj_result;
        ",
            (int)$rango,
            (int)$pesoXRango,
            (int)$idEvalAdmin,
            (int)$idOdi
        );
    }

    // ============================================================
    // 6) Actualizar competencias
    // ============================================================
    public function sql_actualizar_competencia($idEvalAdmin, $idComp, $rango, $pesoXRango): string {
        return sprintf("
            UPDATE evaluacion_competencias
            SET rango_comp = %d,
                pesoxrango_comp = %d
            WHERE id_eval_admin = %d
              AND id_competencia = %d
            RETURNING id_comp_result;
        ",
            (int)$rango,
            (int)$pesoXRango,
            (int)$idEvalAdmin,
            (int)$idComp
        );
    }

    public function getIdEvalAdmin(): int {
        return $this->id_eval_admin;
    }
    
    public function getEvaluadoId(): int {
        return $this->evaluado_id;
    }
    
    public function getEvaluadorId(): int {
        return $this->evaluador_id;
    }
    
    public function getIdRango(): int {
        return $this->id_rango;
    }
    
    public function getPuntajeFinal(): int {
        return $this->puntaje_final;
    }
    
    public function getFechaInicio(): string {
        return $this->fecha_inicio;
    }
    
    public function getFechaCierre(): string {
        return $this->fecha_cierre;
    }
    
    public function getPeriodoEvaluado(): string {
        return $this->periodo_evaluado;
    }
    

}




?>
