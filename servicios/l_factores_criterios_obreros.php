<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/Factores.php";
include_once "../clases/Criterios.php";

try {
    $sqlFactores = Factores::sql_listar();
    $respFactores = $this->ejecutarConsultaBdds($sqlFactores);

    if (empty($respFactores) || empty($respFactores[0])) {
        echo json_encode(['success' => false, 'message' => 'No hay factores registrados']);
        exit;
    }

    $factores = $respFactores[0];
    foreach ($factores as &$f) {
        $sqlCriterios = Criterios::sql_listar_por_factor($f['factor_id']);
        $respCriterios = $this->ejecutarConsultaBdds($sqlCriterios);
        $f['criterios'] = $respCriterios[0] ?? [];
    }

    echo json_encode(['success' => true, 'data' => $factores]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
}
exit;