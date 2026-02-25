<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/EvaluacionObreros.php";

try {

    if (empty($_POST['id_eval_obreros'])) {
        echo json_encode(['success' => false, 'message' => 'Falta id_eval_obreros']);
        exit;
    }

    $idEval = intval($_POST['id_eval_obreros']);

    $sql = EvaluacionObreros::sql_seleccionados($idEval);
    $resp = $this->ejecutarConsultaBdds($sql);

    echo json_encode([
        'success' => true,
        'data' => $resp[0] ?? []
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error: '.$e->getMessage()
    ]);
}

exit;