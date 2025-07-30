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

//$data = $dataCliente['_post'];
//die(print_r($data));

/*if(opc==1){
$sql = sprintf("SELECT * FROM usuario WHERE usuario ILIKE '%s' OR correo_usu ILIKE '%s' OR nombres_usu ILIKE '%s'OR apellidos_usu ILIKE '%s';",
                '%'.$data['buscar'].'%','%'.$data['buscar'].'%','%'.$data['buscar'].'%','%'.$data['buscar'].'%');
 //die($sql);               
}else{*/

  include_once "../clases/Usuario.php";

  $dataCliente['_post']['login'] = $dataCliente['_post']['otros_datos'];
  

  $usuario = new Usuario($dataCliente['_post']['login'] );

    $sql=$usuario->sql_buscar();

  $respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
  return 0;
else
   return 1;

 ?>