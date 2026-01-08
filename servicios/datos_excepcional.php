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

    // 1) Buscar si existe planilla excepcional
    $sqlExiste = DesempenoExcepcional::sql_existe_excepcional($idEvalAdmin);
    $resExiste = $this->ejecutarConsultaBdds($sqlExiste);
    $rowExiste = $resExiste[0][0] ?? $resExiste[0] ?? [];

    if (empty($rowExiste['id_desemp_excepcional'])) {
        echo json_encode(['success' => false, 'message' => 'No existe planilla excepcional para esta evaluación']);
        exit;
    }

    $idDesempExcepcional = (int)$rowExiste['id_desemp_excepcional'];

    // 2) Obtener datos generales de la planilla excepcional
    $sqlDatos = DesempenoExcepcional::sql_datos_excepcional($idDesempExcepcional);
    $resDatos = $this->ejecutarConsultaBdds($sqlDatos);
    $infoExcep = $resDatos[0][0] ?? $resDatos[0] ?? [];

    // 3) Obtener motivos e indicadores asociados
    $sqlMotivos = DesempenoExcepcional::sql_listar_motivos($idDesempExcepcional);
    $resMotivos = $this->ejecutarConsultaBdds($sqlMotivos);
    $motivos = $resMotivos[0] ?? $resMotivos;

    // 🔹 Normalizar: siempre devolver array
    if (!is_array($motivos)) {
        $motivos = [$motivos];
    }

    echo json_encode([
        'success' => true,
        'data' => $infoExcep,
        'motivos' => $motivos
    ]);

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
exit;
?>