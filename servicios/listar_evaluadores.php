<?php

include_once "../clases/UsuariosSistema.php";

// Crear instancia de la clase UsuariosSistema, pasando un array vacío y la conexión ($this)
$Usuarios = new UsuariosSistema([], $this);

// Obtener el resultado de la consulta para listar usuarios con sus roles
$respuesta = $Usuarios->listarUsuarios();

return $respuesta;

?>