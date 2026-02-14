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
// Solo el EVALUADO puede actualizar su comentario
// ======================================================

if (!in_array("evaluado", $rolesSesion)) {
    echo json_encode([
        'success' => false,
        'message' => 'Solo el evaluado puede actualizar este comentario'
    ]);
    exit;
}

// Validar que la evaluación pertenece al evaluado
$sql = $planilla->sql_buscar_por_id_y_evaluado($cedulaSesion);
$respuesta = $this->ejecutarConsultaBdds($sql);

if (empty($respuesta) || empty($respuesta[0])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autorizado para actualizar este comentario'
    ]);
    exit;
}

// ======================================================
// EJECUTAR UPDATE
// ======================================================
$sql = $planilla->sql_update_comentario_evaluado();
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
    'message' => 'Comentario del evaluado actualizado correctamente'
]);
exit;
