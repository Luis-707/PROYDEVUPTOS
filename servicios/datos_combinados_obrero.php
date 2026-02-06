<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaObreros.php";

try {
    $idEvalObrero = isset($_GET['id_eval_obreros']) ? (int)$_GET['id_eval_obreros'] : 0;

    if ($idEvalObrero <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        exit;
    }

    $reporte = new ReportesPlanillaObreros($this);

    // Datos generales
    $sql = ReportesPlanillaObreros::sql_datos_evaluacion($idEvalObrero);
    $res = $reporte->ejecutarConsulta($sql);
    $info = $res[0][0] ?? $res[0] ?? [];

    if (empty($info)) {
        echo json_encode(['success' => false, 'message' => 'No se encontró información para la evaluación']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $info
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}

exit;