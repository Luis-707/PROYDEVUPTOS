<?php
class Objetivo {

    private $conexion;

  public function __construct($dataCliente=array(''), $conexion=null){


    if ($conexion != NULL) {
        $this->conexion = $conexion;
    }

  }
  public static function sql_listar(): string {
    return "SELECT id_odi, nombre_objetivo, peso_objetivo FROM objetivos_desempeno_individual;";
  }

  public function listar_objetivos() {
    if ($this->conexion != NULL) {
        return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
    }
    return "No se ha definido la conexión";
  }
}