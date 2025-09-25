<?php

include_once "../clases/Supervisor.php";

// Instanciar la clase con la conexión
$cargoSupervisor = new Supervisor([], $this);

// Obtener las opciones HTML (o los datos) desde listarCargosSupervisores
$respuesta = $cargoSupervisor->listarCargosSupervisores();

return $respuesta;

?>