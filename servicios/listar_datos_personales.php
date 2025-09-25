<?php

//ini_set('display_errors', '1');

include_once '../clases/PlanillaAdministrativos.php';

// Instanciar la clase con la conexión
$PlanillaAdmin = new PlanillaAdministrativos($this);

// Obtener los evaluados administrativos
$respuesta = $PlanillaAdmin->listarRelaciones();

return $respuesta;
?>