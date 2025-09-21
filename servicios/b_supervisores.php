<?php
include_once "../clases/CargosSupervisor.php";

$dataCliente['_post']['id_supervisor'] = $dataCliente['_post']['otros_datos'];


$supervisor = new CargosSupervisor($dataCliente['_post']['id_supervisor'] );

  $sql=$supervisor->sql_buscar_supervisores();

$respuesta = $this->ejecutarConsultaBdds($sql);
if(empty($respuesta))
return 0;
else
 return 1;