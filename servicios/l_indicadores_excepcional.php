<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/DesempenoExcepcional.php";

try {
    $data = $_GET;
    if (empty($data)) {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true) ?? [];
    }

    if (empty($data['id_eval_admin'])) {
        echo json_encode(['success' => false, 'message' => 'Falta el id_eval_admin']);
        exit;
    }

    $idEvalAdmin = (int)$data['id_eval_admin'];

    // Consulta de indicadores excepcionales
    $sql = DesempenoExcepcional::sql_listar_indicadores($idEvalAdmin);
    $res = $this->ejecutarConsultaBdds($sql);
    $indicadores = $res[0] ?? $res;

    echo json_encode([
        'success' => true,
        'data' => $indicadores
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
exit;
?>

