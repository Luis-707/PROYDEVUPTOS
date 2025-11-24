<?php
include_once "../clases/GestionObjetivos.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

//$editarObj = new GestionObjetivo( $dataCliente['_post']);
//$sql = $editarObj->sql_buscar_odi();
//$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$editarObj = new GestionObjetivo($dataCliente['_post']);

$sql = $editarObj->sql_buscar_odi_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['id_odi'].' No Existe';
 
}else{
  $sql=$editarObj->sql_actualizar_odi();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_odi'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;
