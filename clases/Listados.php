<?php

class Listados {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // 🔹 Listado general (solo para admins, si lo usas)
    public static function sql_listar_evaluados(): string {
        return "
            SELECT 
                e.id_evaluado,
                u.cedula_usuario,
                c.cargo_evaluado,
                ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
        ";
    }

    // 🔹 Listar solo el evaluado logueado
    public static function sql_listar_por_evaluado(string $cedula): string {
        return sprintf("
            SELECT e.id_evaluado, u.cedula_usuario, c.cargo_evaluado, ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            WHERE u.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    // 🔹 Listar evaluados bajo un supervisor
    public static function sql_listar_por_supervisor(string $cedula): string {
        return sprintf("
            SELECT e.id_evaluado, u.cedula_usuario, c.cargo_evaluado, ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
            JOIN usuarios u_sup ON s.id_usuario = u_sup.id_usuario
            WHERE u_sup.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    // 🔹 Listar evaluados bajo un evaluador (si manejas este rol)
    public static function sql_listar_por_evaluador(string $cedula): string {
        return sprintf("
            SELECT e.id_evaluado, u.cedula_usuario, c.cargo_evaluado, ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public static function sql_listar_evaluados_por_cedula(string $cedula): string {
        return sprintf("
            SELECT u.*
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public function listar_user_evaluado(string $cedula) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(
                self::sql_listar_evaluados_por_cedula($cedula)
            );
        }
        return "No se ha definido la conexión";
    }
    public function listarEvaluadosComentarios(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listaEvaluados(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

}