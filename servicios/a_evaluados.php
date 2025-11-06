<?php
include_once "../clases/Evaluados.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

$evaluado = new Evaluado( $dataCliente['_post']);
$sql = $evaluado->sql_buscar_user_evaluado();
$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$evaluado = new Evaluado($dataCliente['_post']);

$sql = $evaluado->sql_buscar_user_evaluado();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['cedula_usuario'].' No Existe';
 
}else{
  $sql=$evaluado->sql_actualizar_user_evaluado();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_evaluados'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;