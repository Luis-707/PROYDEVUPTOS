<?php

/**
 *  selecciona de la base de datos los usuarios
 * 
 *  @return array true o false en la data.
 *  @author Roberson Zerpa 
 * 
 */
/*
 * recursos: 
 *       ejecutar sqls: $respuesta = $this->ejecutarConsultaBdds($cadenaSql);
 * array  $dataCliente: contiene los datos enviados desde las vista o interfaz
 * llamar servicio dede desvicio:
 *      %this->servicio([array]$dataEviada,'nombreservicio')
 */

/*
$data['_post']['login']
*/

// Habilitar reporte de errores
//error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

include_once  "../clases/Usuario.php";

$usuario = new Usuario([],$this);
/*
$sql = $usuario->Sql_listar();
             
$respuesta = $this->ejecutarConsultaBdds($sql);
*/

  $respuesta = $usuario->listar();

  return $respuesta;
 ?>