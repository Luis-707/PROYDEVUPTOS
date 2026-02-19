<?php

class EvaluacionesObreros {

    private $conexion;

    private $id_eval_obreros = 0;
    private $id_usuario = 0;
    private $id_evaluado = 0;
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluacion = "";
    private $estado_eval_obreros = "";

    public function __construct($dataCliente = array(), $conexion = NULL) {

        if (isset($dataCliente['id_eval_obreros'])) {
            $this->id_eval_obreros = $dataCliente['id_eval_obreros'];
        }
        if (isset($dataCliente['id_usuario'])) {
            $this->id_usuario = $dataCliente['id_usuario'];
        }
        if (isset($dataCliente['id_evaluado'])) {
            $this->id_evaluado = $dataCliente['id_evaluado'];
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
        if (isset($dataCliente['estado_eval_obreros'])) {
            $this->estado_eval_obreros = $dataCliente['estado_eval_obreros'];
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
                (id_usuario, id_evaluado, fecha_inicio, fecha_cierre, periodo_evaluacion, estado_eval_obreros)
             VALUES (%d, %d, '%s', '%s', '%s', '%s')
             RETURNING id_eval_obreros;",
            $this->id_usuario,
            $this->id_evaluado,
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
             SET estado_eval_obreros = '%s' 
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
             WHERE id_evaluado = %d
               AND periodo_evaluacion = '%s'
               AND fecha_inicio = '%s'
               AND fecha_cierre = '%s'
             LIMIT 1;",
            $this->id_evaluado,
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

    public function sql_listar_eval_obreros(): string {
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
            ORDER BY u.cedula_usuario;
        ";
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

    public function listarEvalObreros() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_listar_eval_obreros());
        }
        return "No se ha definido la conexión";
    }
}

?>