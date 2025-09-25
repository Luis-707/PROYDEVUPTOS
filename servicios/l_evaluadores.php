<?php

include_once "../clases/Evaluador.php";

// Instanciar la clase con la conexión
$Evaluador = new Evaluador([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluadores
$respuesta = $Evaluador->listarEvaluadores();

return $respuesta;

?>