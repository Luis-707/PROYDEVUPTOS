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

    // Detectar JSON o POST
    $dataCliente = [];
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $dataCliente = json_decode($raw, true) ?? [];
    } else {
        $dataCliente = $_POST;
    }

    // Inyectar evaluador
    $dataCliente['evaluador_id'] = $idUsuarioSesion;

    // Crear objeto SIN calcular aún tiempo_puesto
    $eval = new EvaluacionesObreros($dataCliente, $this->conexion);

    // ============================================================
    // 1. OBTENER FECHA DE INGRESO DESDE LA CLASE
    // ============================================================
    $sqlFecha = $eval->sql_fecha_ingreso_evaluado();
    $fechaRow = $this->ejecutarConsultaBdds($sqlFecha);
    $fechaIngreso = $fechaRow[0][0]['fecha_ingreso'] ?? null;

    // ============================================================
    // 2. CALCULAR TIEMPO EN EL PUESTO
    // ============================================================
    $tiempoPuesto = 0;

    if ($fechaIngreso) {
        $f1 = new DateTime($fechaIngreso);
        $f2 = new DateTime();
        $diff = $f1->diff($f2);
        $tiempoPuesto = $diff->y;
    }

    // Inyectar en el array original
    $dataCliente['tiempo_puesto'] = $tiempoPuesto;

    // Recrear objeto con tiempo_puesto incluido
    $eval = new EvaluacionesObreros($dataCliente, $this->conexion);

    // ============================================================
    // 3. VALIDAR DUPLICADOS
    // ============================================================
    $check = $this->ejecutarConsultaBdds($eval->sql_existe_duplicado_periodo_obrero());

    if (!empty($check) && !empty($check[0][0]['id_eval_obreros'])) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Ya existe una evaluación para este evaluado en este período'
        ]);
        exit;
    }

    // ============================================================
    // 4. GUARDAR
    // ============================================================
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