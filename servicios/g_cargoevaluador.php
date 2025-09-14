<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

include_once "../clases/CargosEvaluador.php";

// Supongo que $dataCliente['_post'] es un array con las claves 'clave' y 'cedula_usuario'
// Ejemplo: $dataCliente['_post'] = ['clave' => 'valor', 'cedula_usuario' => 'valor'];

$cargo = new CargosEvaluador($dataCliente['_post'], $this->conexion);

// Generar la consulta para buscar por cedula_usuario
$sql = $cargo->sql_buscar_evaluadores();

// Ejecutar consulta para buscar usuario existente
$respuesta = $this->ejecutarConsultaBdds($sql);

if (count($respuesta) == 0) {
    // No existe cargo, insertar nuevo
    $sql = $cargo->sql_guardar_cargo_evaluador();
    $respuesta = $this->ejecutarConsultaBdds($sql);
} else {
    // Usuario ya existe
    $respuesta = $dataCliente['_post']['id_evaluador'].' ya existe';
}

return $respuesta;
?>