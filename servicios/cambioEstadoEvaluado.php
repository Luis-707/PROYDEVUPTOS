<?php
include_once "../clases/Evaluado.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

//$editarObj = new GestionObjetivo( $dataCliente['_post']);
//$sql = $editarObj->sql_buscar_odi();
//$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$editarEstadoEval = new Evaluado($dataCliente['_post']);

$sql = $editarEstadoEval->sql_buscar_usuario_eval_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['id_usuario'].' No Existe';
 
}else{
  $sql=$editarEstadoEval->sql_actualizar_estado_usuario_eval();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_user_evaluado'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;
