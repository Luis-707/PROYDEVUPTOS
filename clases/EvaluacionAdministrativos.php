<?php

class EvaluacionesAdministrativos {
    // Propiedades privadas actualizadas
    private $conexion;
    private $id_eval_admin = 0;
    private $evaluador_id = 0;  // Cambiado de id_usuario
    private $evaluado_id = 0;   // Cambiado de id_evaluado
    private $id_rango = 0;      // Nuevo campo del esquema
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluado = "";
    private $estado_eval_admin = "";
    // Opcionales del esquema (agregados para completitud)
    private $comentario_supervisor = "";
    private $comentario_evaluado = "";
    private $puntaje_final = 0;
    private $conformidad = "";

    // Constructor actualizado
    public function __construct($dataCliente=array(''), $conexion = NULL) {
        if (isset($dataCliente['id_eval_admin'])) {
            $this->id_eval_admin = $dataCliente['id_eval_admin'];
        }
        if (isset($dataCliente['evaluador_id'])) {  // Cambiado
            $this->evaluador_id = $dataCliente['evaluador_id'];
        }
        if (isset($dataCliente['evaluado_id'])) {   // Cambiado
            $this->evaluado_id = $dataCliente['evaluado_id'];
        }
        if (isset($dataCliente['id_rango'])) {
            $this->id_rango = $dataCliente['id_rango'];
        }
        if (isset($dataCliente['fecha_inicio'])) {
            $this->fecha_inicio = $dataCliente['fecha_inicio'];
        }
        if (isset($dataCliente['fecha_cierre'])) {
            $this->fecha_cierre = $dataCliente['fecha_cierre'];
        }
        if (isset($dataCliente['periodo_evaluado'])) {
           $this->periodo_evaluado = trim($dataCliente['periodo_evaluado']);
        }
        if (isset($dataCliente['estado_eval_admin'])) {
            $this->estado_eval_admin = $dataCliente['estado_eval_admin'];
        }
        // Campos opcionales
        if (isset($dataCliente['comentario_supervisor'])) {
            $this->comentario_supervisor = $dataCliente['comentario_supervisor'];
        }
        if (isset($dataCliente['comentario_evaluado'])) {
            $this->comentario_evaluado = $dataCliente['comentario_evaluado'];
        }
        if (isset($dataCliente['puntaje_final'])) {
            $this->puntaje_final = $dataCliente['puntaje_final'];
        }
        if (isset($dataCliente['conformidad'])) {
            $this->conformidad = $dataCliente['conformidad'];
        }
        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    // Setters y Getters actualizados
    public function setEvaluadoId(int $id): void {
        $this->evaluado_id = $id;
    }
    public function getEvaluadoId(): int {
        return $this->evaluado_id;
    }

    public function setEvaluadorId(int $id): void {
        $this->evaluador_id = $id;
    }
    public function getEvaluadorId(): int {
        return $this->evaluador_id;
    }

    public function setIdRango(int $id): void {
        $this->id_rango = $id;
    }
    public function getIdRango(): int {
        return $this->id_rango;
    }

    // Método INSERT actualizado
    public function sql_guardar_eval_administrativos(): string {
        return sprintf(
            "INSERT INTO evaluacion_administrativos 
                (evaluador_id, evaluado_id, fecha_inicio, fecha_cierre, periodo_evaluado, estado_eval_admin) 
             VALUES (%d, %d, '%s', '%s', '%s', '%s')
             RETURNING id_eval_admin;",
            $this->evaluador_id,
            $this->evaluado_id,
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluado),
            'Iniciada'
        );
    }

public function sql_existe_duplicado_periodo(): string {
    return sprintf(
        "SELECT id_eval_admin
         FROM evaluacion_administrativos
         WHERE evaluado_id = %d
           AND TRIM(periodo_evaluado) = '%s'
         LIMIT 1;",
        $this->evaluado_id,
        addslashes($this->periodo_evaluado)
    );
}

public function sql_existe_duplicado_periodo_edicion(): string {
    return sprintf(
        "SELECT id_eval_admin
         FROM evaluacion_administrativos
         WHERE evaluado_id = %d
           AND TRIM(periodo_evaluado) = '%s'
           AND id_eval_admin != %d
         LIMIT 1;",
        $this->evaluado_id,
        addslashes($this->periodo_evaluado),
        $this->id_eval_admin
    );
}

    // Búsquedas actualizadas (ejemplos clave)
    public function sql_buscarPorEvaluado(): string {
        return sprintf(
            "SELECT ea.id_eval_admin, ea.evaluado_id
             FROM evaluacion_administrativos ea
             WHERE ea.evaluado_id = %d;",
            $this->evaluado_id
        );
    }

    public function sql_buscarPorEvaluador(): string {  // Nuevo/renombrado
        return sprintf(
            "SELECT * FROM evaluacion_administrativos WHERE evaluador_id = %d;",
            $this->evaluador_id
        );
    }

