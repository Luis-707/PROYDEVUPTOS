<?php

include_once "../clases/Evaluador.php";

// Instanciar la clase con la conexión
$cargoEvaluador = new Evaluador([], $this);

// Obtener las opciones HTML (o los datos) desde listarCargosEvaluadores
$respuesta = $cargoEvaluador->listarCargosEvaluadores();

return $respuesta;

?>