<?php

class EvaluacionesObreros {

    private $conexion;
    private $id_eval_obreros = 0;
    private $evaluador_id = 0;  // Cambiado de id_usuario
    private $evaluado_id = 0;   // Cambiado de id_evaluado
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluacion = "";
    private $estado_eval_obreros = "";

    public function __construct($dataCliente = array(), $conexion = NULL) {

        if (isset($dataCliente['id_eval_obreros'])) {
            $this->id_eval_obreros = $dataCliente['id_eval_obreros'];
        }
        if (isset($dataCliente['evaluador_id'])) {  // Cambiado
            $this->evaluador_id = $dataCliente['evaluador_id'];
        }
        if (isset($dataCliente['evaluado_id'])) {   // Cambiado
            $this->evaluado_id = $dataCliente['evaluado_id'];
        }
        if (isset($dataCliente['fecha_inicio'])) {
            $this->fecha_inicio = $dataCliente['fecha_inicio'];
        }
        if (isset($dataCliente['fecha_cierre'])) {
            $this->fecha_cierre = $dataCliente['fecha_cierre'];
        }
        if (isset($dataCliente['periodo_evaluacion'])) {
            $this->periodo_evaluacion = $dataCliente['periodo_evaluacion'];
        }
        if (isset($dataCliente['estado_eval_obrero'])) {
            $this->estado_eval_obreros = $dataCliente['estado_eval_obrero'];
        }

        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    // ============================================================
    // SQL PARA GUARDAR EVALUACIÓN
    // ============================================================

    public function sql_guardar_eval_obreros(): string {
        return sprintf(
            "INSERT INTO evaluacion_obreros 
                (evaluador_id, evaluado_id, fecha_inicio, fecha_cierre, periodo_evaluacion, estado_eval_obrero)
             VALUES (%d, %d, '%s', '%s', '%s', '%s')
             RETURNING id_eval_obreros;",
            $this->evaluador_id,
            $this->evaluado_id,
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluacion),
            ('Iniciada')
        );
    }

    // ============================================================
    // SQL PARA ACTUALIZAR PERIODO
    // ============================================================

    public function sql_actualizar_periodo_obrero(): string {
        return sprintf(
            "UPDATE evaluacion_obreros
             SET fecha_inicio = '%s',
                 fecha_cierre = '%s',
                 periodo_evaluacion = '%s'
             WHERE id_eval_obreros = %d
             RETURNING id_eval_obreros;",
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluacion),
            $this->id_eval_obreros
        );
    }

    // ============================================================
    // SQL PARA CAMBIAR ESTADO
    // ============================================================

    public function sql_actualizar_estado_evalObrero(): string {
        return sprintf(
            "UPDATE evaluacion_obreros 
             SET estado_eval_obrero = '%s' 
             WHERE id_eval_obreros = %d;",
            addslashes($this->estado_eval_obreros),
            $this->id_eval_obreros
        );
    }

    // ============================================================
    // SQL PARA VALIDAR DUPLICADOS
    // ============================================================

    public function sql_existe_evaluacion_obrero(): string {
        return sprintf(
            "SELECT id_eval_obreros 
             FROM evaluacion_obreros 
             WHERE evaluado_id = %d
               AND periodo_evaluacion = '%s'
               AND fecha_inicio = '%s'
               AND fecha_cierre = '%s'
             LIMIT 1;",
            $this->evaluado_id,
            addslashes($this->periodo_evaluacion),
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre)
        );
    }

    public function existeEvaluacionDuplicada() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_existe_evaluacion_obrero());
        }
        return [];
    }

    // ============================================================
    // SQL PARA BUSCAR POR ID
    // ============================================================

    public function sql_buscar_evalObrero_id(): string {
        return sprintf(
            "SELECT * FROM evaluacion_obreros WHERE id_eval_obreros = %d;",
            $this->id_eval_obreros
        );
    }

    // ============================================================
    // SQL PARA LISTAR EVALUACIONES
    // ============================================================

    public static function sql_listar_evaluacionesOb(int $idUsuario): string {
        return sprintf("
            SELECT 
                eo.id_eval_obreros,
                u_evaluado.cedula_usuario AS cedula_evaluado,
                u_evaluado.nombre_completo AS nombre_evaluado,
                u_evaluado.ubicacion_administrativa,
                eo.estado_eval_obrero,
                c.nombre_cargo AS cargo_evaluado,  -- asumiendo que existe este campo
                eo.periodo_evaluacion,
                EXTRACT(YEAR FROM eo.fecha_inicio) AS anio_inicio  -- usar fecha_inicio que es DATE
            FROM evaluacion_obreros eo
            JOIN usuarios u_evaluador ON eo.evaluador_id = u_evaluador.id_usuario
            JOIN usuarios u_evaluado ON eo.evaluado_id = u_evaluado.id_usuario
            JOIN cargos c ON u_evaluado.id_cargo::integer = c.id_cargo::integer  -- CAST en el JOIN problemático
            WHERE u_evaluador.id_usuario = %d;", $idUsuario);
    }

        //listar evaluados especificos
            //Listar evaluados en el select de evaluados especificos

    public static function sql_listar_evaluadosOb(int $UsuarioID): string {
        return sprintf("
            SELECT 
                sub.ID_USUARIO AS evaluado_id,
                sub.NOMBRE_COMPLETO AS subordinado,
                c.NOMBRE_CARGO AS cargo,
                uh.NOMBRE AS unidad,
                c.ES_JEFE AS es_jefe
            FROM USUARIOS jefe
            JOIN CARGOS cj ON jefe.ID_CARGO = cj.ID_CARGO
            JOIN ORGANIZACIONES uj ON cj.ID_ORG = uj.ID_ORG
            JOIN ORGANIZACIONES uh ON (uh.PADRE_ID = uj.ID_ORG OR uh.ID_ORG = uj.ID_ORG)  -- Propia + hijas directas
            JOIN CARGOS c ON c.ID_ORG = uh.ID_ORG
            JOIN USUARIOS sub ON sub.ID_CARGO = c.ID_CARGO 
            AND sub.ESTADO_USUARIO = 'Activo'
            WHERE jefe.ID_USUARIO = %d  -- Jefe específico (cambia según necesites)
            AND sub.ID_USUARIO != jefe.ID_USUARIO;", $UsuarioID);
        }
        
    // ============================================================
    // MÉTODOS EJECUTORES
    // ============================================================

    public function guardarEvalObreros() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_guardar_eval_obreros());
        }
        return "No se ha definido la conexión";
    }

    public function actualizarPeriodoObrero() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_actualizar_periodo_obrero());
        }
        return "No se ha definido la conexión";
    }

    public function actualizarEstadoObrero() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_actualizar_estado_evalObrero());
        }
        return "No se ha definido la conexión";
    }

    /*public function listarEvalObreros() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_listar_evaluacionesOb($idUsuario));
        }
        return "No se ha definido la conexión";
    }*/
}

?>