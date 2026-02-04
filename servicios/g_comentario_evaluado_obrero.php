<?php
session_start();
include_once "../clases/Planilla_comentarios_obreros.php";

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
if (!$cedulaSesion) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$data = $dataCliente['_post'] ?? $_POST ?? [];
$planilla = new Planilla_comentarios_obreros($data, $this);

// 1. Validar propiedad de la evaluación
$sql = $planilla->sql_buscar_por_id_y_evaluado_obrero($cedulaSesion);
$res = $this->ejecutarConsultaBdds($sql);

if (empty($res) || empty($res[0])) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autorizado para actualizar este comentario"
    ]);
    exit;
}

// 2. Ejecutar UPDATE
$sql = $planilla->sql_update_comentario_evaluado_obrero();
$update = $this->ejecutarConsultaBdds($sql);

if (empty($update) || empty($update[0])) {
    echo json_encode([
        "success" => false,
        "message" => "No se actualizó ningún registro"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "Comentario del evaluado actualizado correctamente"
]);
exit;