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

    // 2) Obtener periodo y fechas
    $sqlPeriodo = $planilla->sql_obtener_periodo();
    $resPeriodo = $this->ejecutarConsultaBdds($sqlPeriodo);
    $periodoData = !empty($resPeriodo[0][0]) ? $resPeriodo[0][0] : [];

    // 3) Obtener objetivos guardados
    $sqlObj = $planilla->sql_listar_objetivos_guardados($idEvalAdmin);
    $resObj = $this->ejecutarConsultaBdds($sqlObj);

    // 4) Obtener competencias guardadas
    $sqlComp = $planilla->sql_listar_competencias_guardadas($idEvalAdmin);
    $resComp = $this->ejecutarConsultaBdds($sqlComp);

    // 5) Respuesta final
    echo json_encode([
        'success' => true,
        'message' => '✅ Datos de evaluación cargados',
        'data' => [
            'periodo' => $periodoData,
            'objetivos' => $resObj[0] ?? [],
            'competencias' => $resComp[0] ?? []
        ]
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;