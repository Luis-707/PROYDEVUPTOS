<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

include_once "../clases/ReportesPlanillaAdmin.php";

// Validar sesión
$cedulaSesion = $_SESSION['usuario']['cedula'] ?? null;
$rolesSesion  = $_SESSION['usuario']['roles']  ?? null;

if (!$cedulaSesion || empty($rolesSesion)) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no autenticado"
    ]);
    exit;
}

// Instanciar clase con conexión real
$reporte = new ReportesPlanillaAdmin($this);

// Ejecutar consulta
$respuesta = $reporte->listarReportesAdmin();

// Si no hay evaluaciones disponibles
if (empty($respuesta)) {
    echo json_encode([
        "success" => true,
        "data"    => [],
        "message" => "No hay evaluaciones disponibles para reporte"
    ]);
    exit;
}

// Respuesta normal
echo json_encode([
    "success" => true,
    "data"    => $respuesta
]);
exit;
?>