<?php 
    //include_once "config/configuracion.php"; 
    include "clases/Seguridad.php"; 
    include "clases/controlador.php";

    ini_set('display_errors',0);

    //print_r($_POST);
    
    
    $seguridad = new Seguridad();
    $servicio = Seguridad::identServicio($_SERVER['REQUEST_URI']);
    $result = "";

    $dataCliente = array("_post" => FALSE, "_get" => $servicio);

    if(!empty($_POST)){
        $dataCliente['_post'] = $_POST;
    } 
    if(!empty($_FILES)){
        $dataCliente['_files'] = $_FILES;
    } 

        $servApi = new Controlador($servicio);

        $result = $servApi->servicio($dataCliente);
        $msn = "|".$servicio."|".json_encode($dataCliente);


    $dataRespuesta = $result;
    
    //print_r($dataRespuesta);

    header("Content-Type: application/json");
    echo json_encode($dataRespuesta);
// */
?>