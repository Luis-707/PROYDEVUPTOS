<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); // Evita que los errores se impriman en la salida
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Supervisor.php";

try {
    $cargo = new Supervisor($dataCliente['_post'], $this->conexion);

    // Buscar si ya existe
    $sql = $cargo->sql_buscar_supervisores();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (count($respuesta) == 0) {
        // No existe, insertar nuevo
        $sql = $cargo->sql_guardar_cargo_supervisor();
        $this->ejecutarConsultaBdds($sql);

        echo json_encode([
            'success' => true,
            'message' => 'Cargo asignado con éxito'
        ]);
    } else {
        // Ya existe
        echo json_encode([
            'success' => false,
            'message' => 'Ya existe un cargo para este supervisor'
        ]);
    }
} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;