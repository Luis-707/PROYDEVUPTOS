<?php

include_once "../clases/CargosSupervisor.php";

// Instanciar la clase con la conexión
$cargoSupervisor = new CargosSupervisor([], $this);

// Obtener las opciones HTML (o los datos) desde listarCargosSupervisores
$respuesta = $cargoSupervisor->listarCargosSupervisores();

return $respuesta;

?>