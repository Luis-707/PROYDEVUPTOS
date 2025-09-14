<?php

include_once '../clases/JefeSuperior.php';

// Instanciar la clase con la conexión
$JefeSuperior = new JefeSuperior([],$this);

// Obtener las opciones HTML para el select
$respuesta = $JefeSuperior->listarJefes();

return $respuesta;
?>