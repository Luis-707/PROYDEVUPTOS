<?php
include_once "config/configuracion.php";

class Seguridad {

    private $claveSecreta ='UPTOS precongreso';
    private $listaNegra = "config/lista_negra_token.php";
    private $listaBlanca = "config/lista_blanca_token.php";

    /*
    public function __construct() {

    }
    */

    static public function identServicio($url){
        list($dir,$var) = explode("?",$url);
        $metodo = array();
        $metodo =  explode("&",$var);
        return $metodo[0];
    }


    public function siglasSistema(){
        return SISTEMA;
    }

    public function nombreSistema(){
        return DESCRIP_SISTEMA;
    }

    //public function sql_listarUsuario(){
    //    return sprintf("SELECT *from usuario where usuario = '$this->login'");
    //}


}

?>