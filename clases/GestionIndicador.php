<?php

class GestionIndicador {
    // Propiedades privadas
    private $conexion;
    private $indicador_id = 0;
    private $id_usuario = 0;
    private $indicador = "";
    private $estado_indicador = "";
    private $tipo_indicador = "";

    // Constructor
    public function __construct($data = array(), $conexion = NULL) {
        if (isset($data['indicador_id'])) {
            $this->indicador_id = $data['indicador_id'];
        }
        if (isset($data['id_usuario'])) {
            $this->id_usuario = $data['id_usuario'];
        }
        if (isset($data['indicador'])) {
            $this->indicador = $data['indicador'];
        }
        if (isset($data['estado_indicador'])) {
            $this->estado_indicador = $data['estado_indicador'];
        }
        if (isset($data['tipo_indicador'])) {
            $this->tipo_indicador = $data['tipo_indicador'];
        }
        if ($conexion !== NULL) {
            $this->conexion = $conexion;
        }
    }

    // INSERT
    public function sql_insertar_indicador(): string {
        return sprintf(
            "INSERT INTO indicadores (id_usuario, indicador, estado_indicador, tipo_indicador) 
             VALUES (%d, '%s', '%s', '%s');",
            $this->id_usuario,
            $this->indicador,
            ('Activo'),   // podrías fijarlo a 'Activo' si quieres
            $this->tipo_indicador
        );
    }

    // DELETE por indicador_id
    public function sql_eliminar_indicador(): string {
        return sprintf(
            "DELETE FROM indicadores WHERE indicador_id = %d;",
            $this->indicador_id
        );
    }

    // UPDATE por indicador_id (sin cambiar estado)
    public function sql_actualizar_indicador(): string {
        return sprintf(
            "UPDATE indicadores 
             SET indicador = '%s'
             WHERE indicador_id = %d;",
            $this->indicador,
            $this->indicador_id
        );
    }

    // UPDATE solo estado_indicador por indicador_id
    public function sql_actualizar_estado_indicador(): string {
        return sprintf(
            "UPDATE indicadores 
             SET estado_indicador = '%s' 
             WHERE indicador_id = %d;",
            $this->estado_indicador,
            $this->indicador_id
        );
    }

    // SELECT * (static)
    public static function sql_listarIndicadores(): string {
        return "SELECT * FROM indicadores;";
    }

    // Listar usando la conexión
    public function listarIndicadores() {
        if ($this->conexion !== NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listarIndicadores());
        }
        return "No se ha definido la conexión";
    }

    // Buscar por indicador (texto exacto, case-insensitive)
    public function sql_buscar_por_indicador(): string {
        return sprintf(
            "SELECT * FROM indicadores 
             WHERE UPPER(indicador) = UPPER('%s');",
            $this->indicador
        );
    }

    // Buscar por indicador_id
    public function sql_buscar_indicardor_id(): string {
        return sprintf(
            "SELECT * FROM indicadores WHERE indicador_id = %d;",
            $this->indicador_id
        );
    }

    //Listar indicadores segun el usuario

    public static function sql_listar_indicadores(string $UserID): string {
        return sprintf("
        SELECT indicador_id, indicador, id_usuario, estado_indicador FROM indicadores 
        WHERE id_usuario = %d;
        ", addslashes($UserID));
    }

    // Getters y setters
    public function getIndicadorId(): int {
        return $this->indicador_id;
    }

    public function setIndicadorId(int $id): void {
        $this->indicador_id = $id;
    }

    public function getIdUsuario(): int {
        return $this->id_usuario;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->id_usuario = $idUsuario;
    }

    public function getIndicador(): string {
        return $this->indicador;
    }

    public function setIndicador(string $indicador): void {
        $this->indicador = $indicador;
    }

    public function getEstadoIndicador(): string {
        return $this->estado_indicador;
    }

    public function setEstadoIndicador(string $estado): void {
        $this->estado_indicador = $estado;
    }

    public function getTipoIndicador(): string {
        return $this->tipo_indicador;
    }

    public function setTipoIndicador(string $tipo): void {
        $this->tipo_indicador = $tipo;
    }
}

?>