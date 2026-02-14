<?php
session_start();
include_once "../clases/PlanillaAdministrativos.php";

header('Content-Type: application/json; charset=utf-8');

try {

    $id_eval_admin = isset($_POST['id_eval_admin']) ? (int)$_POST['id_eval_admin'] : 0;

    if ($id_eval_admin <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Falta id_eval_admin para cargar el período'
        ]);
        exit;
    }

    $sql = PlanillaAdministrativos::sql_periodo($id_eval_admin);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp) || empty($resp[0])) {
        echo json_encode([
            'success' => false,
            'message' => 'No se encontró período para esta evaluación'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'data' => $resp[0][0],
        'id_eval_admin' => $id_eval_admin
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
