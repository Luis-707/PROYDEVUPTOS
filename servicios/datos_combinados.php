<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaAdmin.php";
include_once "../clases/DesempenoExcepcional.php";

try {
    $idEvalAdmin = isset($_GET['id_eval_admin']) ? (int)$_GET['id_eval_admin'] : 0;

    if ($idEvalAdmin <= 0) {
        echo json_encode(['success' => false, 'message' => 'ID inválido']);
        exit;
    }

    // Datos combinados (admin + excepcional)
    $sql = ReportesPlanillaAdmin::sql_datos_combinados($idEvalAdmin);
    $res = $this->ejecutarConsultaBdds($sql);
    $info = $res[0][0] ?? $res[0] ?? [];

    if (empty($info)) {
        echo json_encode(['success' => false, 'message' => 'No se encontró información']);
        exit;
    }

    // Motivos del desempeño excepcional
    $motivos = [];
    if (!empty($info['id_desemp_excepcional'])) {
        $sqlMotivos = DesempenoExcepcional::sql_listar_motivos((int)$info['id_desemp_excepcional']);
        $resMotivos = $this->ejecutarConsultaBdds($sqlMotivos);
        $motivos = $resMotivos[0] ?? $resMotivos;
    }

    echo json_encode([
        'success' => true,
        'data'    => $info,
        'motivos' => $motivos
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;
?>
