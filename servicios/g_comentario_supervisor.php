<?php
session_start();
include_once "../clases/Planilla_comentarios.php";

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion  = $_SESSION['usuario']['roles'] ?? [];   // AHORA ES UN ARRAY

if (!$cedulaSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$data = $dataCliente['_post'] ?? $_POST ?? [];
$planilla = new Planilla_comentarios($data);

// ======================================================
// VALIDACIÓN DE PERMISOS (MISMA LÓGICA DEL ORIGINAL)
// Solo el SUPERVISOR DEL EVALUADOR puede actualizar
// ======================================================

if (!in_array("supervisor del evaluador", $rolesSesion)) {
    echo json_encode([
        'success' => false,
        'message' => 'Solo el supervisor puede actualizar este comentario'
    ]);
    exit;
}

// Validar que la evaluación pertenece a un evaluado bajo este supervisor
$sql = $planilla->sql_buscar_por_id_y_supervisor($cedulaSesion);
$respuesta = $this->ejecutarConsultaBdds($sql);

if (empty($respuesta) || empty($respuesta[0])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autorizado para actualizar este comentario'
    ]);
    exit;
}

$comentario = trim($data['comentario_supervisor'] ?? '');
$idEval = $data['id_eval_admin'] ?? '';

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

// ======================================================
// EJECUTAR UPDATE
// ======================================================
$sql = $planilla->sql_update_comentario_supervisor();
$resUpdate = $this->ejecutarConsultaBdds($sql);

if (empty($resUpdate) || empty($resUpdate[0])) {
    echo json_encode([
        'success' => false,
        'message' => 'No se actualizó ningún registro'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Comentario del supervisor actualizado correctamente'
]);
exit;