<?php
session_start();
include_once "../clases/Planilla_comentarios.php";

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
if (!$cedulaSesion) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$data = $dataCliente['_post'] ?? $_POST ?? [];
$planilla = new Planilla_comentarios($data);

// 1. Validar que la evaluación corresponda al evaluado en sesión
$sql = $planilla->sql_buscar_por_id_y_supervisor($cedulaSesion);
$respuesta = $this->ejecutarConsultaBdds($sql);

if (empty($respuesta) || empty($respuesta[0])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuario no autorizado para actualizar este comentario'
    ]);
    exit;
}

// 2. Ejecutar update
$sql = $planilla->sql_update_comentario_supervisor();
$resUpdate = $this->ejecutarConsultaBdds($sql);

// 3. Verificar filas afectadas
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