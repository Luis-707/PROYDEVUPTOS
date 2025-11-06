<?php
include_once "../clases/DatosEvaluados.php";

$dataCliente['_post']['id_evaluado'] = $dataCliente['_post']['otros_datos'];


$evaluado = new DatosEvaluados($dataCliente['_post']['id_evaluado'] );

  $sql=$evaluado->sql_buscar_evaluados();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;