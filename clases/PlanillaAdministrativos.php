<?php

class PlanillaAdministrativos {
    private $conexion;
    private $id_usuario = 0;
    private $id_evaluado = 0;
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

    public static function sql_listar_relaciones(): string {
        return "
            SELECT 
    e.id_evaluado AS id_evaluado,                -- 👈 ID del evaluado
    u_evaluado.cedula_usuario AS cedula_usuario,
    c_ev.cargo_evaluado AS cargo_evaluado,

    u_evaluador.id_usuario AS id_usuario_evaluador, -- 👈 ID del evaluador
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
         RETURNING id_eval_admin;",
        $this->id_rango,
        $this->puntaje_final,
        $this->id_evaluado,
        $this->id_usuario
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

    // Buscar evaluación existente
    public function sql_buscar(): string {
        return sprintf(
            "SELECT id_eval_admin
             FROM evaluacion_administrativos
             WHERE id_evaluado = %d
               AND id_usuario = %d
             ORDER BY id_eval_admin DESC
             LIMIT 1;",
            $this->id_evaluado,
            $this->id_usuario
        );
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


}


?>
