<?php

include_once "../clases/Asignar_Supervisores.php";

// Crear instancia de la clase Evaluadores, pasando un array vacío y la conexión ($this)
$supervisores = new AsignarSupervisores([], $this);

// Obtener el resultado de la consulta para listar evaluadores con cédula de usuario
$respuesta = $supervisores->listarEvaluadores();

return $respuesta;




?>