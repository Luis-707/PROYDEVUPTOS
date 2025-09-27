<?php

include_once "../clases/Competencia.php";

// Instanciar la clase con la conexión
$competencia = new Competencia([], $this);

// Obtener las opciones HTML (o los datos) desde listar_competencias
$respuesta = $competencia->listar_competencias();

return $respuesta;

?>