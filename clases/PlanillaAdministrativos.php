<?php

class PlanillaAdministrativos {
    private $conexion;
    public $id_usuario = 0;
    public $id_evaluado = 0;
    public $id_rango = 0;
    public $puntaje_final = 0;
    public $fecha_inicio = "";
    public $fecha_cierre = "";
    public $periodo_evaluado = "";

    public function __construct($dataCliente=array(''),$conexion = NULL) {
        

      
        $this->id_usuario       = isset($dataCliente['id_usuario']) ? (int)$dataCliente['id_usuario'] : 0;
        $this->id_evaluado      = isset($dataCliente['id_evaluado']) ? (int)$dataCliente['id_evaluado'] : 0;
        $this->id_rango         = isset($dataCliente['id_rango']) ? (int)$dataCliente['id_rango'] : 0;
        $this->puntaje_final    = isset($dataCliente['puntaje_final']) ? (int)$dataCliente['puntaje_final'] : 0;
        $this->fecha_inicio     = isset($dataCliente['fecha_inicio']) ? $dataCliente['fecha_inicio'] : "";
        $this->fecha_cierre     = isset($dataCliente['fecha_cierre']) ? $dataCliente['fecha_cierre'] : "";
        $this->periodo_evaluado = isset($dataCliente['periodo_evaluado']) ? $dataCliente['periodo_evaluado'] : "";

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

public function sql_guardar_evaluacion(): string {
    return sprintf(
        "UPDATE evaluacion_administrativos
         SET id_rango = %d,
             puntaje_final = %d,
             fecha_inicio = '%s',
             fecha_cierre = '%s',
             periodo_evaluado = '%s'
         WHERE id_usuario = %d
         RETURNING id_eval_admin;",
        $this->id_rango,
        $this->puntaje_final,
        $this->fecha_inicio,
        $this->fecha_cierre,
        $this->periodo_evaluado,
        $this->id_usuario
    );
}

public function sql_buscar(): string {
    return sprintf(
        "SELECT * FROM evaluacion_administrativos 
         WHERE id_evaluado = %d AND periodo_evaluado = '%s';",
        (int)$this->id_evaluado,
        $this->periodo_evaluado
    );
}
}


?>
