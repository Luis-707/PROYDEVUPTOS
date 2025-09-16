<?php

include_once "../clases/CargosSupervisor.php";

// Instanciar la clase con la conexión
$Supervisor = new CargosSupervisor([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluadores
$respuesta = $Supervisor->listarSupervisores();

return $respuesta;

?>