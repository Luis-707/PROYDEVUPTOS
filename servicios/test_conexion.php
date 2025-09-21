<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

header('Content-Type: application/json; charset=utf-8');

try {
    // Consulta de prueba
    $sql = "SELECT * FROM asignados_supervisor LIMIT 5;";

    // Log para verificar el SQL
    error_log("DEBUG: Ejecutando prueba directa => " . $sql);

    // Ejecutar usando tu método habitual
    $resultado = $this->ejecutarConsultaBdds($sql);

    // Verificar el tipo de resultado
    if ($resultado === false) {
        error_log("ERROR: ejecutarConsultaBdds devolvió FALSE en la prueba directa");
        echo json_encode([
            'success' => false,
            'message' => 'La consulta de prueba falló en al menos una conexión'
        ]);
        exit;
    }

    if (!is_array($resultado)) {
        error_log("ERROR: ejecutarConsultaBdds devolvió tipo inesperado: " . gettype($resultado));
        echo json_encode([
            'success' => false,
            'message' => 'Tipo de dato inesperado en la respuesta'
        ]);
        exit;
    }

    // Si todo va bien, mostrar los datos
    echo json_encode([
        'success' => true,
        'message' => 'Consulta de prueba ejecutada correctamente',
        'data' => $resultado
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;