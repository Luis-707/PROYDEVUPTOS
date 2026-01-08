<?php

class ReportesPlanillaAdmin {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_datos_combinados(int $idEvalAdmin): string {
        return sprintf("
            SELECT ea.id_eval_admin, ea.periodo_evaluado, ea.fecha_inicio, ea.fecha_cierre,
                   ea.comentario_supervisor, ea.comentario_evaluado, ea.conformidad,
                   
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
                   r.rango_actuacion, ea.puntaje_final,
                   
                   -- Desempeño excepcional (si existe)
                   de.id_desemp_excepcional, de.periodo AS periodo_excep, de.fecha AS fecha_excep
            FROM evaluacion_administrativos ea
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
            LEFT JOIN desempeno_excepcional de ON ea.id_eval_admin = de.id_eval_admin
            WHERE ea.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // 🔹 Listar evaluaciones disponibles para reporte 


    public static function sql_listar_reportes(): string {
        return "
            SELECT ea.id_eval_admin, u.cedula_usuario, u.nombre_completo,
                   c.cargo_evaluado, ea.periodo_evaluado,
                   ea.comentario_supervisor, ea.comentario_evaluado, ea.conformidad,
                   EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio
            FROM evaluacion_administrativos ea
            JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            WHERE TRIM(ea.comentario_supervisor) <> ''
              AND TRIM(ea.comentario_evaluado) <> ''
              AND TRIM(ea.conformidad) <> '';
        ";
    }
   
    public function listarReportesAdmin() {
    if ($this->conexion != NULL) {
   
    return $this->conexion->ejecutarConsultaBdds(self::sql_listar_reportes()); 

} 
    
    return []; 
   
   }

    // Datos generales de la evaluación
    public static function sql_datos_evaluacion(int $idEvalAdmin): string {
        return sprintf("
            SELECT ea.id_eval_admin, ea.periodo_evaluado, ea.fecha_inicio, ea.fecha_cierre,
                   ea.comentario_supervisor, ea.comentario_evaluado, ea.conformidad,
                   u_eval.cedula_usuario AS cedula_evaluado, u_eval.nombre_completo  AS nombre_evaluado,
                   c_ev.cargo_evaluado, u_eval.ubicacion_administrativa AS ubicacion_evaluado,   -- 🔹 nueva columna

                   u_ev.cedula_usuario AS cedula_evaluador, u_ev.nombre_completo AS nombre_evaluador,
                   c_ee.cargo_evaluador,  u_ev.ubicacion_administrativa AS ubicacion_evaluador,
                   
                   u_sup.cedula_usuario AS cedula_supervisor, u_sup.nombre_completo AS nombre_supervisor,
                   c_es.cargo_supervisor,
                   r.rango_actuacion, ea.puntaje_final
            FROM evaluacion_administrativos ea
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
            WHERE ea.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // Objetivos asociados
    public static function sql_objetivos(int $idEvalAdmin): string {
        return sprintf("
            SELECT o.nombre_objetivo, o.peso_objetivo, eo.rango_obj, eo.pesoxrango_obj
            FROM evaluacion_objetivos eo
            JOIN objetivos_desempeno_individual o ON eo.id_odi = o.id_odi
            WHERE eo.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    // Competencias asociadas
    public static function sql_competencias(int $idEvalAdmin): string {
        return sprintf("
            SELECT c.nombre_competencia, c.peso_competencia, ec.rango_comp, ec.pesoxrango_comp
            FROM evaluacion_competencias ec
            JOIN competencias c ON ec.id_competencia = c.id_competencia
            WHERE ec.id_eval_admin = %d;
        ", $idEvalAdmin);
    }

    public function ejecutarConsulta(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return [];
    }
}