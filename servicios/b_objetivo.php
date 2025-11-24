<?php
include_once "../clases/GestionObjetivos.php";

$dataCliente['_post']['nombre_objetivo'] = $dataCliente['_post']['otros_datos'];


$bObj = new GestionObjetivo($dataCliente['_post']['nombre_objetivo'] );

  $sql=$bObj->sql_buscar_odi();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;