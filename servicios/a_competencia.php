<?php
include_once "../clases/GestionCompetencia.php";

//$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
 $data=$dataCliente['_post'];
// var_dump($data['nombres']);

//$editarObj = new GestionObjetivo( $dataCliente['_post']);
//$sql = $editarObj->sql_buscar_odi();
//$respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
die(print_r($respuesta));
*/
$editarComp = new GestionCompetencia($dataCliente['_post']);

$sql = $editarComp->sql_buscar_por_id();
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {     
  $respuesta = $dataCliente['_post']['id_competencia'].' No Existe';
 
}else{
  $sql=$editarComp->sql_actualizar_competencia();
  $respuesta = $this->ejecutarConsultaBdds($sql);
}


$respuesta = $this->ejecutarConsultaBdds($sql);
$respuesta = $this->servicio($data,'l_gestionCompetencias'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
return $respuesta;
