<?php

include_once '../clases/Evaluador.php'; // Cambiar importación a Supervisores.php

// Instanciar la clase Supervisores con la conexión actual ($this)
$supervisores = new Evaluador([], $this);

// Obtener las opciones o datos de supervisores con cargos
$respuesta = $supervisores->listarSupervisores();

return $respuesta;

?>
