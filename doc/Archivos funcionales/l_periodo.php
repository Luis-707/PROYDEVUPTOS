<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente y obtener id_eval_admin
    $sql = $planilla->sql_buscar();
    $respuesta = $this->ejecutarConsultaBdds($sql);
    
    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación previa para este evaluado en el periodo indicado'
        ]);
        exit;
    }
    
    $idEvalAdmin = (int)$respuesta[0][0]['id_eval_admin'];
    $periodoEvaluado = $respuesta[0][0]['periodo_evaluado'] ?? '';
    
    $sqlPeriodo = $planilla->sql_listar_periodo_por_id($idEvalAdmin);
    $resPeriodo = $this->ejecutarConsultaBdds($sqlPeriodo);
    
    if (empty($resPeriodo) || empty($resPeriodo[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se encontró periodo de evaluación'
        ]);
        exit;
    }
    
    $periodo = $resPeriodo[0][0];
    
    echo json_encode([
        'success' => true,
        'message' => '✅ Periodo cargado',
        'data' => [
            'fecha_inicio'     => $periodo['fecha_inicio'] ?? '',
            'fecha_cierre'     => $periodo['fecha_cierre'] ?? '',
            'periodo_evaluado' => $periodoEvaluado
        ],
        'id_eval_admin' => $idEvalAdmin
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;