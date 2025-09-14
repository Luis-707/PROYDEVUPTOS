<?php
include_once "../clases/Usuario3.php";

$dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];


$usuario = new Usuario($dataCliente['_post']['cedula_usuario'] );

  $sql=$usuario->sql_buscar();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;