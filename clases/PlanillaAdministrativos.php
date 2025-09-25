<?php

class PlanillaAdministrativos {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    public static function sql_listar_relaciones(): string {
        return "
            SELECT 
                u_evaluado.cedula_usuario AS cedula_usuario,
                c_ev.cargo_evaluado AS cargo_evaluado,
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
        ";
    }
    
    public function listarRelaciones() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_relaciones());
        }
        return "No se ha definido la conexión";
    }
}

?>
