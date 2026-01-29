<?php
class AreaOcupacional {

    private $conexion;
    
    public function __construct($dataCliente=array(''), $conexion=null){

        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }
    public static function sql_listar(): string {
        return "SELECT id_ao, nombre_ao FROM area_ocupacional ORDER BY nombre_ao;";
    }
}