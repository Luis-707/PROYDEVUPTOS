<?php

//ini_set('display_errors', '1');

include_once '../clases/EvalAdministrativos.php';

// Instanciar la clase con la conexión
$EvalAdmin = new EvalAdministrativos($this);

// Obtener los evaluados administrativos
$respuesta = $EvalAdmin->listarEvaluados();

return $respuesta;
?>
