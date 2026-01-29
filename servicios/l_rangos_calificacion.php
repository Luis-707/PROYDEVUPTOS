<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/RangosCalificacion.php";

try {
    $sql = RangosCalificacion::sql_listar();
    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp) || empty($resp[0])) {
        echo json_encode(['success' => false, 'message' => 'No hay rangos registrados']);
        exit;
    }

    echo json_encode(['success' => true, 'data' => $resp[0]]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
}
exit;

