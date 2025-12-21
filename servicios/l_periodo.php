<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/PlanillaAdministrativos.php";

try {
    // Instanciar la clase con los datos recibidos
    $planilla = new PlanillaAdministrativos($dataCliente['_post'], $this->conexion);

    // 1) Buscar evaluación existente con sql_buscar
    // 👇 aquí debes pasar el id_eval_admin que venga del cliente
    $idEvalAdmin = isset($dataCliente['_post']['id_eval_admin']) 
        ? (int)$dataCliente['_post']['id_eval_admin'] 
        : 0;

    $sqlEval = $planilla->sql_buscar($idEvalAdmin);
    $respuesta = $this->ejecutarConsultaBdds($sqlEval);

    if (empty($respuesta) || empty($respuesta[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación previa para este evaluado'
        ]);
        exit;
    }

    // Normalizar resultado
    $evalData = isset($respuesta[0][0]) ? $respuesta[0][0] : $respuesta[0];

    // 2) Obtener periodo y fechas con ese id_eval_admin
    $sqlPeriodo = $planilla->sql_listar_periodo_por_id((int)$evalData['id_eval_admin']);
    $resPeriodo = $this->ejecutarConsultaBdds($sqlPeriodo);

    if (empty($resPeriodo) || empty($resPeriodo[0])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se encontró periodo de evaluación'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => '✅ Periodo cargado',
        'data' => $resPeriodo[0][0],
        'id_eval_admin' => (int)$evalData['id_eval_admin']
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;