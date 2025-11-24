<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

include_once  "../clases/GestionObjetivos.php";

$odi = new GestionObjetivo([],$this);
/*
$sql = $odi->sql_listarObjetivo();
            
$respuesta = $this->ejecutarConsultaBdds($sql);
*/

$respuesta = $odi->listarOdi();

return $respuesta;