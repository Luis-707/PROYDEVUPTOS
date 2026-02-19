<?php
session_start();
include_once '../clases/Listados.php';

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new Listados($this);

// ======================================================
// Seleccionar SQL según rol
// ======================================================

$sql = "";

// ---------------------------------------------
// Caso 1: Usuario es EVALUADO
// ---------------------------------------------
if (in_array("evaluado", $rolesSesion)) {
    $sql = Listados::sql_listar_por_evaluado($idUsuarioSesion);
}

// ---------------------------------------------
// Caso 2: Usuario es SUPERVISOR DEL EVALUADOR
// ---------------------------------------------
elseif (in_array("supervisor del evaluador", $rolesSesion)) {
    $sql = Listados::sql_listar_por_supervisor($idUsuarioSesion);
}

// ---------------------------------------------
// Ningún rol válido
// ---------------------------------------------
else {
    echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
    exit;
}

// ======================================================
// Ejecutar consulta
// ======================================================
$respuesta = $Lista->listarEvaluadosComentarios($sql);
echo json_encode(["success" => true, "data" => $respuesta]);
exit;