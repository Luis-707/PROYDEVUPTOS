<?php
include_once "../clases/Evaluador.php";

$dataCliente['_post']['id_evaluador'] = $dataCliente['_post']['otros_datos'];


$evaluador = new Evaluador($dataCliente['_post']['id_evaluador'] );

  $sql=$evaluador->sql_buscar_evaluadores();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;