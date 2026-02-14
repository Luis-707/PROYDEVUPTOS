<?php

class Listados {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }

    // Buscar id_evaluador asociado a un usuario
    public static function sql_buscar_evaluador_por_usuario(int $idUsuario): string {
        return sprintf("
            SELECT ev.id_evaluador
            FROM evaluadores ev
            JOIN usuarios u ON ev.id_usuario = u.id_usuario
            WHERE u.id_usuario = %d;
        ", $idUsuario);
    }

    // Listar todos los evaluados (registrados + no registrados)
    public static function sql_listar_todos_evaluados_union(string $cedula, int $idEvaluador): string {
        return sprintf("
            -- Evaluados ya registrados
            SELECT u.*, e.id_evaluador
            FROM usuarios u
            JOIN evaluados e ON u.id_usuario = e.id_usuario
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s'

            UNION

            -- Evaluados no registrados (se les asigna el evaluador en sesión)
            SELECT u.*, %d AS id_evaluador
            FROM usuarios u
            JOIN roles_sistema r ON u.rol_id = r.rol_id
            WHERE r.rol = 'Evaluado'
              AND u.id_usuario NOT IN (SELECT id_usuario FROM evaluados);
        ", addslashes($cedula), $idEvaluador);
    }

    public function ejecutarConsulta(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public static function sql_listar_evaluaciones_admin(int $idEvaluador): string {
        return sprintf("
            SELECT 
                ea.id_eval_admin,
                ea.evaluado_id,
                u.cedula_usuario AS cedula,
                u.nombre_completo,
                c.nombre_cargo AS cargo,
                ea.periodo_evaluado,
                EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio
            FROM evaluacion_administrativos ea
            JOIN usuarios u ON u.id_usuario = ea.evaluado_id
            JOIN cargos c ON c.id_cargo = u.id_cargo
            WHERE ea.evaluador_id = %d
            ORDER BY u.cedula_usuario ASC;
        ", $idEvaluador);

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

    public static function sql_listar_por_evaluado(string $cedula): string
    {
        return sprintf("
            SELECT 
                ea.id_eval_admin,
                u.cedula_usuario,
                u.nombre_completo,
                c.nombre_cargo AS cargo,
                o.nombre AS unidad,
                ea.periodo_evaluado,
                EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio
            FROM evaluacion_administrativos ea
            JOIN usuarios u ON u.id_usuario = ea.evaluado_id
            JOIN cargos c ON c.id_cargo = u.id_cargo
            JOIN organizaciones o ON o.id_org = c.id_org
            WHERE u.cedula_usuario = '%s'
              AND ea.estado_eval_admin = 'Finalizada'
            ORDER BY u.cedula_usuario ASC;
        ", addslashes($cedula));
    }

    public static function sql_listar_por_supervisor(string $cedulaSupervisor): string
{
    return sprintf("
       SELECT 
            ea.id_eval_admin,
            u.cedula_usuario,
            u.nombre_completo,
            c.nombre_cargo AS cargo,
            o.nombre AS unidad,
            ea.periodo_evaluado,
            EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio

        FROM evaluacion_administrativos ea
        JOIN usuarios u ON u.id_usuario = ea.evaluado_id
        JOIN cargos c ON c.id_cargo = u.id_cargo
        JOIN organizaciones o ON o.id_org = c.id_org
        JOIN usuarios ev ON ev.id_usuario = ea.evaluador_id
        JOIN cargos c_ev ON c_ev.id_cargo = ev.id_cargo
        JOIN organizaciones org_ev ON org_ev.id_org = c_ev.id_org
        JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
        JOIN cargos c_sup ON c_sup.id_org = org_sup.id_org
        JOIN usuarios sup ON sup.id_cargo = c_sup.id_cargo

        WHERE sup.cedula_usuario = '%s'
          AND ea.estado_eval_admin = 'Finalizada'
        ORDER BY u.cedula_usuario ASC LIMIT 100
    ", addslashes($cedulaSupervisor));
}
  

    // 🔹 Listar evaluados bajo un evaluador (si manejas este rol)
    public static function sql_listar_por_evaluador(string $cedula): string {
        return sprintf("
        SELECT ea.id_eval_admin, EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio, e.id_evaluado, u.cedula_usuario, u.nombre_completo, c.cargo_evaluado, eo.periodo_evaluacion ,ea.periodo_evaluado, ea.comentario_evaluado, ea.comentario_supervisor
        FROM evaluados e
        JOIN usuarios u ON e.id_usuario = u.id_usuario
        JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
        LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
        LEFT JOIN evaluacion_obreros eo ON eo.id_evaluado = e.id_evaluado
        JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
        JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
        WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public static function sql_listar_comentarios_obrero_evaluado(string $cedula): string {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u.cedula_usuario,
                u.nombre_completo,
                c.cargo_evaluado,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio,
                eo.periodo_evaluacion
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            WHERE u.cedula_usuario = '%s'
            AND eo.estado_eval_obreros = 'Finalizada';
        ", addslashes($cedula));
    }

    public static function sql_listar_comentarios_obrero_supervisor(string $cedula): string {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u.cedula_usuario,
                u.nombre_completo,
                c.cargo_evaluado,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio,
                eo.periodo_evaluacion
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN supervisores s ON ev.id_supervisor = s.id_supervisor
            JOIN usuarios u_sup ON s.id_usuario = u_sup.id_usuario
            WHERE u_sup.cedula_usuario = '%s'
            AND eo.estado_eval_obreros = 'Finalizada';
        ", addslashes($cedula));
    }

     // 🔹 Listar evaluados bajo un evaluador (si manejas este rol)
     public static function sql_listar_cargos(string $cedula): string {
        return sprintf("
            SELECT u.id_usuario ,e.id_evaluado, u.cedula_usuario, u.nombre_completo, u.ubicacion_administrativa, c.cargo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public static function sql_listar_evaluados_por_cedula(string $cedula): string {
        return sprintf("
            SELECT u.*
            FROM usuarios u
            JOIN evaluados e ON e.id_usuario = u.id_usuario
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    // 🔹 Listar evaluados bajo un evaluador (si manejas este rol)
    public static function sql_listar_por_registro_evaluacion_Administrativos(string $cedula): string {
        return sprintf("
           SELECT ea.id_eval_admin,e.id_evaluado, u.cedula_usuario, c.cargo_evaluado, ea.periodo_evaluado
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public static function sql_listar_por_registro_evaluacion_Obreros(string $cedula): string {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                e.id_evaluado,
                u.cedula_usuario,
                c.cargo_evaluado,
                eo.periodo_evaluacion
            FROM evaluados e
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN evaluacion_obreros eo ON eo.id_evaluado = e.id_evaluado
            JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
            WHERE u_ev.cedula_usuario = '%s';
        ", addslashes($cedula));
    }

    public static function sql_listar_por_registro_Obreros(string $cedula) {

        return "
            SELECT 
                u.cedula_usuario,
                u.nombre_completo,
                u.ubicacion_administrativa,
                c.cargo_evaluado,
                eo.estado_eval_obreros,
                eo.periodo_evaluacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio,
                eo.id_eval_obreros,
                eo.id_evaluado,
                eo.id_usuario
            FROM evaluacion_obreros eo
            JOIN evaluados e ON eo.id_evaluado = e.id_evaluado
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            WHERE eo.id_usuario = (
                SELECT id_usuario FROM usuarios WHERE cedula_usuario = '$cedula'
            )
            ORDER BY u.cedula_usuario;
        ";
    }

    public static function sql_listar_eval_administrativos(string $idUser): string {
        return sprintf("
            SELECT u.cedula_usuario, u.nombre_completo, u.ubicacion_administrativa, c.cargo_evaluado, ea.estado_eval_admin, ea.periodo_evaluado, EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio, ea.id_eval_admin, ea.id_evaluado, ea.id_usuario
            FROM evaluacion_administrativos ea
            JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            WHERE ea.id_usuario = %d;
        ", addslashes($idUser));
    }

    // 🔹 Listar evaluaciones por usuario evaluador
    public static function sql_reportes_por_evaluador(string $cedula): string {
        return sprintf("
           SELECT 
            ea.id_eval_admin, 
            EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio, 
            e.id_evaluado, 
            u.cedula_usuario, 
            u.nombre_completo, 
            c.cargo_evaluado, 
            ea.periodo_evaluado,
            ea.puntaje_final,
            ra.rango_actuacion
        FROM evaluados e
        JOIN usuarios u ON e.id_usuario = u.id_usuario
        JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
        LEFT JOIN evaluacion_administrativos ea ON ea.id_evaluado = e.id_evaluado
        LEFT JOIN rango_actuacion ra ON ea.id_rango = ra.id_rango
        JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
        JOIN usuarios u_ev ON ev.id_usuario = u_ev.id_usuario
        WHERE u_ev.cedula_usuario = '%s';
     ", addslashes($cedula));
    }

     // Método estático que devuelve la consulta SQL para listar usuarios con su rol
     public static function sql_listar_datos(string $idUsuario): string {
        return sprintf("
        SELECT e.id_evaluado, 
            eu.id_usuario, 
            eu.cedula_usuario,
            eu.nombre_completo, 
            r.rol
            FROM evaluados e
            INNER JOIN evaluadores ev ON e.id_evaluador = ev.id_evaluador
            INNER JOIN usuarios eu ON e.id_usuario = eu.id_usuario
            INNER JOIN roles_sistema r ON eu.rol_id = r.rol_id
            WHERE ev.id_usuario = %d; -- Aquí pones el id_usuario del evaluador
        ", addslashes($idUsuario));
    }

    public function listar_user_evaluado(string $cedula) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(
                self::sql_listar_evaluados_por_cedula($cedula)
            );
        }
        return "No se ha definido la conexión";
    }
    public function listar_cargos_evaluados(string $cedula) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(
                self::sql_listar_cargos($cedula)
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



    public function listarEvaluadorResultados(string $sql) {
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

    public function listaEvaluadosObreros(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listaObreros(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listarComentariosEvaluadosObreros(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listarEvalAdministrativos(string $cedula) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(
                self::sql_listar_por_registro_evaluacion_Administrativos($cedula)
            );
        }
        return "No se ha definido la conexión";
    }

    public function listarEvalAdmin(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listarReportes(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

    public function listarDatos(string $sql) {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds($sql);
        }
        return "No se ha definido la conexión";
    }

}