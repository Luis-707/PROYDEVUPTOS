<?php

error_reporting(E_ALL);
ini_set('display_errors', '0'); 
ini_set('display_startup_errors', '0');

header('Content-Type: application/json; charset=utf-8');

session_start();
include_once "../clases/EvaluacionAdministrativos.php";

try {
    // 1) Validar sesión
    $idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
    if (!$idUsuarioSesion) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Usuario no autenticado'
        ]);
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

    // 3) Inyectar el id_usuario del evaluador desde la sesión
    $dataCliente['evaluador_id'] = $idUsuarioSesion;

    // 4) Instanciar la clase
    $evaluacion = new EvaluacionesAdministrativos($dataCliente, $this->conexion);

  $sqlCheck = $evaluacion->sql_existe_duplicado_periodo();
$respCheck = $this->ejecutarConsultaBdds($sqlCheck);

if (!empty($respCheck) && !empty($respCheck[0][0]['id_eval_admin'])) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Ya existe una evaluación para este evaluado en este período'
    ]);
    exit;
}

    // 6) Ejecutar el INSERT
    $sqlInsert = $evaluacion->sql_guardar_eval_administrativos();
    $respInsert = $this->ejecutarConsultaBdds($sqlInsert);

    $registro = $respInsert[0][0] ?? null;

    if ($registro && !empty($registro['id_eval_admin'])) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Evaluación creada con éxito',
            'id_eval_admin' => (int)$registro['id_eval_admin']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ No se pudo crear la evaluación'
        ]);
    }

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error en el servidor: ' . $e->getMessage()
    ]);
}
exit;