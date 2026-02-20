<?php
session_start();
include_once '../clases/ResultadosObreros.php';

$idUsuarioSesion = $_SESSION['usuario']['id_usuario'] ?? null;
$rolesSesion     = $_SESSION['usuario']['roles'] ?? [];

if (!$idUsuarioSesion || empty($rolesSesion)) {
    echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
    exit;
}

$Resultados = new ResultadosObreros($this);

// ======================================================
// Seleccionar SQL según rol
// ======================================================

$sql = "";

// Caso 1: Evaluador obrero
if (in_array("evaluador", $rolesSesion)) {
    $sql = ResultadosObreros::sql_listar_por_evaluador($idUsuarioSesion);
}

// Caso 2: Supervisor del evaluador obrero
elseif (in_array("supervisor del evaluador", $rolesSesion)) {
    $sql = ResultadosObreros::sql_listar_por_supervisor($idUsuarioSesion);
}

// Ningún rol válido
else {
    echo json_encode(["success" => false, "message" => "Rol no autorizado"]);
    exit;
}

// ======================================================
// Ejecutar consulta
// ======================================================
$respuesta = $Resultados->listarResultados($sql);

echo json_encode([
    "success" => true,
    "data"    => $respuesta
]);
exit;
