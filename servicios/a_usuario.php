<?php

/**
 *  Insertta en la tabla personal de la base de datos
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

 // Habilitar reporte de errores
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');

 include_once "../clases/Usuario.php";

  //$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
   $data=$dataCliente['_post'];
  // var_dump($data['nombres']);

  $usuario = new Usuario( $dataCliente['_post']);
  $sql = $usuario->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
  die(print_r($respuesta));
*/
  $usuario = new Usuario($dataCliente['_post']);

  $sql = $usuario->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);

  if (count($respuesta) == 0) {     
    $respuesta = $dataCliente['_post']['login'].' No Exite';
   
  }else{
    $sql=$usuario->sql_actualizar();
    $respuesta = $this->ejecutarConsultaBdds($sql);
  }


 $respuesta = $this->ejecutarConsultaBdds($sql);
  $respuesta = $this->servicio($data,'l_usuario'); // el parametro no tiene extension pero es el servicio del archivo l_usuario.php
  return $respuesta;
  
 ?>