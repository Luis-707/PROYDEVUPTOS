<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once '../clases/Listados.php';

$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion  = $_SESSION['usuario']['roles'] ?? [];

if (!$cedulaSesion || empty($rolesSesion)) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

$ListaR = new Listados($this);

// Seleccionar SQL según rol
if (in_array("evaluador", $rolesSesion)) {

    $sql = Listados::sql_reportes_por_evaluador($cedulaSesion);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Rol no autorizado"
    ]);
    exit;
}

$respuesta = $ListaR->listarReportes($sql);

// Si listarReportes devuelve array, lo enviamos como JSON
if (is_array($respuesta)) {
    echo json_encode([
        "success" => true,
        "data" => $respuesta
    ]);
}

exit;
