<?php
error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

include_once "../clases/EvaluacionAdministrativos.php";

try {
    // 1) Detectar si los datos vienen como JSON o como Form-Data
    $dataCliente = [];
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $dataCliente = json_decode($raw, true) ?? [];
    } else {
        $dataCliente = $_POST;
    }

    // Normalizar: asegurar que id_evaluado esté presente
    $idEvaluado = $dataCliente['id_evaluado'] ?? null;
    if (!$idEvaluado) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falta id_evaluado en la petición'
        ]);
        exit;
    }

    // 2) Instanciar la clase con los datos recibidos
    $evaluacion = new EvaluacionesAdministrativos($dataCliente, $this->conexion);

    // 3) Buscar si ya existe un registro para ese evaluado
    $sqlBuscar = $evaluacion->sql_buscarPorEvaluado();
    $respBuscar = $this->ejecutarConsultaBdds($sqlBuscar);

    // Aplanar respuesta
    $registro = $respBuscar[0][0] ?? null;

    if (!$registro || empty($registro['id_evaluado'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ No existe evaluación registrada para este evaluado'
        ]);
        exit;
    }

    $evaluacion->setIdEvalAdmin((int)$registro['id_eval_admin']);
    $evaluacion->setIdEvaluado((int)$registro['id_evaluado']);

    // 4) Si existe evaluación, actualizar periodo
    if ($evaluacion->getIdEvalAdmin() > 0) {
        $sqlUpdate = $evaluacion->sql_actualizar_periodo();
        $respUpdate = $this->ejecutarConsultaBdds($sqlUpdate);

        $registroUpdate = $respUpdate[0][0] ?? null;

        if ($registroUpdate && !empty($registroUpdate['id_eval_admin'])) {
            echo json_encode([
                'success' => true,
                'message' => '✅ Periodo actualizado con éxito',
                'id_eval_admin' => (int)$registroUpdate['id_eval_admin']
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => '⚠️ No se pudo actualizar el periodo'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'ℹ️ Este evaluado aún no tiene evaluación registrada'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;