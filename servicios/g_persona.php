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

 // print_r($dataCliente);

  //echo $dataCliente['_post']['nombre'];
  /*
    [_post] => Array
        [cedula] => 15740816
        [apellidos] => trujillo
        [nombres] => miguel
        [fecha_nac] => 2025-03-19
        [sexo] => Masculino
        [estado_ci] => Casado
        [nacionalidad] => EX
        [ciudad] => cumana
        [estado] => ...
        [municipio] => ...
        [parroquia] => ...
        [comunidad] => ...
        [sector] => ..
        [correo] => correo@gmail.com
        [telefono] => 321654987
        [datos] => Ingrese la información de interés notas
        [otrosDatos] => 
*/
$data = $dataCliente['_post'];

 $cadenaSql = "INSERT INTO persona (cedula,sector,nacionalidad,nombres,apellidos,sexo,estado_civil,fecha_nac,correo_elect,telefono_per,otro_datos)
                 VALUES('".$data['cedula']."',
                        '".$data['sector']."',
                        '".$data['nacionalidad']."',
                        '".$data['nombres']."',
                        '".$data['apellidos']."',
                        '".$data['sexo']."',
                        '".$data['estado_ci']."',
                        '".$data['fecha_nac']."',
                        '".$data['correo']."',
                        '".$data['telefono']."',
                        '".$data['otrosDatos']."');";
  die($cadenaSql);
                     
  $respuesta = $this->ejecutarConsultaBdds($cadenaSql);

  return $dataCliente;
 ?>