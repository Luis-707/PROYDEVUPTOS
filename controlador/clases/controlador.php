<?php
//include_once "Log.php";
include_once "config/configuracion.php";
include_once "Pg_Bdd.php";

class Controlador
{
    private $service = '';
    //private $conexionesBdd_Sice = array();
    
    public function __construct($servicio){
        $this->service = $servicio;
    }

    public function servicio($dataCliente = array(),$servicio=false){
        //print_r($dataCliente);

        if ($servicio) {
            $this->service = $servicio;
        }
        
        if(file_exists("../servicios/".$this->service.".php")){
            return include_once "../servicios/".$this->service.".php";
        }else{
            return "Servicio ".$this->service." no existe.";
        }
    }
    
    /**
     * Ejecutara una sentencia DLL en la o las bases de datos del array de bases de datos indicadas en 
     * el archivo de conexion.
     * 
     * @param $sql: sentencia DLL (sql) a ejecutar en la base de datos, esta ya debe estar preparada o 
     *              formateada (statemen)
     * @author Luis Duran UPTOS 
     */
    private function ejecutarConsultaBdds($sql){
        $respuestaData = array();
        for ($i=0, $aux=0; $i < count(ARRAY_CONEXION_BDDS); $i++) { 
            
            $conexionesBddSice = new BDD(ARRAY_CONEXION_BDDS[$i]['SERVIDOR'], ARRAY_CONEXION_BDDS[$i]['USUARIO'], ARRAY_CONEXION_BDDS[$i]['CLAVE'], ARRAY_CONEXION_BDDS[$i]['BD'], ARRAY_CONEXION_BDDS[$i]['PUERTO']);               

            $respuestaSql = $conexionesBddSice->obtenerRespSQL($sql);
            
            if($respuestaSql){
                for ($j=0; $j < count($respuestaSql); $j++) { 
                    $respuestaSql[$j]['SEDE'] = ARRAY_CONEXION_BDDS[$i]['SEDE'];
                }
                
                $respuestaData[$aux++] = $respuestaSql;

                }
                
           $conexionesBddSice->cerrarConexion();
        } // fin for

        return $respuestaData;

    }


}
?>
