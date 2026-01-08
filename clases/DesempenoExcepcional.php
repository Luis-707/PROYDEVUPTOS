<?php

class DesempenoExcepcional {
    private $conexion;
    private $id_desemp_excepcional = 0;
    private $id_eval_admin = 0;
    private $periodo = "";
    private $fecha = "";

    public function __construct($data=array(''), $conexion = NULL) {
        if (isset($data['id_eval_admin'])) {
            $this->id_eval_admin = (int)$data['id_eval_admin'];
        }
        if (isset($data['periodo'])) {
            $this->periodo = $data['periodo'];
        }
        if (isset($data['fecha'])) {
            $this->fecha = $data['fecha'];
        }
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_datos_excepcional_completo(int $idEvalAdmin): string {
        return sprintf("
            SELECT de.id_desemp_excepcional, de.id_eval_admin, de.periodo, de.fecha,
                   -- Evaluado
                   u_eval.cedula_usuario AS cedula_evaluado, u_eval.nombre_completo AS nombre_evaluado,
                   c_ev.cargo_evaluado, u_eval.ubicacion_administrativa AS ubicacion_evaluado,
                   
                   -- Evaluador
                   u_ev.cedula_usuario AS cedula_evaluador, u_ev.nombre_completo AS nombre_evaluador,
                   c_ee.cargo_evaluador, u_ev.ubicacion_administrativa AS ubicacion_evaluador,
                   
                   -- Supervisor
                   u_sup.cedula_usuario AS cedula_supervisor, u_sup.nombre_completo AS nombre_supervisor,
                   c_es.cargo_supervisor,
                   
                   -- Rango y puntaje
                   r.rango_actuacion, ea.puntaje_final
            FROM desempeno_excepcional de
            JOIN evaluacion_administrativos ea ON de.id_eval_admin = ea.id_eval_admin
            JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
            JOIN usuarios u_eval ON e.id_usuario = u_eval.id_usuario
            JOIN cargos_evaluados c_ev ON e.id_cargo_evaluado = c_ev.id_cargo_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            JOIN cargos_evaluadores c_ee ON ev.id_cargo_evaluador = c_ee.id_cargo_evaluador
            JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
            JOIN usuarios u_sup ON s.id_usuario = u_sup.id_usuario
            JOIN cargos_supervisores c_es ON s.id_cargo_supervisor = c_es.id_cargo_supervisor
            JOIN rango_actuacion r ON ea.id_rango = r.id_rango
            WHERE de.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // Crear registro principal en desempeno_excepcional
    public function sql_guardar_excepcional(): string {
        return sprintf(
            "INSERT INTO desempeno_excepcional (id_eval_admin, periodo, fecha)
             VALUES (%d, '%s', '%s')
             RETURNING id_desemp_excepcional;",
            $this->id_eval_admin,
            $this->periodo,
            $this->fecha
        );
    }

    // Guardar motivo asociado a un indicador
    public function sql_guardar_motivo($idDesempExcepcional, $indicadorId, $motivo): string {
        return sprintf(
            "INSERT INTO motivos (id_desemp_excepcional, indicador_id, motivo)
             VALUES (%d, %d, '%s')
             RETURNING motivo_id;",
            (int)$idDesempExcepcional,
            (int)$indicadorId,
            pg_escape_string($motivo)
        );
    }

    // Relacionar indicador con desempeño excepcional
    public function sql_guardar_relacion($idDesempExcepcional, $indicadorId): string {
        return sprintf(
            "INSERT INTO tiene_indicador (id_desemp_excepcional, indicador_id)
             VALUES (%d, %d);",
            (int)$idDesempExcepcional,
            (int)$indicadorId
        );
    }

    public static function sql_listar_motivos(int $idDesempExcepcional): string {
        return sprintf("
            SELECT m.motivo_id, m.motivo, i.indicador
            FROM motivos m
            JOIN indicadores i ON m.indicador_id = i.indicador_id
            WHERE m.id_desemp_excepcional = %d;",
            $idDesempExcepcional
        );
    }

    // Listar indicadores fijos para desempeño excepcional
    public static function sql_listar_indicadores(int $idEvalAdmin): string {
        return "
            SELECT i.indicador_id, i.indicador, i.tipo_indicador, i.estado_indicador
            FROM indicadores i
            WHERE i.tipo_indicador = 'Fijo'
              AND i.estado_indicador = 'Activo'
            ORDER BY i.indicador_id ASC
            LIMIT 3;
        ";
    }

    public static function sql_datos_excepcional(int $idDesempExcepcional): string {
        return sprintf("
            SELECT id_desemp_excepcional, id_eval_admin, periodo, fecha
            FROM desempeno_excepcional
            WHERE id_desemp_excepcional = %d;",
            $idDesempExcepcional
        );
    }

    // Verificar si ya existe planilla excepcional para una evaluación
    public static function sql_existe_excepcional($idEvalAdmin): string {
        return sprintf(
            "SELECT id_desemp_excepcional
             FROM desempeno_excepcional
             WHERE id_eval_admin = %d;",
            (int)$idEvalAdmin
        );
    }
}
?>