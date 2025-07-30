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
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
//  */
 include_once "../clases/Usuario.php";

  //$dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
  // $dataCliente['_post'];

  $usuario = new Usuario( $dataCliente['_post']);
  $sql = $usuario->sql_buscar();
  $respuesta = $this->ejecutarConsultaBdds($sql);
/*  echo "---".count($respuesta)."---";
  die(print_r($respuesta));
*/
  if (count($respuesta) == 0) {     
    $sql = $usuario->sql_guardar();

    $respuesta = $this->ejecutarConsultaBdds($sql);
  }else{
    $respuesta = $dataCliente['_post']['login'].' Ya Exite';
  }
  
  //die(print_r($respuesta[0]));
//die(print_r($data));

 //die($sql);                    
  

  return $respuesta;
 ?>