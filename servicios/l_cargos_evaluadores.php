<?php

include_once "../clases/CargosEvaluador.php";

// Instanciar la clase con la conexión
$cargoEvaluador = new CargosEvaluador([], $this);

// Obtener las opciones HTML (o los datos) desde listarCargosEvaluadores
$respuesta = $cargoEvaluador->listarCargosEvaluadores();

return $respuesta;

?>