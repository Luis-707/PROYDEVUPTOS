<?php

class Listados {
    private $conexion;

    public function __construct($conexion = NULL) {
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
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
        EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,
        COUNT(con.id_odi) AS cantidad_objetivos,
        COALESCE(SUM(o.peso_objetivo), 0) AS suma_pesos
    FROM evaluacion_administrativos ea
    JOIN usuarios u ON u.id_usuario = ea.evaluado_id
    JOIN cargos c ON c.id_cargo = u.id_cargo
    LEFT JOIN contiene con ON con.id_eval_admin = ea.id_eval_admin
    LEFT JOIN objetivos_desempeno_individual o ON o.id_odi = con.id_odi
    WHERE ea.evaluador_id = %d
    GROUP BY 
        ea.id_eval_admin,
        ea.evaluado_id,
        u.cedula_usuario,
        u.nombre_completo,
        c.nombre_cargo,
        ea.periodo_evaluado,
        ea.fecha_inicio
    ORDER BY u.cedula_usuario ASC;
        ", $idEvaluador);

    }

    public static function sql_listar_por_evaluado(int $idUsuario): string
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
        WHERE u.id_usuario = %d
          AND ea.estado_eval_admin = 'Finalizada'
        ORDER BY u.cedula_usuario ASC;
    ", $idUsuario);
}





public static function sql_listar_por_supervisor(int $idUsuarioSupervisor): string
{
    return sprintf("
        SELECT 
            ea.id_eval_admin,
            u_eval.cedula_usuario,
            u_eval.nombre_completo,
            cargo_eval.nombre_cargo AS cargo,
            org_eval.nombre AS unidad,
            ea.periodo_evaluado,
            EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio

        FROM evaluacion_administrativos ea
        JOIN usuarios u_eval ON u_eval.id_usuario = ea.evaluado_id
        JOIN cargos cargo_eval ON cargo_eval.id_cargo = u_eval.id_cargo
        JOIN organizaciones org_eval ON org_eval.id_org = cargo_eval.id_org
        JOIN usuarios u_ev ON u_ev.id_usuario = ea.evaluador_id
        JOIN cargos cargo_ev ON cargo_ev.id_cargo = u_ev.id_cargo
        JOIN organizaciones org_ev ON org_ev.id_org = cargo_ev.id_org
        JOIN usuarios u_sup ON u_sup.id_usuario = %d
        JOIN cargos cargo_sup ON cargo_sup.id_cargo = u_sup.id_cargo
        JOIN organizaciones org_sup ON org_sup.id_org = cargo_sup.id_org

        WHERE org_ev.padre_id = org_sup.id_org
          AND ea.estado_eval_admin = 'Finalizada'
        ORDER BY u_eval.cedula_usuario ASC;
    ", $idUsuarioSupervisor);
}
  

    public static function sql_listar_comentarios_obrero_por_evaluado(string $cedula): string
    {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u.cedula_usuario,
                u.nombre_completo,
                c.nombre_cargo AS cargo,
                org.nombre AS unidad,
                eo.periodo_evaluacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio
            FROM evaluacion_obreros eo
            JOIN usuarios u ON u.id_usuario = eo.evaluado_id
            JOIN cargos c ON c.id_cargo = u.id_cargo
            JOIN organizaciones org ON org.id_org = c.id_org
            WHERE u.cedula_usuario = '%s'
              AND eo.estado_eval_obrero = 'Finalizada'
            ORDER BY u.cedula_usuario ASC;
        ", addslashes($cedula));
    }

    public static function sql_listar_comentarios_obrero_por_supervisor(string $idSupervisor): string
{
    return sprintf("
       SELECT 
            eo.id_eval_obreros,
            u.cedula_usuario,
            u.nombre_completo,
            c.nombre_cargo AS cargo,
            eo.periodo_evaluacion,
            EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio

        FROM usuarios jefe
        JOIN cargos cj ON jefe.id_cargo = cj.id_cargo
        JOIN organizaciones org_jefe ON cj.id_org = org_jefe.id_org
        JOIN organizaciones org_hija 
            ON org_hija.padre_id = org_jefe.id_org
            OR org_hija.id_org = org_jefe.id_org
        JOIN cargos c_ev ON c_ev.id_org = org_hija.id_org
        JOIN usuarios ev ON ev.id_cargo = c_ev.id_cargo
            AND ev.estado_usuario = 'Activo'
        JOIN evaluacion_obreros eo ON eo.evaluador_id = ev.id_usuario
            AND eo.estado_eval_obrero = 'Finalizada'
        JOIN usuarios u ON u.id_usuario = eo.evaluado_id
        JOIN cargos c ON c.id_cargo = u.id_cargo
        JOIN organizaciones org_eval ON org_eval.id_org = c.id_org

        WHERE jefe.id_usuario = %d
        ORDER BY u.cedula_usuario ASC
        LIMIT 100;
    ", addslashes($idSupervisor));
}

    public static function sql_listar_eval_obreros(string $idUser): string {
        return sprintf("
            SELECT 
                u.cedula_usuario,
                u.nombre_completo,
                u.ubicacion_administrativa,
                c.nombre_cargo AS cargo_evaluado,
                eo.periodo_evaluacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio,
                eo.id_eval_obreros,
                eo.evaluado_id,
                eo.evaluador_id
            FROM evaluacion_obreros eo
            JOIN usuarios u ON eo.evaluado_id = u.id_usuario
            JOIN cargos c ON u.id_cargo = c.id_cargo
            WHERE eo.evaluador_id = %d
            ORDER BY u.cedula_usuario ASC;
        ", intval($idUser));
    }

    // 🔹 Listar evaluaciones por usuario evaluador
    public static function sql_reportes_por_evaluador(string $cedula): string {
        return sprintf("
         SELECT 
    ea.id_eval_admin,
    EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio,
    
    u_eval.id_usuario AS evaluado_id,
    u_eval.cedula_usuario,
    u_eval.nombre_completo,
    c_eval.nombre_cargo AS cargo_evaluado,
    org_eval.nombre AS ubicacion_evaluado,

    ea.periodo_evaluado,
    ea.puntaje_final,
    ra.rango_actuacion

FROM evaluacion_administrativos ea
JOIN usuarios u_eval 
    ON ea.evaluado_id = u_eval.id_usuario
JOIN cargos c_eval 
    ON u_eval.id_cargo = c_eval.id_cargo
JOIN organizaciones org_eval
    ON c_eval.id_org = org_eval.id_org
JOIN usuarios u_ev 
    ON ea.evaluador_id = u_ev.id_usuario
LEFT JOIN rango_actuacion ra 
    ON ea.id_rango = ra.id_rango

WHERE u_ev.cedula_usuario = '%s'
ORDER BY ea.fecha_inicio DESC;
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

}