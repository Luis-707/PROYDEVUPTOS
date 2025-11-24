<?php
// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

include_once  "../clases/GestionCompetencia.php";

$comp = new GestionCompetencia([],$this);
/*
$sql = $odi->sql_listarObjetivo();
            
$respuesta = $this->ejecutarConsultaBdds($sql);
*/

$respuesta = $comp->listarCompetencias();

return $respuesta;