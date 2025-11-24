<?php

include_once "../clases/DatosEvaluados.php";

// Crear instancia de la clase UsuariosSistema, pasando un array vacío y la conexión ($this)
$evaluado = new DatosEvaluados([], $this);

// Obtener el resultado de la consulta para listar usuarios con sus roles
$respuesta = $evaluado->listarEvaluados();

return $respuesta;

?>