<?php
include_once "../clases/DatosEvaluados.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

$evaluados = new DatosEvaluados( $dataCliente['_post']);
$sql = $evaluados->sql_buscar_evaluados();
$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$evaluados = new DatosEvaluados($dataCliente['_post']);

$sql = $evaluados->sql_buscar_evaluados();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['cedula_usuario'].' No Existe';
 
}else{
  $sql=$evaluados->sql_actualizar_datos_evaluados();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_evaluados'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;