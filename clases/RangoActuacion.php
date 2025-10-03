<?php
class RangoActuacion {
    private $conexion;

  public function __construct($dataCliente=array(''), $conexion=null){


    if($conexion!=null){
      $this->conexion=$conexion;
    }
  }


  public static function sql_listar(): string {
    return "SELECT id_rango,rango_actuacion, puntaje_minimo, puntaje_maximo FROM rango_actuacion;";
  }


   public function listar_rango_actuacion() {
        if ($this->conexion != NULL) {
            return $this->conexion->ejecutarConsultaBdds(self::sql_listar());
        }
        return "No se ha definido la conexión";
    }

}