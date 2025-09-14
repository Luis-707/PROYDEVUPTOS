<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

include_once  "../clases/Usuario3.php";

$usuario = new Usuario([],$this);
/*
$sql = $usuario->Sql_listar();
             
$respuesta = $this->ejecutarConsultaBdds($sql);
*/

  $respuesta = $usuario->listar();

  return $respuesta;