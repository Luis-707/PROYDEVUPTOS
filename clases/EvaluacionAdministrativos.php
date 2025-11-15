<?php

class EvaluacionesAdministrativos {
    // Propiedades privadas
    private $conexion;
    private $id_eval_admin = 0;
    private $id_usuario = 0;
    private $id_evaluado = 0;
    private $fecha_inicio = "";
    private $fecha_cierre = "";
    private $periodo_evaluado = "";

    // Constructor
    public function __construct($dataCliente=array(''), $conexion = NULL) {
        if (isset($dataCliente['id_eval_admin'])) {
            $this->id_eval_admin = $dataCliente['id_eval_admin'];
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
        if (isset($dataCliente['periodo_evaluado'])) {
            $this->periodo_evaluado = $dataCliente['periodo_evaluado'];
        }
        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    //setters y getters

  // 🔹 Getters y Setters
  public function setIdEvaluado(int $id): void {
    $this->id_evaluado = $id;
}
public function getIdEvaluado(): int {
    return $this->id_evaluado;
}

public function setIdEvalAdmin(int $id): void {
    $this->id_eval_admin = $id;
}
public function getIdEvalAdmin(): int {
    return $this->id_eval_admin;
}

public function getIdUsuario(): int {
    return $this->id_usuario;
}

    // Método para guardar nuevo registro en evaluacion_administrativos
    /*public function sql_guardar_eval_administrativos(): string {
        return sprintf(
            "INSERT INTO evaluacion_administrativos (id_usuario, id_evaluado, fecha_inicio, fecha_cierre, periodo_evaluado) 
            VALUES (%d, %d, '%s', '%s', '%s');",
            $this->id_usuario,
            $this->id_evaluado,
            $this->fecha_inicio,
            $this->fecha_cierre,
            $this->periodo_evaluado
        );
    }*/

    public function sql_guardar_eval_administrativos(): string {
        return sprintf(
            "INSERT INTO evaluacion_administrativos 
                (id_usuario, id_evaluado, fecha_inicio, fecha_cierre, periodo_evaluado) 
             VALUES (%d, %d, '%s', '%s', '%s')
             RETURNING id_eval_admin;",
            $this->id_usuario,   // evaluador (de la sesión)
            $this->id_evaluado,  // evaluado (del formulario)
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            addslashes($this->periodo_evaluado)
        );
    }

    // Método para editar un registro en evaluacion_administrativos
    public function sql_actualizar_eval_administrativos(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos 
            SET id_evaluado = %d, fecha_inicio = '%s', fecha_cierre = '%s', periodo_evaluado = '%s'
            WHERE id_evaluado = %d;",
            $this->fecha_inicio,
            $this->fecha_cierre,
            $this->periodo_evaluado,
            $this->id_evaluado
        );
    }

    // Método para eliminar un registro en evaluacion_administrativos
    public function sql_eliminar_eval_administrativos(): string {
        return sprintf(
            "DELETE FROM evaluacion_administrativos WHERE id_eval_admin = %d;",
            $this->id_eval_admin
        );
    }

     // Método UPDATE para fechas y periodo
     /*public function sql_actualizar_periodo(): string {
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
    }*/

   /* public function sql_actualizar_periodo(): string {
        return sprintf(
            "UPDATE evaluacion_administrativos
             SET periodo_evaluado = '%s',
                 fecha_inicio = '%s',
                 fecha_cierre = '%s'
             WHERE id_eval_admin = %d
             RETURNING id_eval_admin;",
            addslashes($this->periodo_evaluado),
            addslashes($this->fecha_inicio),
            addslashes($this->fecha_cierre),
            $this->getIdEvalAdmin()
        );
    }*/
   // Buscar evaluación por id_evaluado
   public function sql_buscarPorEvaluado(): string {
    return sprintf(
        "SELECT ea.id_eval_admin, ea.id_evaluado
         FROM evaluacion_administrativos ea
         WHERE ea.id_evaluado = %d;",
        $this->id_evaluado
    );
}

public static function sql_buscar_evaluador_por_usuario(int $idUsuario): string {
    return sprintf(
        "SELECT id_evaluador 
         FROM evaluadores 
         WHERE id_usuario = %d 
         LIMIT 1;",
        $idUsuario
    );
}

    public function sql_buscarPorUsuario(): string {
        return sprintf(
            "SELECT * FROM evaluacion_administrativos WHERE id_usuario = %d;",
            $this->id_usuario
        );
    }

    public function sql_listar_eval_administrativos(): string {
        return "
            SELECT u.cedula_usuario, c.cargo_evaluado, ea.periodo_evaluado, ea.id_eval_admin, ea.id_evaluado, ea.id_usuario
            FROM evaluacion_administrativos ea
            JOIN evaluados e ON ea.id_evaluado = e.id_evaluado
            JOIN cargos_evaluados c ON e.id_cargo_evaluado = c.id_cargo_evaluado
            JOIN usuarios u ON e.id_usuario = u.id_usuario
            ORDER BY u.cedula_usuario;
        ";
    }
    
    // Método que ejecuta la consulta y devuelve el resultado
    
    public function eliminarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_eliminar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

    // Método para ejecutar el guardado si la conexión está definida
    public function guardarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_guardar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

    // Método que ejecuta la consulta y devuelve el resultado

    public function actualizarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_actualizar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function buscarPorUsuario() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_buscarPorUsuario());
        }
        return "No se ha definido la conexión";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarEvalAdministrativos() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds($this->sql_listar_eval_administrativos());
        }
        return "No se ha definido la conexión";
    }

     // Método estático que devuelve la consulta SQL para listar usuarios con su rol
     public static function sql_listar_datos(): string {
        return "
            SELECT e.id_evaluado, 
               u.id_usuario, 
               u.cedula_usuario, 
               r.rol
        FROM evaluados e
        INNER JOIN usuarios u ON e.id_usuario = u.id_usuario
        INNER JOIN roles_sistema r ON u.rol_id = r.rol_id
        WHERE r.rol = 'Evaluado';
        ";
    }

    // Método que ejecuta la consulta y devuelve el resultado
    public function listarDatos() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar_datos());
        }
        return "No se ha definido la conexión";
    }


    public function getFechainicio(): string {
        return $this->fecha_inicio;
    }

    public function getFechacierre(): string {
        return $this->fecha_cierre;
    }

    public function getPeriodoevaluado(): string {
        return $this->periodo_evaluado;
    }

}
?>
