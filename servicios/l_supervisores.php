<?php

include_once "../clases/Supervisor.php";

// Instanciar la clase con la conexión
$Supervisor = new Supervisor([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluadores
$respuesta = $Supervisor->listarSupervisores();

return $respuesta;

?>