<?php
include_once "../clases/Usuario3.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

$usuario = new Usuario( $dataCliente['_post']);
$sql = $usuario->sql_buscar();
$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$usuario = new Usuario($dataCliente['_post']);

$sql = $usuario->sql_buscar();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['cedula_usuario'].' No Existe';
 
}else{
  $sql=$usuario->sql_actualizar();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_user'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;
