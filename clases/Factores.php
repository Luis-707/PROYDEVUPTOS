<?php
class Factores {

    private $conexion;

    public function __construct($dataCliente=array(''), $conexion=null){
        if ($conexion != NULL) {
            $this->conexion = $conexion;
        }
    }
    
    public static function sql_listar(): string {
        return "SELECT factor_id, nombre_factor, valor_factor 
                FROM factores 
                ORDER BY factor_id;";
    }
}