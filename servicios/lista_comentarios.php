<?php

//ini_set('display_errors', '1');

include_once '../clases/ListaComentarios.php';

// Instanciar la clase con la conexión
$Lista = new ListaComentarios($this);

// Obtener los evaluados administrativos
$respuesta = $Lista->listarEvaluadosComentarios();

return $respuesta;
?>