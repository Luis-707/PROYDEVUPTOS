<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

include_once  "../clases/Usuario3.php";

$usuario = new Usuario([],$this);

$respuesta = $usuario->listarCargos();

return $respuesta;