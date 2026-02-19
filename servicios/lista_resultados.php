<?php
session_start();
include_once '../clases/ResultadosAdmin.php';

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Resultados = new ResultadosAdmin($this);

// ======================================================
// Seleccionar SQL según rol (MISMA LÓGICA QUE EL DE REFERENCIA)
// ======================================================

$sql = "";

// ---------------------------------------------
// Caso 1: Usuario es EVALUADOR
// ---------------------------------------------
if (in_array("evaluador", $rolesSesion)) {
    $sql = ResultadosAdmin::sql_listar_por_evaluador($idUsuarioSesion);
}

// ---------------------------------------------
// Caso 2: Usuario es SUPERVISOR DEL EVALUADOR
// ---------------------------------------------
elseif (in_array("supervisor del evaluador", $rolesSesion)) {
    $sql = ResultadosAdmin::sql_listar_por_supervisor($idUsuarioSesion);
}

// ---------------------------------------------
// Ningún rol válido
// ---------------------------------------------
else {
    echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
    exit;
}

// ======================================================
// Ejecutar consulta (SIN NORMALIZACIÓN EXTRA)
// ======================================================
$respuesta = $Resultados->listarResultados($sql);

echo json_encode([
    "success" => true,
    "data"    => $respuesta
]);
exit;