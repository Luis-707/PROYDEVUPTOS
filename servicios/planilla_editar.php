<?php
session_start();
include_once "../clases/PlanillaAdministrativos.php";

header('Content-Type: application/json; charset=utf-8');

try {

    $evaluado_id = (int)($_POST['evaluado_id'] ?? 0);
    $id_eval_admin = (int)($_POST['id_eval_admin'] ?? 0);

    if ($evaluado_id <= 0 || $id_eval_admin <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Datos incompletos para cargar la planilla"
        ]);
        exit;
    }

    $sql = PlanillaAdministrativos::sql_cargar_planilla_editar($evaluado_id, $id_eval_admin);
    $resp = $this->ejecutarConsultaBdds($sql);

    if (empty($resp[0])) {
        echo json_encode([
            "success" => false,
            "message" => "No se encontraron datos para esta evaluación"
        ]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "data" => $resp[0][0]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor: " . $e->getMessage()
    ]);
}
exit;