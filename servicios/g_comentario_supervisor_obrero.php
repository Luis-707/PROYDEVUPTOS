<?php
session_start();
include_once "../clases/Planilla_comentarios_obreros.php";

header('Content-Type: application/json; charset=utf-8');

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

if (!in_array("supervisor del evaluador", $rolesSesion)) {
    echo json_encode(["success" => false, "message" => "No tiene permiso para comentar como supervisor"]);
    exit;
}

$data = $_POST ?? [];
$planilla = new Planilla_comentarios_obreros($data, $this->conexion);

// ============================================================
// 1. Validar permiso real
// ============================================================
$sqlPermiso = $planilla->sql_buscar_por_id_y_supervisor($_SESSION['usuario']['cedula']);
$permiso = $this->ejecutarConsultaBdds($sqlPermiso);

if (empty($permiso) || empty($permiso[0])) {
    echo json_encode([
        "success" => false,
        "message" => "No está autorizado para comentar esta evaluación"
    ]);
    exit;
}

$comentario = trim($data['comentario_supervisor'] ?? '');
$idEval = $data['id_eval_obreros'] ?? '';

if ($comentario === '' || strlen($comentario) < 10) {
    echo json_encode([
        'success' => false,
        'message' => 'El comentario es demasiado corto o está vacío.'
    ]);
    exit;
}

$triviales = ['ok','bien','si','no','.','na','n/a'];
if (in_array(strtolower($comentario), $triviales)) {
    echo json_encode([
        'success' => false,
        'message' => 'El comentario no es válido.'
    ]);
    exit;
}

if (!$idEval) {
    echo json_encode([
        'success' => false,
        'message' => 'ID de evaluación no recibido.'
    ]);
    exit;
}

// ============================================================
// 2. Ejecutar UPDATE usando el método de la clase
// ============================================================
$sqlUpdate = $planilla->sql_update_comentario_supervisor();
$update = $this->ejecutarConsultaBdds($sqlUpdate);

if (empty($update) || empty($update[0])) {
    echo json_encode([
        "success" => false,
        "message" => "No se pudo guardar el comentario"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Comentario del supervisor guardado correctamente"
]);
exit;