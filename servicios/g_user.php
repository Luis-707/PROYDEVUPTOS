<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once "../clases/Usuario3.php";

// Supongo que $dataCliente['_post'] es un array con las claves 'clave' y 'cedula_usuario'
// Ejemplo: $dataCliente['_post'] = ['clave' => 'valor', 'cedula_usuario' => 'valor'];

$usuarioP = new Usuario($dataCliente['_post'], $this->conexion);

// Generar la consulta para buscar por cedula_usuario
$sql = $usuarioP->sql_buscar();

// Ejecutar consulta para buscar usuario existente
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    // No existe usuario, insertar nuevo
    $sql = $usuarioP->sql_guardar();
    $respuesta = $this->ejecutarConsultaBdds($sql);
} else {
    // Usuario ya existe
    $respuesta = $dataCliente['_post']['cedula_usuario'].' ya existe';
}

return $respuesta;
?>
