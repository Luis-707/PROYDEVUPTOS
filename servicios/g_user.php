<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Evita que los errores se impriman en la salida
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Usuario3.php";

try {
    $cargo = new Usuario($dataCliente['_post'], $this->conexion);

    // Buscar si ya existe
    $sql = $cargo->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
        // No existe, insertar nuevo
        $sql = $cargo->sql_guardar();
        $this->ejecutarConsultaBdds($sql);

        echo json_encode([
            'success' => true,
            'message' => 'Usuario creado con éxito'
        ]);
    } else {
        // Ya existe
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe un usuario para este empleado'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;