<?php
session_start();
include_once '../clases/Listados.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion  = $_SESSION['usuario']['roles'] ?? [];   // AHORA ES UN ARRAY

if (!$cedulaSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Lista = new Listados($this);

// ======================================================
// Seleccionar SQL según rol (MISMA LÓGICA DEL ORIGINAL)
// ======================================================

$sql = "";

// ---------------------------------------------
// Caso 1: Usuario es EVALUADO
// ---------------------------------------------
if (in_array("evaluado", $rolesSesion)) {
    $sql = Listados::sql_listar_por_evaluado($cedulaSesion);
}

// ---------------------------------------------
// Caso 2: Usuario es SUPERVISOR DEL EVALUADOR
// ---------------------------------------------
elseif (in_array("supervisor del evaluador", $rolesSesion)) {
    $sql = Listados::sql_listar_por_supervisor($cedulaSesion);
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
return $respuesta;
