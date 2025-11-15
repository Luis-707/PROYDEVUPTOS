<?php
ini_set('display_errors', '1');

include_once "../clases/EvaluacionAdministrativos.php";

// Asumiendo que $this tiene el método ejecutarConsultaBdds para la conexión a BD
$evalAdmin = new EvaluacionesAdministrativos([], $this);

$respuesta = $evalAdmin->listarEvalAdministrativos();

return $respuesta;
