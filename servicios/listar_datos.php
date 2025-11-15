<?php

include_once "../clases/EvaluacionAdministrativos.php";

// Crear instancia de la clase UsuariosSistema, pasando un array vacío y la conexión ($this)
$Usuarios = new EvaluacionesAdministrativos([], $this);

// Obtener el resultado de la consulta para listar usuarios con sus roles
$respuesta = $Usuarios->listarDatos();

return $respuesta;

?>