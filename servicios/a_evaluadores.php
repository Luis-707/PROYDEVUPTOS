<?php
include_once "../clases/CargosEvaluador.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

$cargo = new CargosEvaluador( $dataCliente['_post']);
$sql = $cargo->sql_buscar_evaluadores();
$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$cargo = new CargosEvaluador($dataCliente['_post']);

$sql = $cargo->sql_buscar_evaluadores();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['id_usuario'].' No Existe';
 
}else{
  $sql=$cargo->sql_actualizar_evaluador();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_evaluadores'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;