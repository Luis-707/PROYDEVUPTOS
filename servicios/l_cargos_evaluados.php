<?php

include_once "../clases/Evaluados.php";

// Instanciar la clase con la conexión
$GEvaluados = new Evaluado([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluados
$respuesta = $GEvaluados->listarCargosEvaluados();

return $respuesta;
