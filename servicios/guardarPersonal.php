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

  //print_r($dataCliente);

  //echo $dataCliente['_post']['nombre'];


/*
            [cedula] => cedula
            [nombre] => nombre
            [apellido] => apellido
            [email] => correo
            [telefono] => teelfono
            [cargo] => cargo
            [fechaNac] => 2025-01-15
            [unidad] => 
            [sexo] => M
            [otrosDatos] => */



 $cadenaSql = "INSERT INTO persona (nombre,apellido,correo,telefono,cargo,fecha_nac,sexo)
                 VALUES('".$dataCliente['_post']['nombre']."',
                        '".$dataCliente['_post']['apellido']."',
                        '".$dataCliente['_post']['email']."',
                        '".$dataCliente['_post']['telefono']."',
                        '".$dataCliente['_post']['cargo']."',
                        '".$dataCliente['_post']['fechaNac']."',
                        '".$dataCliente['_post']['sexo']."');";
                     
  $respuesta = $this->ejecutarConsultaBdds($cadenaSql);

  return $dataCliente;
 ?>