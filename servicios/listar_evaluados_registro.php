<?php

include_once "../clases/Registro_evaluados.php";

// Crear instancia de la clase Evaluado, pasando un array vacío y la conexión ($this)
$Evaluado = new RegistroEvaluados([], $this);

// Obtener el resultado de la consulta para listar evaluados con sus cargos
$respuesta = $Evaluado->listarEvaluados();

return $respuesta;

?>