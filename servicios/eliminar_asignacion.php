<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/Asignar_Supervisores.php";

try {
    $asignacion = new AsignarSupervisores($dataCliente['_post'], $this->conexion);

    $resultado = $asignacion->eliminarAsignacion();

    if ($resultado === true) {
        echo json_encode(['success' => true, 'message' => 'Asignaciones eliminadas con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => $resultado]);
    }
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
exit;