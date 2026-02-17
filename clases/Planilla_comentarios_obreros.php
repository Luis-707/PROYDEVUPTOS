<?php
class Planilla_comentarios_obreros {

private $conexion;
private $cedula_usuario = "";
private $comentario_supervisor = "";
private $comentario_evaluado = "";
private $conformidad = "";
private $id_eval_obreros = 0;

public function __construct($dataCliente = [], $conexion = NULL) {

    if (!empty($dataCliente)) {

        if (isset($dataCliente['cedula_usuario'])) {
            $this->cedula_usuario = trim($dataCliente['cedula_usuario']);
        }

        if (isset($dataCliente['id_eval_obreros'])) {
            $this->id_eval_obreros = (int)$dataCliente['id_eval_obreros'];
        }

        if (isset($dataCliente['comentario_supervisor'])) {
            $this->comentario_supervisor = trim($dataCliente['comentario_supervisor']);
        }

        if (isset($dataCliente['comentario_evaluado'])) {
            $this->comentario_evaluado = trim($dataCliente['comentario_evaluado']);
        }

        if (isset($dataCliente['conformidad'])) {
            $this->conformidad = trim($dataCliente['conformidad']);
        }
    }

    if ($conexion != NULL) {
        $this->conexion = $conexion;
    }
}

public function getIdEvalObrero(): int {
    return $this->id_eval_obreros;
}

public function setIdEvalObrero(int $id): void {
    $this->id_eval_obreros = $id;
}

public function getCedulaUsuario(): string {
    return $this->cedula_usuario;
}

// ============================================================
// 1. VALIDAR QUE EL USUARIO ES EL EVALUADO
// ============================================================
public function sql_buscar_por_id_y_evaluado(string $cedula): string {
    return sprintf("
        SELECT eo.id_eval_obreros
        FROM evaluacion_obreros eo
        JOIN usuarios u ON u.id_usuario = eo.evaluado_id
        WHERE eo.id_eval_obreros = %d
          AND u.cedula_usuario = '%s'
        LIMIT 1;
    ",
    $this->id_eval_obreros,
    addslashes($cedula));
}

// ============================================================
// 2. VALIDAR QUE EL USUARIO ES SUPERVISOR DEL EVALUADOR
// ============================================================
public function sql_buscar_por_id_y_supervisor(string $cedula): string {
    return sprintf("
        SELECT eo.id_eval_obreros
        FROM evaluacion_obreros eo
        JOIN usuarios ev       ON ev.id_usuario   = eo.evaluador_id
        JOIN cargos   c_ev     ON c_ev.id_cargo   = ev.id_cargo
        JOIN organizaciones org_ev  ON org_ev.id_org = c_ev.id_org

        JOIN organizaciones org_sup ON org_sup.id_org = org_ev.padre_id
        JOIN cargos   c_sup    ON c_sup.id_org    = org_sup.id_org
        JOIN usuarios sup      ON sup.id_cargo    = c_sup.id_cargo

        WHERE eo.id_eval_obreros = %d
          AND sup.cedula_usuario = '%s'
        LIMIT 1;
    ",
    $this->id_eval_obreros,
    addslashes($cedula));
}

// ============================================================
// 3. RELACIONES (evaluado, evaluador)
// ============================================================
public static function sql_relaciones_por_cedula(string $cedula, int $idEval): string {
    return sprintf("
        SELECT 
            u_eval.cedula_usuario   AS cedula_evaluado,
            u_eval.nombre_completo  AS nombre_evaluado,
            c_eval.nombre_cargo     AS cargo_evaluado,
            org_eval.nombre         AS area_ocupacional,
            uf.nombre_ubicacion     AS ubicacion_fisica,
            u_eval.ubicacion_administrativa AS ubicacion_evaluado,
            eo.tiempo_puesto,
            u_eval.fecha_ingreso,

            u_ev.cedula_usuario     AS cedula_evaluador,
            u_ev.nombre_completo    AS nombre_evaluador,
            c_ev.nombre_cargo       AS cargo_evaluador,
            org_ev.nombre           AS ubicacion_evaluador

        FROM usuarios u_eval
        JOIN cargos c_eval        ON c_eval.id_cargo = u_eval.id_cargo
        JOIN organizaciones org_eval ON org_eval.id_org = c_eval.id_org
        JOIN ubicacion_fisica uf ON uf.id_uf = u_eval.id_uf

        JOIN evaluacion_obreros eo ON eo.evaluado_id = u_eval.id_usuario

        JOIN usuarios u_ev        ON u_ev.id_usuario = eo.evaluador_id
        JOIN cargos c_ev          ON c_ev.id_cargo   = u_ev.id_cargo
        JOIN organizaciones org_ev ON org_ev.id_org  = c_ev.id_org

        WHERE u_eval.cedula_usuario = '%s'
          AND eo.id_eval_obreros = %d
        LIMIT 1;
    ",
    addslashes($cedula),
    $idEval);
}

// ============================================================
// 4. DATOS DE LA EVALUACIÓN
// ============================================================
public function sql_buscar(): string {
    return sprintf("
        SELECT 
            eo.*,
            u_eval.cedula_usuario AS cedula_evaluado,
            u_ev.cedula_usuario   AS cedula_evaluador,
            rc.nombre_rango       AS nombre_rango
        FROM evaluacion_obreros eo

        JOIN usuarios u_eval ON u_eval.id_usuario = eo.evaluado_id
        JOIN usuarios u_ev   ON u_ev.id_usuario   = eo.evaluador_id
        JOIN rangos_calificacion rc ON rc.rango_id = eo.rango_id

        WHERE eo.id_eval_obreros = %d
        LIMIT 1;
    ",
    $this->id_eval_obreros);
}

// ============================================================
// 5. FACTORES
// ============================================================
public static function sql_factores(): string {
    return "
        SELECT factor_id, nombre_factor, valor_factor
        FROM factores
        ORDER BY factor_id;
    ";
}

// ============================================================
// 6. CRITERIOS
// ============================================================
public static function sql_criterios(): string {
    return "
        SELECT criterio_id, factor_id, codigo_criterio, descripcion_criterio
        FROM criterios
        ORDER BY factor_id, criterio_id;
    ";
}

// ============================================================
// 7. SELECCIONADOS
// ============================================================
public static function sql_seleccionados(int $idEval): string {
    return "
        SELECT criterio_id, puntaje_obtenido
        FROM detalles_evaluacion_obreros
        WHERE id_eval_obreros = $idEval;
    ";
}

// ============================================================
// 8. ACTUALIZAR COMENTARIO DEL SUPERVISOR
// ============================================================
public function sql_update_comentario_supervisor(): string {
    return sprintf("
        UPDATE evaluacion_obreros
        SET comentario_supervisor = '%s'
        WHERE id_eval_obreros = %d
        RETURNING id_eval_obreros;
    ",
    addslashes($this->comentario_supervisor),
    $this->id_eval_obreros);
}

// ============================================================
// 9. ACTUALIZAR COMENTARIO DEL EVALUADO
// ============================================================
public function sql_update_comentario_evaluado(): string {
    return sprintf("
        UPDATE evaluacion_obreros
        SET comentario_evaluado = '%s',
            conformidad = '%s'
        WHERE id_eval_obreros = %d
        RETURNING id_eval_obreros;
    ",
    addslashes($this->comentario_evaluado),
    addslashes($this->conformidad),
    $this->id_eval_obreros);
}

}

?>
