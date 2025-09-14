<?php
include_once "../clases/Usuario3.php";

  $dataCliente['_post']['cedula_usuario'] = $dataCliente['_post']['otros_datos'];
  
//print_r($dataCliente['_post']);

  $usuario = new Usuario($dataCliente['_post']);

  $sql = $usuario->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);

  if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['login'].' No Existe';
  }else{
    $sql = $usuario->sql_Eliminar();
    $respuesta = $this->ejecutarConsultaBdds($sql);
  }
  
  return $respuesta;