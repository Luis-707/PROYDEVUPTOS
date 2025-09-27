<?php

class Competencia {

    private $conexion;

  public function __construct($dataCliente=array(''), $conexion=null){


    if ($conexion != NULL) {
        $this->conexion = $conexion;
    }
  }

  public static function sql_listar(): string {
    return "SELECT id_competencia, nombre_competencia, peso_competencia FROM competencias;";
  }



    public function listar_competencias() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
        }
        return "No se ha definido la conexión";
    }

}