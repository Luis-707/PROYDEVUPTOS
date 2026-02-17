<?php
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/EvaluacionObreros.php";

session_start();

try {

    $rolesSesion  = $_SESSION['usuario']['roles'] ?? [];

    if (!in_array("evaluador", $rolesSesion)) {
        echo json_encode(['success' => false, 'message' => 'Rol no autorizado']);
        exit;
    }

    if (empty($_POST['cedula_usuario']) || empty($_POST['id_eval_obreros'])) {
        echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
        exit;
    }

    $cedula = $_POST['cedula_usuario'];
    $idEval = intval($_POST['id_eval_obreros']);

    $sql = EvaluacionObreros::sql_datos_planilla($cedula, $idEval);

    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp) || empty($resp[0])) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $resp[0][0]
    ]);

} catch (Throwable $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

exit;