    // Listado principal actualizado
    public function sql_listar_eval_administrativos(): string {
        return "
            SELECT u.cedula_usuario, u.nombre_completo, u.ubicacion_administrativa, c.cargo_evaluado, 
                   ea.estado_eval_admin, ea.periodo_evaluado, EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio, 
                   ea.id_eval_admin, ea.evaluado_id, ea.evaluador_id
            FROM evaluacion_administrativos ea
            JOIN usuarios ue ON ea.evaluado_id = ue.id_usuario  -- evaluado
            JOIN cargos_evaluados c ON ue.id_cargo = c.id_cargo_evaluado  -- ajusta según tu esquema
            JOIN usuarios u ON ue.id_usuario = u.id_usuario
            ORDER BY u.cedula_usuario;
        ";
    }

    // Resto de métodos (eliminar, actualizar, etc.) siguen iguales, ya que usan id_eval_admin
    public function sql_eliminar_eval_administrativos(): string {
        return sprintf(
            "DELETE FROM evaluacion_administrativos WHERE id_eval_admin = %d;",
            $this->id_eval_admin
        );
    }

    public function sql_actualizar_periodo(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos
             SET fecha_inicio = '%s',
                 fecha_cierre = '%s',
                 periodo_evaluado = '%s'
             WHERE id_eval_admin = %d
             RETURNING id_eval_admin;",
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluado),
            $this->id_eval_admin
        );
    }

    // *** Listar evaluados ***
    public static function sql_listar_evaluacionesAdmin(int $idUsuario): string {
        return sprintf("
            SELECT 
                ea.id_eval_admin,
                u_evaluado.cedula_usuario AS cedula_evaluado,
                u_evaluado.nombre_completo AS nombre_evaluado,
                u_evaluado.ubicacion_administrativa,
                ea.estado_eval_admin,
                c.nombre_cargo AS cargo_evaluado,  -- asumiendo que existe este campo
                ea.periodo_evaluado,
                EXTRACT(YEAR FROM ea.fecha_inicio) AS anio_inicio  -- usar fecha_inicio que es DATE
                FROM evaluacion_administrativos ea
            JOIN usuarios u_evaluador ON ea.evaluador_id = u_evaluador.id_usuario
            JOIN usuarios u_evaluado ON ea.evaluado_id = u_evaluado.id_usuario
            JOIN cargos c ON u_evaluado.id_cargo::integer = c.id_cargo::integer  -- CAST en el JOIN problemático
            WHERE u_evaluador.id_usuario = %d;", $idUsuario);
        }

        //Listar evaluados en el select de evaluados especificos

        public static function sql_listar_evaluadosAd(int $idUser): string {
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
                AND sub.ID_USUARIO != jefe.ID_USUARIO;", $idUser);
            }

            //Listar evaluados en el select de evaluados especificos en caso de supervisor

        public static function sql_listar_evaluadosES(int $UserID): string {
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
                JOIN ORGANIZACIONES uh ON (
                    uh.ID_ORG = uj.ID_ORG          -- Misma unidad del jefe
                    OR uh.PADRE_ID = uj.ID_ORG      -- Hijas directas
                    )
                JOIN CARGOS c ON c.ID_ORG = uh.ID_ORG
                JOIN USUARIOS sub ON sub.ID_CARGO = c.ID_CARGO 
                AND sub.ESTADO_USUARIO = 'Activo'
                AND sub.ID_USUARIO != jefe.ID_USUARIO
                WHERE jefe.ID_USUARIO = %d
                    AND (
                    -- Misma unidad: SOLO no-jefes (ES_JEFE = false)
                    (uh.ID_ORG = uj.ID_ORG AND c.ES_JEFE = false)
                    OR 
                    -- Nivel inferior: SOLO jefes (ES_JEFE = true)
                    (uh.PADRE_ID = uj.ID_ORG AND c.ES_JEFE = true)
                    );", $UserID);
                }

            // Método para actualizar el estado del usuario (Activo, Inactivo) según id_usuario
    public function sql_actualizar_estado_evalAdmin(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos SET estado_eval_admin = '%s' WHERE id_eval_admin = %d;",
            $this->estado_eval_admin,
            $this->id_eval_admin
        );
    }

            // Método para buscar por id_competencia
    public function sql_buscar_evalAdmin_id(): string {
        return sprintf(
            "SELECT * FROM evaluacion_administrativos WHERE id_eval_admin = %d;",
            $this->id_eval_admin
        );
    }

    //Método para buscar por id

    public function sql_buscarPorId(): string {
        return sprintf(
            "SELECT id_eval_admin 
             FROM evaluacion_administrativos 
             WHERE id_eval_admin = %d;",
            $this->id_eval_admin
        );
    }

    // Métodos de ejecución sin cambios
    public function existeEvaluacionDuplicada() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_existe_evaluacion());
        }
        return [];
    }

    public function guardarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_guardar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

    public function eliminarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_eliminar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

    // Getters para fechas (sin cambios)
    public function getFechainicio(): string {
        return $this->fecha_inicio;
    }
    public function getFechacierre(): string {
        return $this->fecha_cierre;
    }
    public function getPeriodoevaluado(): string {
        return $this->periodo_evaluado;
    }

    // Getters adicionales para campos nuevos
    public function getComentarioSupervisor(): string {
        return $this->comentario_supervisor;
    }
    public function getPuntajeFinal(): int {
        return $this->puntaje_final;
    }
}