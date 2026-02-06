<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaObreros.php";

try {
    $reporte = new ReportesPlanillaObreros($this);

    $res = $reporte->listarReportesObreros();
    $data = $res[0] ?? $res;

    echo json_encode([
        "success" => true,
        "data" => $data
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}

exit;