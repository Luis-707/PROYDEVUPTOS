<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

include_once "../clases/EvaluacionAdministrativos.php";

$data=$dataCliente['_post'];

// Instanciar clase
$evalAdmin = new EvaluacionesAdministrativos($dataCliente['_post']);

// Buscar si existe el registro
$sql = $evalAdmin->sql_buscarPorId(); // o mejor sql_buscarPorId
$respuesta = $this->ejecutarConsultaBdds($sql);


if (count($respuesta) == 0) {
    $respuesta = $data['id_eval_admin'] . ' No Existe';
} else {

    $sql=$evalAdmin->sql_actualizar_periodo();
  $respuesta=$this->ejecutarConsultaBdds($sql);

}

$respuesta = $this->ejecutarConsultaBdds($sql);
  $respuesta = $this->servicio($data, 'l_evalAdministrativos');
return $respuesta;

 

/*
include_once "../clases/Supervisor.php";


 $data=$dataCliente['_post'];

$cargo = new Supervisor( $dataCliente['_post']);
$sql = $cargo->sql_buscar_supervisores();
$respuesta = $this->ejecutarConsultaBdds($sql);

$cargo = new Supervisor($dataCliente['_post']);

$sql = $cargo->sql_buscar_supervisores();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['id_usuario'].' No Existe';
 
}else{
  $sql=$cargo->sql_actualizar_supervisor();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_supervisores'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;*/