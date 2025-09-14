<?php
include_once "../clases/CargosEvaluador.php";

$dataCliente['_post']['id_evaluador'] = $dataCliente['_post']['otros_datos'];


$evaluador = new CargosEvaluador($dataCliente['_post']['id_evaluador'] );

  $sql=$evaluador->sql_buscar_evaluadores();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;