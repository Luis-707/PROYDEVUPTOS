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



 $cadenaSql = "SELECT * FROM persona;";
                     
  $respuesta = $this->ejecutarConsultaBdds($cadenaSql);
//print_r($respuesta);
  $html = "<table>";
  for ($i=0; $i < count($respuesta[0]) ; $i++) { 
    $html =  $html . "<tr><td>".$respuesta[0][$i]['nombre']."<td></tr>";
  }
  $html =  $html ."</table>";

  return $html;
 ?>