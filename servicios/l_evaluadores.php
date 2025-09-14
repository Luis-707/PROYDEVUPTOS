<?php

include_once "../clases/CargosEvaluador.php";

// Instanciar la clase con la conexión
$Evaluador = new CargosEvaluador([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluadores
$respuesta = $Evaluador->listarEvaluadores();

return $respuesta;

?>