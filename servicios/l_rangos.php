<?php

include_once "../clases/RangoActuacion.php";

// Instanciar la clase con la conexión
$rango = new RangoActuacion([], $this);

// Obtener las opciones HTML (o los datos) desde listar_objetivos
$respuesta = $rango->listar_rango_actuacion();

return $respuesta;

?>