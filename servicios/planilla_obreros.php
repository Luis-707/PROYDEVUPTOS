<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/EvaluacionObreros.php";

try {
    if (empty($_POST['cedula_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'No se recibió la cédula']);
        exit;
    }

    $cedula = $_POST['cedula_usuario'];

    $sql = EvaluacionObreros::sql_datos_planilla($cedula);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp) || empty($resp[0])) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $resp[0][0]]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
}
exit;
