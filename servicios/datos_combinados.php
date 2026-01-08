<?php
header('Content-Type: application/json; charset=utf-8');
include_once "../clases/ReportesPlanillaAdmin.php";
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

    // Consulta combinada
    $sql = ReportesPlanillaAdmin::sql_datos_combinados($idEvalAdmin);
    $res = $this->ejecutarConsultaBdds($sql);
    $info = $res[0][0] ?? $res[0] ?? [];

    if (empty($info)) {
        echo json_encode(['success' => false, 'message' => 'No se encontró información para la evaluación']);
        exit;
    }

    // Si existe desempeño excepcional, obtener motivos
    $motivos = [];
    if (!empty($info['id_desemp_excepcional'])) {
        $sqlMotivos = DesempenoExcepcional::sql_listar_motivos((int)$info['id_desemp_excepcional']);
        $resMotivos = $this->ejecutarConsultaBdds($sqlMotivos);
        $motivos = $resMotivos[0] ?? $resMotivos;
    }

    echo json_encode([
        'success' => true,
        'data' => $info,
        'motivos' => $motivos
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
exit;
?>