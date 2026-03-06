<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');

session_start();
include_once "../clases/EvaluacionesObreros.php";

try {

    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    if (!$idUsuarioSesion) {
        echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
        exit;
    }

    // 2) Detectar si los datos vienen como JSON o Form-Data
    $dataCliente = [];
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $dataCliente = json_decode($raw, true) ?? [];
    } else {
        $dataCliente = $_POST;
    }

    //$dataCliente = $_POST;
    $dataCliente['evaluador_id'] = $idUsuarioSesion;

    $eval = new EvaluacionesObreros($dataCliente, $this->conexion);

$check = $this->ejecutarConsultaBdds($eval->sql_existe_duplicado_periodo_obrero());

if (!empty($check) && !empty($check[0][0]['id_eval_obreros'])) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Ya existe una evaluación para este evaluado en este período'
    ]);
    exit;
}

    // Insertar
    $resp = $this->ejecutarConsultaBdds($eval->sql_guardar_eval_obreros());
    $registro = $resp[0][0] ?? null;

    if ($registro && !empty($registro['id_eval_obreros'])) {
        echo json_encode([
            'success' => true,
            'message' => 'Evaluación creada con éxito',
            'id_eval_obreros' => (int)$registro['id_eval_obreros']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear la evaluación']);
    }

} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;

