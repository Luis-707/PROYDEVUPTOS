<?php
session_start();
include_once "../clases/Listados.php";

header('Content-Type: application/json; charset=utf-8');

// =============================
// Validación de sesión
// =============================
$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$cedulaSesion    = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || !$cedulaSesion || empty($rolesSesion)) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

$Listados = new Listados($this);

// =============================
// Selección de consulta según rol
// =============================

// 1) Si es evaluado → listar sus propias evaluaciones obreras finalizadas
if (in_array("evaluado", $rolesSesion)) {
    $sql = Listados::sql_listar_comentarios_obrero_por_evaluado($cedulaSesion);
}

// 2) Si es supervisor del evaluador → listar evaluaciones de sus subordinados
elseif (in_array("supervisor del evaluador", $rolesSesion)) {
    $sql = Listados::sql_listar_comentarios_obrero_por_supervisor((int)$idUsuarioSesion);
}

// 3) Cualquier otro rol → no autorizado
else {
    echo json_encode([
        "success" => false,
        "message" => "Rol no autorizado para ver comentarios obreros"
    ]);
    exit;
}

// =============================
// Ejecutar consulta
// =============================
$resp = $Listados->listarComentariosEvaluadosObreros($sql);

// La clase Listados devuelve un arreglo con índice 0
$rows = $resp[0] ?? [];

// =============================
// Respuesta final
// =============================
echo json_encode([
    "success" => true,
    "data"    => $rows
]);
exit;