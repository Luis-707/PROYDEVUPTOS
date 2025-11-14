<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente
    $sql = $planilla->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);

    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación previa para este evaluado'
        ]);
        exit;
    }

    $idEvalAdmin = (int)$respuesta[0][0]['id_eval_admin'];

    // 2) Listar objetivos guardados
    $sqlObj = $planilla->sql_listar_objetivos_guardados($idEvalAdmin);
    $resObj = $this->ejecutarConsultaBdds($sqlObj);

    echo json_encode([
        'success' => true,
        'message' => '✅ Objetivos cargados',
        'data' => $resObj[0] ?? []
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;