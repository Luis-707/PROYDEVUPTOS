<?php

include_once "../clases/Objetivo.php";

// Instanciar la clase con la conexión
$objetivo = new Objetivo([], $this);

// Obtener las opciones HTML (o los datos) desde listar_objetivos
$respuesta = $objetivo->listar_objetivos();

return $respuesta;

?>