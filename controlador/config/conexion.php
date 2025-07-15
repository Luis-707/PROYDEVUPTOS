<?php
    include_once __DIR__."/../../config/conexion.php";

    $BDDS = array();
    for ($i=0, $j=0; $i < count($bdds); $i++) { 
        $BDDS[$j]['BD']       = $bdds[$i]['BD'];
        $BDDS[$j]['USUARIO']  = $bdds[$i]['USUARIO'];
        $BDDS[$j]['CLAVE']    = $bdds[$i]['CLAVE'];
        $BDDS[$j]['SERVIDOR'] = $bdds[$i]['SERVIDOR'];
        $BDDS[$j]['SEDE']     = $bdds[$i]['SEDE'];
        $BDDS[$j]['PUERTO']   = $bdds[$i]['PUERTO'];
        $j++;
    }

?>