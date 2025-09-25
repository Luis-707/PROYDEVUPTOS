<?php
class Planilla {
    private $conexion;
    private $cedula_evaluado = "";

    public function __construct($dataCliente = array(), $conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
        $this->cedula_evaluado = $dataCliente['cedula_usuario'] ?? '';
    }

    // 🔹 Datos del Evaluado (rol_id = 4)
    public function sql_datos_evaluado(): string {
        return sprintf("
            SELECT 
                e.id_evaluado,
                u.id_usuario,
                u.cedula_usuario,
                u.rol_id,
                ce.cargo_evaluado AS cargo,
                e.id_evaluador
            FROM evaluados e
            INNER JOIN usuarios u 
                ON u.id_usuario = e.id_usuario
            LEFT JOIN cargos_evaluados ce 
                ON ce.id_usuario = u.id_usuario
            WHERE u.cedula_usuario = '%s'
              AND u.rol_id = 4;
        ", $this->cedula_evaluado);
    }

    // 🔹 Datos del Evaluador (rol_id = 2)
    public function sql_datos_evaluador(): string {
        return sprintf("
            SELECT 
                ev.id_evaluador,
                u.id_usuario,
                u.cedula_usuario,
                u.rol_id,
                ce.cargo_evaluador AS cargo,
                ev.id_supervisor
            FROM evaluados e
            INNER JOIN evaluadores ev 
                ON ev.id_evaluador = e.id_evaluador
            INNER JOIN usuarios u 
                ON u.id_usuario = ev.id_usuario
            LEFT JOIN cargos_evaluados ce 
                ON ce.id_usuario = u.id_usuario
            WHERE e.id_evaluador = (
                SELECT e2.id_evaluador
                FROM evaluados e2
                INNER JOIN usuarios u2 ON u2.id_usuario = e2.id_usuario
                WHERE u2.cedula_usuario = '%s'
                  AND u2.rol_id = 4
            )
              AND u.rol_id = 2;
        ", $this->cedula_evaluado);
    }

    // 🔹 Datos del Supervisor (rol_id = 3)
    public function sql_datos_supervisor(): string {
        return sprintf("
            SELECT 
                s.id_supervisor,
                u.id_usuario,
                u.cedula_usuario,
                u.rol_id,
                cs.cargo_supervisor AS cargo
            FROM evaluadores ev
            INNER JOIN supervisores s 
                ON s.id_supervisor = ev.id_supervisor
            INNER JOIN usuarios u 
                ON u.id_usuario = s.id_usuario
            LEFT JOIN cargos_supervisores cs 
                ON cs.id_usuario = u.id_usuario
            WHERE ev.id_evaluador = (
                SELECT e2.id_evaluador
                FROM evaluados e2
                INNER JOIN usuarios u2 ON u2.id_usuario = e2.id_usuario
                WHERE u2.cedula_usuario = '%s'
                  AND u2.rol_id = 4
            )
              AND u.rol_id = 3;
        ", $this->cedula_evaluado);
    }
}