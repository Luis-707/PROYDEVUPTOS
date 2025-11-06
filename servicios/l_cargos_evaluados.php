<?php

include_once "../clases/DatosEvaluados.php";

// Instanciar la clase con la conexión
$GEvaluados = new DatosEvaluados([], $this);

// Obtener las opciones HTML (o los datos) desde listarEvaluados
$respuesta = $GEvaluados->listarCargosEvaluados();

return $respuesta;
